<?php

namespace App\Factory;

use App\Entity\Address;
use App\Repository\AddressRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @method static Address|Proxy findOrCreate(array $attributes)
 * @method static Address|Proxy random()
 * @method static Address[]|Proxy[] randomSet(int $number)
 * @method static Address[]|Proxy[] randomRange(int $min, int $max)
 * @method static AddressRepository|RepositoryProxy repository()
 * @method Address|Proxy create($attributes = [])
 * @method Address[]|Proxy[] createMany(int $number, $attributes = [])
 */
final class AddressFactory extends ModelFactory
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
            'streetNumber' => self::faker()->buildingNumber,
            'streetName' => self::faker()->streetName,
            'Type' => self::faker()->citySuffix,
            'city' => self::faker()->city,
            'postCode'=> (int) self::faker()->postcode,
            'country' => self::faker()->country,
        ];
    }

    protected function initialize(): self
    {
        // see https://github.com/zenstruck/foundry#initialization
        return $this
            // ->afterInstantiate(function(Address $address) {})
        ;
    }

    protected static function getClass(): string
    {
        return Address::class;
    }
}
