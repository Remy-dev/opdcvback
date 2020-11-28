<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\Token;
use App\Entity\User;
use App\Repository\RoleRepository;
use App\Repository\TokenRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Config\ContainerParametersResource;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;

class SecurityController extends AbstractController
{

    private $manager;
    private $userRepository;
    private $mailer;
    private $logger;
    private $token;

    public function __construct(EntityManagerInterface $manager, UserRepository $userRepository, MailerInterface $mailer, LoggerInterface $logger)
    {
        $this->manager = $manager;
        $this->userRepository = $userRepository;
        $this->mailer = $mailer;
        $this->logger = $logger;
        $this->token = new Token();
    }
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {

    }
    ###### functions below are for registration and mail sending ######
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request,UserPasswordEncoderInterface $passwordEncoder, SerializerInterface $serializer, RoleRepository $roleRepository)
    {
        try {
            $user = $serializer->deserialize(
                $request->getContent(),
                User::class,
                'json'
            );
        } catch (NotEncodableValueException $e) {
            return $this->json(
                [
                    "success" => false,
                    "error" => $e->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
        $role = $roleRepository->findOneByRoleName('ROLE_USER');
        $plainPassword = $user->getPassword();
        $encodedPassword = $passwordEncoder->encodePassword($user, $plainPassword);
        $user->setPassword($encodedPassword);
        $user->setRole($role);

        if($user->isProducer())
        {
            $role = $roleRepository->findOneByRoleName('ROLE_PRODUCER');
            $user->setRole($role);
        }

        $this->encodeToken($user);
        $this->manager->persist($user);
        $this->manager->persist($this->token);
        $this->manager->flush();
        $this->sendMail($user, $this->token);

        return new Response('', Response::HTTP_OK);
    }

    public function encodeToken($user)
    {
        $this->token->setValue(md5($user->getEmail()));
    }

    public function sendMail(User $user, Token $token)
    {

        $email = (new TemplatedEmail())
            ->from('alienmailer@example.com')
            ->to($user->getEmail())
            ->subject('Une autre façon de penser la consommation, un nouvel appuie pour les producteurs, un pas de plus pour un environnement préservé.')
            ->htmlTemplate('emails/welcome_notification.html.twig')
            ->context([
                'user' => $user ,
                'token' => $token,
                'expiration_date' => new \DateTime("+1 hour")

            ]);

        $this->mailer->send($email);
        return;
    }
    /**
     * @Route("/mail-validation/{id}/{token}", name="mail-validation", methods={"GET"})
     */
    public function mailValidation(User $user, $token,TokenRepository $tokenRepository, Request $request)
    {
        $this->logger->info('Registration from email ' . $request);

        $sanitizedToken = htmlspecialchars($token);


        if ($user instanceof UserInterface)
        {
            $fetchedToken = $tokenRepository->findOneBy(['value' => $sanitizedToken]);

            if (!empty($fetchedToken) && null != $fetchedToken)
            {
                $user->setActive(true);
                $this->manager->persist($user);
                $this->manager->remove($fetchedToken);
                $this->manager->flush();
                return $this->redirect('http://www.opdcv.com/login');

            }
            return new Response('', Response::HTTP_NOT_FOUND);
        }

        return new Response('', Response::HTTP_NOT_FOUND);

    }


}
