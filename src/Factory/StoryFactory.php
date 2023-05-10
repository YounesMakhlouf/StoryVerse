<?php

namespace App\Factory;

use App\Entity\Story;
use App\Repository\StoryRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Story>
 *
 * @method        Story|Proxy create(array|callable $attributes = [])
 * @method static Story|Proxy createOne(array $attributes = [])
 * @method static Story|Proxy find(object|array|mixed $criteria)
 * @method static Story|Proxy findOrCreate(array $attributes)
 * @method static Story|Proxy first(string $sortedField = 'id')
 * @method static Story|Proxy last(string $sortedField = 'id')
 * @method static Story|Proxy random(array $attributes = [])
 * @method static Story|Proxy randomOrCreate(array $attributes = [])
 * @method static StoryRepository|RepositoryProxy repository()
 * @method static Story[]|Proxy[] all()
 * @method static Story[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Story[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static Story[]|Proxy[] findBy(array $attributes)
 * @method static Story[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static Story[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class StoryFactory extends ModelFactory
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
        $genre=self::faker()->randomElement(['Horror', 'Fiction', 'Mystery','Comedy','Drama','Romance']);
        $storyImage=$genre.'.jpg';

        return [
            'createdAt' => self::faker()->dateTime(),
            'language' => self::faker()->randomElement(['french', 'english', 'arabic','italian','spanish']),
            'title' => self::faker()->sentence(5),
            'status'=>'pending',
            'genre'=>$genre,
            'storyImage'=>$storyImage
        ];
    }


    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Story $story): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Story::class;
    }
}
