<?php

namespace App\Factory;

use App\Entity\Role;
use App\Repository\RoleRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @method static Role|Proxy findOrCreate(array $attributes)
 * @method static Role|Proxy random()
 * @method static Role[]|Proxy[] randomSet(int $number)
 * @method static Role[]|Proxy[] randomRange(int $min, int $max)
 * @method static RoleRepository|RepositoryProxy repository()
 * @method Role|Proxy create($attributes = [])
 * @method Role[]|Proxy[] createMany(int $number, $attributes = [])
 */
final class RoleFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();

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
            // ->afterInstantiate(function(Role $role) {})
        ;
    }

    protected static function getClass(): string
    {
        return Role::class;
    }
}
