<?php

namespace App\Factory;

use App\Entity\User;
use DateTime;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends PersistentObjectFactory<User>
 */
final class UserFactory extends PersistentObjectFactory
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function defaults(): array
    {
        $gender = self::faker()->randomElement(['male', 'female']);
        $avatar = $gender === 'male' ? 'male.png' : 'female.png';

        return [
            'email' => self::faker()->email(),
            'first_name' => self::faker()->firstName(),
            'gender' => $gender,
            'isVerified' => true,
            'last_name' => self::faker()->lastName(),
            'password' => '$2y$13$k1v3SKo/t3grS5Oo3/yYieiO2pMg1gMWugsV7X.hbZMkmsgpfVxN6',
            'roles' =>self::faker()->randomElement([['ROLE_USER'], ['ROLE_ADMIN']]),
            'username' => self::faker()->unique()->userName(),
            'bio'=>self::faker()->sentence(8),
            'Last_login_date'=>new DateTime(),
            'avatar' => $avatar,
            'xp' => self::faker()->numberBetween(100, 99999),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    public static function class(): string
    {
        return User::class;
    }
}
