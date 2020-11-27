<?php

namespace App\EventListener;
use ApiPlatform\Core\Api\IriConverterInterface;
use ApiPlatform\Core\Bridge\Symfony\Routing\IriConverter;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Lexik\Bundle\JWTAuthenticationBundle\LexikJWTAuthenticationBundle;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class AuthenticationSuccessListener
{
    private $iriConverter;
    private $token;
    private $logger;


    public function __construct(IriConverterInterface $iriConverter, TokenStorageInterface $token, LoggerInterface $logger)
    {
        $this->iriConverter = $iriConverter;
        $this->token = $token;
        $this->logger = $logger;


    }
    /**
     * @param AuthenticationSuccessEvent $event
     */
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event )
    {

        $data = $event->getData();
        $user = $event->getUser();

        $response = $event->getResponse();

        if (!$user instanceof UserInterface) {
            return;
        }

        $data['data'] = array(
            'roles'=> $user->getRoles(),
            'iri' => $this->iriConverter->getIriFromItem($user),
        );

        $context = [
            'response' => $response->getContent()
        ];

        $event->setData($data);


        $this->logger->notice('Authentication_Success_Event: ', $context);
    }
}