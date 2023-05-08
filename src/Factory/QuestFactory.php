<?php

namespace App\Factory;

use App\Entity\Quest;
use App\Repository\QuestRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Quest>
 *
 * @method        Quest|Proxy create(array|callable $attributes = [])
 * @method static Quest|Proxy createOne(array $attributes = [])
 * @method static Quest|Proxy find(object|array|mixed $criteria)
 * @method static Quest|Proxy findOrCreate(array $attributes)
 * @method static Quest|Proxy first(string $sortedField = 'id')
 * @method static Quest|Proxy last(string $sortedField = 'id')
 * @method static Quest|Proxy random(array $attributes = [])
 * @method static Quest|Proxy randomOrCreate(array $attributes = [])
 * @method static QuestRepository|RepositoryProxy repository()
 * @method static Quest[]|Proxy[] all()
 * @method static Quest[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Quest[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static Quest[]|Proxy[] findBy(array $attributes)
 * @method static Quest[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static Quest[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class QuestFactory extends ModelFactory
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
            'name' => self::faker()->text(50),
            'description' => self::faker()->text(),
            'points' => self::faker()->numberBetween(1, 100),
            'type' => self::faker()->randomElement(['daily', 'weekly', 'achievement', 'milestone']),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Quest $quest): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Quest::class;
    }
}
