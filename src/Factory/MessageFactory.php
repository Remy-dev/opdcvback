<?php

namespace App\Factory;

use App\Entity\Message;
use App\Repository\MessageRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use function Zenstruck\Foundry\faker;

/**
 * @method static Message|Proxy findOrCreate(array $attributes)
 * @method static Message|Proxy random()
 * @method static Message[]|Proxy[] randomSet(int $number)
 * @method static Message[]|Proxy[] randomRange(int $min, int $max)
 * @method static MessageRepository|RepositoryProxy repository()
 * @method Message|Proxy create($attributes = [])
 * @method Message[]|Proxy[] createMany(int $number, $attributes = [])
 */
final class MessageFactory extends ModelFactory
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
            'content' => self::faker()->text(120)
        ];
    }

    protected function initialize(): self
    {
        // see https://github.com/zenstruck/foundry#initialization
        return $this
            // ->afterInstantiate(function(Message $message) {})
        ;
    }

    protected static function getClass(): string
    {
        return Message::class;
    }
}
