<?php

namespace App\Factory;

use App\Entity\Discussion;
use App\Repository\DiscussionRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @method static Discussion|Proxy findOrCreate(array $attributes)
 * @method static Discussion|Proxy random()
 * @method static Discussion[]|Proxy[] randomSet(int $number)
 * @method static Discussion[]|Proxy[] randomRange(int $min, int $max)
 * @method static DiscussionRepository|RepositoryProxy repository()
 * @method Discussion|Proxy create($attributes = [])
 * @method Discussion[]|Proxy[] createMany(int $number, $attributes = [])
 */
final class DiscussionFactory extends ModelFactory
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
            // ->afterInstantiate(function(Discussion $discussion) {})
        ;
    }

    protected static function getClass(): string
    {
        return Discussion::class;
    }
}
