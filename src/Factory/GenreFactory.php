<?php

namespace App\Factory;

use App\Entity\Genre;
use App\Repository\GenreRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Genre>
 *
 * @method        Genre|Proxy create(array|callable $attributes = [])
 * @method static Genre|Proxy createOne(array $attributes = [])
 * @method static Genre|Proxy find(object|array|mixed $criteria)
 * @method static Genre|Proxy findOrCreate(array $attributes)
 * @method static Genre|Proxy first(string $sortedField = 'id')
 * @method static Genre|Proxy last(string $sortedField = 'id')
 * @method static Genre|Proxy random(array $attributes = [])
 * @method static Genre|Proxy randomOrCreate(array $attributes = [])
 * @method static GenreRepository|RepositoryProxy repository()
 * @method static Genre[]|Proxy[] all()
 * @method static Genre[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Genre[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static Genre[]|Proxy[] findBy(array $attributes)
 * @method static Genre[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static Genre[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class GenreFactory extends ModelFactory
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
            'name' => self::faker()->word(),
            'description' => self::faker()->text(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Genre $genre): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Genre::class;
    }
}
