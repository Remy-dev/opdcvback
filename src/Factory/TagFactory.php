<?php

namespace App\Factory;

use App\Entity\Tag;
use App\Repository\TagRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @method static Tag|Proxy findOrCreate(array $attributes)
 * @method static Tag|Proxy random()
 * @method static Tag[]|Proxy[] randomSet(int $number)
 * @method static Tag[]|Proxy[] randomRange(int $min, int $max)
 * @method static TagRepository|RepositoryProxy repository()
 * @method Tag|Proxy create($attributes = [])
 * @method Tag[]|Proxy[] createMany(int $number, $attributes = [])
 */
final class TagFactory extends ModelFactory
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
            'name' => self::faker()->name
        ];
    }

    protected function initialize(): self
    {
        // see https://github.com/zenstruck/foundry#initialization
        return $this
            // ->afterInstantiate(function(Tag $tag) {})
        ;
    }

    protected static function getClass(): string
    {
        return Tag::class;
    }
}
