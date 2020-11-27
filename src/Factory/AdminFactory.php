<?php

namespace App\Factory;

use App\Entity\Admin;
use App\Repository\AdminRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @method static Admin|Proxy findOrCreate(array $attributes)
 * @method static Admin|Proxy random()
 * @method static Admin[]|Proxy[] randomSet(int $number)
 * @method static Admin[]|Proxy[] randomRange(int $min, int $max)
 * @method static AdminRepository|RepositoryProxy repository()
 * @method Admin|Proxy create($attributes = [])
 * @method Admin[]|Proxy[] createMany(int $number, $attributes = [])
 */
final class AdminFactory extends ModelFactory
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

        ];
    }

    protected function initialize(): self
    {
        // see https://github.com/zenstruck/foundry#initialization
        return $this
            ->afterInstantiate(function(Admin $admin) {
                $username = $admin->getUsername();
                $password = $this->encoder->encodePassword($admin, $username);
            $admin->setPassword($password);
        });

    }

    protected static function getClass(): string
    {
        return Admin::class;
    }
}
