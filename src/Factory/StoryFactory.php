<?php

namespace App\Factory;

use App\Entity\Story;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends PersistentObjectFactory<Story>
 */
final class StoryFactory extends PersistentObjectFactory
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
    protected function defaults(): array
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
    public static function class(): string
    {
        return Story::class;
    }
}
