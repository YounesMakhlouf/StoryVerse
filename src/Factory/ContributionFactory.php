<?php

namespace App\Factory;

use App\Entity\Contribution;
use App\Repository\ContributionRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Contribution>
 *
 * @method        Contribution|Proxy create(array|callable $attributes = [])
 * @method static Contribution|Proxy createOne(array $attributes = [])
 * @method static Contribution|Proxy find(object|array|mixed $criteria)
 * @method static Contribution|Proxy findOrCreate(array $attributes)
 * @method static Contribution|Proxy first(string $sortedField = 'id')
 * @method static Contribution|Proxy last(string $sortedField = 'id')
 * @method static Contribution|Proxy random(array $attributes = [])
 * @method static Contribution|Proxy randomOrCreate(array $attributes = [])
 * @method static ContributionRepository|RepositoryProxy repository()
 * @method static Contribution[]|Proxy[] all()
 * @method static Contribution[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Contribution[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static Contribution[]|Proxy[] findBy(array $attributes)
 * @method static Contribution[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static Contribution[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class ContributionFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function getDefaults(): array
    {
        return [
            'content' => self::faker()->text(),
            'createdAt' => self::faker()->dateTime(),
            'position' => self::faker()->numberBetween(1, 20),
            'updatedAt' => self::faker()->dateTime(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Contribution $contribution): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Contribution::class;
    }
}
