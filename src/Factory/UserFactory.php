<?php

namespace App\Factory;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use function Zenstruck\Foundry\faker;

/**
 * @method static User|Proxy findOrCreate(array $attributes)
 * @method static User|Proxy random()
 * @method static User[]|Proxy[] randomSet(int $number)
 * @method static User[]|Proxy[] randomRange(int $min, int $max)
 * @method static UserRepository|RepositoryProxy repository()
 * @method User|Proxy create($attributes = [])
 * @method User[]|Proxy[] createMany(int $number, $attributes = [])
 */
final class UserFactory extends ModelFactory
{
    private object $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        parent::__construct();

        $this->encoder = $encoder;
        // TODO inject services if required (https://github.com/zenstruck/foundry#factories-as-services)
    }

    protected function getDefaults(): array
    {
        return [
            // TODO add your default values here (https://github.com/zenstruck/foundry#model-factories)
            'username' => self::faker()->userName,
            'password' => self::faker()->password,
            'firstname' => self::faker()->firstName,
            'lastname' => self::faker()->lastName,
            'email' => self::faker()->safeEmail,
            'phone' => self::faker()->phoneNumber,
            'producer' => self::faker()->boolean(25),
            'image' => self::faker()->imageUrl(),

        ];
    }

    protected function initialize(): self
    {
        // see https://github.com/zenstruck/foundry#initialization
        return $this
            ->afterInstantiate(function(User $user) {
                if ($user->isProducer()) {
                    $user->setCompanyPro(faker()->company);
                    $user->setDescriptionPro(faker()->realText(50));
                    $user->setEmailPro(faker()->safeEmail);
                    $user->setImagePro(faker()->imageUrl());
                    $user->setSiretPro(faker()->iban());
                    $user->setWebsitePro(faker()->url);
                    $user->setPhonePro(faker()->phoneNumber);
                }
            })
            ->afterPersist(function (User $user) {
                $username = (string) $user->getUsername();
                $password = $this->encoder->encodePassword($user, $username);
                $user->setPassword($password);
            })
        ;
    }

    protected static function getClass(): string
    {
        return User::class;
    }
}
