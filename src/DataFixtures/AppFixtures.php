<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\Admin;
use App\Entity\Comment;
use App\Entity\Itinerary;
use App\Entity\Message;
use App\Entity\Product;
use App\Entity\Tag;
use App\Entity\User;
use App\Factory\AddressFactory;
use App\Factory\AdminFactory;
use App\Factory\CommentFactory;
use App\Factory\ItineraryFactory;
use App\Factory\ProductFactory;
use App\Factory\RoleFactory;
use App\Factory\TagFactory;
use App\Factory\UserFactory;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\Role;
use function Zenstruck\Foundry\faker;

class AppFixtures extends Fixture
{
    private $encoder;
    private $manager;
    private $userRepository;

    public function __construct(UserPasswordEncoderInterface $encoder, EntityManagerInterface $manager, UserRepository $userRepository)
    {
        $this->encoder = $encoder;
        $this->manager = $manager;
        $this->userRepository = $userRepository;
    }

    public function load(ObjectManager $manager)
    {

        $roles = [
            'admin' => 'Administrateur',
            'moderator' => 'Modérateur',
            'producer' => 'Producteur',
            'user' => 'Utilisateur',
        ];

        // Users : seront créés en dur pour pouvoir les manipuler en attendant le module de sécurité
        $userGroup = array(
            'admin' => ['blandine', 'raphael', 'maureen', 'ryme', 'thomas'],
            'moderator' => ['john', 'jeanette', 'sol', 'lamia', 'martin'],
            'producer' => ['jean','claude'],
            'user' => ['remy', 'roland', 'marc'],
        );


        foreach ($userGroup as $roleGroup => $users) {
            // Role

            $role = new Role();
            $role->setRoleName('ROLE_' . mb_strtoupper($roleGroup));
            $role->setName($roles[$roleGroup]);
            $manager->persist($role);
            $manager->flush();


            foreach ($users as $u) {

                    if ($role->getName() === 'Utilisateur' || $role->getName() === 'Producteur') {

                        $user = UserFactory::new()->create([
                            'role' => $role,
                            'username' => \trim($u),
                            ])->object();
                        print 'Adding user ' . $user->getUsername() . PHP_EOL;

                        AddressFactory::new()->create(['user' => $user]);
                        print 'Adding itinerary' ;
                        $itinerary = ItineraryFactory::new()->create()->object();
                        $itinerary->addUser($user);

                        if ($user->isProducer()) {

                            for ($z = 0; $z < 5; $z++) {
                                ProductFactory::new()->create(['user' => $user]);

                            }
                            for ($e = 0; $e < 5; $e++) {

                                $user->addTag(TagFactory::new()->create()->object());
                            }
                        }

                    } elseif ($role->getName() === 'Administrateur') {

                        print 'role id : ' . $role->getId() . PHP_EOL;
                        AdminFactory::new()->create([
                            'username' => $u,
                            'role' => $role
                        ]);

                    }
            }

          /*  $users = $this->userRepository->findAll();
            foreach ($users as $user) {
                $addressee = $this->userRepository->findOneRandom($user);
                CommentFactory::new()->create([
                    'author' => $user,
                    'addressee' => $addressee
                ]);
            }*/

        }


    }


}
