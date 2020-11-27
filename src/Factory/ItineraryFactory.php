<?php

namespace App\Factory;

use App\Entity\Itinerary;
use App\Repository\ItineraryRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @method static Itinerary|Proxy findOrCreate(array $attributes)
 * @method static Itinerary|Proxy random()
 * @method static Itinerary[]|Proxy[] randomSet(int $number)
 * @method static Itinerary[]|Proxy[] randomRange(int $min, int $max)
 * @method static ItineraryRepository|RepositoryProxy repository()
 * @method Itinerary|Proxy create($attributes = [])
 * @method Itinerary[]|Proxy[] createMany(int $number, $attributes = [])
 */
final class ItineraryFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function getDefaults(): array
    {
        return [
            'departureAddress' => self::faker()->address,
            'arrivalAddress' => self::faker()->address,
            'dateDeparture' => self::faker()->dateTimeBetween('-1 day', '+2 days'),
            'state' => 'active',
            'description' => self::faker()->text('50')        ];
    }

    protected function initialize(): self
    {
        // see https://github.com/zenstruck/foundry#initialization
        return $this
            ->afterInstantiate(function(Itinerary $itinerary) {
                $itinerary->setDateArrival(self::faker()->dateTimeBetween($itinerary->getDateDeparture()->format('Y/m/d h:i'), '+ 5 days'));
            })
        ;
    }

    protected static function getClass(): string
    {
        return Itinerary::class;
    }
}
