<?php

namespace App\Factory;
use DateTime;
use App\Entity\User;
use App\Repository\UserRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;


/**
 * @extends ModelFactory<User>
 *
 * @method        User|Proxy create(array|callable $attributes = [])
 * @method static User|Proxy createOne(array $attributes = [])
 * @method static User|Proxy find(object|array|mixed $criteria)
 * @method static User|Proxy findOrCreate(array $attributes)
 * @method static User|Proxy first(string $sortedField = 'id')
 * @method static User|Proxy last(string $sortedField = 'id')
 * @method static User|Proxy random(array $attributes = [])
 * @method static User|Proxy randomOrCreate(array $attributes = [])
 * @method static UserRepository|RepositoryProxy repository()
 * @method static User[]|Proxy[] all()
 * @method static User[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static User[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static User[]|Proxy[] findBy(array $attributes)
 * @method static User[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static User[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class UserFactory extends ModelFactory
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
        $maleAvatarUrls = [
            'https://cdn.icon-icons.com/icons2/582/PNG/512/man-2_icon-icons.com_55041.png',
            'https://cdn.icon-icons.com/icons2/1879/PNG/512/iconfinder-8-avatar-2754583_120515.png',
            'https://cdn.icon-icons.com/icons2/1736/PNG/512/4043260-avatar-male-man-portrait_113269.png',
        ];

        $femaleAvatarUrls = [
            'https://cdn.icon-icons.com/icons2/1736/PNG/512/4043247-1-avatar-female-portrait-woman_113261.png',
            'https://cdn.icon-icons.com/icons2/1736/PNG/512/4043251-avatar-female-girl-woman_113291.png',
            'https://cdn.icon-icons.com/icons2/1879/PNG/512/iconfinder-4-avatar-2754580_120522.png',
        ];

        $gender = self::faker()->randomElement(['male', 'female']);
        $avatarUrls = $gender === 'male' ? $maleAvatarUrls : $femaleAvatarUrls;

        return [
            'email' => self::faker()->email(),
            'first_name' => self::faker()->firstName(),
            'gender' => $gender,
            'isVerified' => true,
            'last_name' => self::faker()->lastName(),
            'password' => '$2y$13$k1v3SKo/t3grS5Oo3/yYieiO2pMg1gMWugsV7X.hbZMkmsgpfVxN6',
            'roles' =>self::faker()->randomElement([['ROLE_USER'], ['ROLE_ADMIN']]),
            'username' =>  substr(self::faker()->firstName, 0, 10),
            'bio'=>self::faker()->paragraph(),
            'Last_login_date'=>new \DateTime(),
            'avatar' => self::faker()->randomElement($avatarUrls),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(User $user): void {})
        ;
    }

    protected static function getClass(): string
    {
        return User::class;
    }
}
