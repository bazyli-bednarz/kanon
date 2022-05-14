<?php
/**
 * User fixtures.
 */

namespace App\DataFixtures;

use App\Entity\User;
use DateTimeImmutable;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Class UserFixtures.
 */
class UserFixtures extends AbstractBaseFixtures
{
    /**
     * Password hasher.
     */
    private UserPasswordHasherInterface $passwordHasher;

    /**
     * @param UserPasswordHasherInterface $passwordHasher Password hasher
     */
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * Load data.
     */
    protected function loadData(): void
    {
        $this->createMany(3, 'users', function ($i) {
            $user = new User();
            $user->setEmail(sprintf('user%d@kanon.pl', $i));
            $user->setName($this->faker->firstName());
            $user->setCreatedAt(
                DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-100 days', '-1 days')
                )
            );
            $user->setRoles([User::ROLE_USER]);
            $user->setIsVerified(true);
            $user->setExperience(0);
            $user->setLevel(1);
            $user->setImage($this->faker->numberBetween(1,20));

            $user->setPassword(
                $this->passwordHasher->hashPassword(
                    $user,
                    'user'
                )
            );

            return $user;
        });

        $this->createMany(1, 'admins', function ($i) {
            $user = new User();
            $user->setEmail(sprintf('admin%d@kanon.pl', $i));
            $user->setName('Admin');
            $user->setCreatedAt(
                DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-100 days', '-1 days')
                )
            );
            $user->setRoles([User::ROLE_USER, User::ROLE_ADMIN]);
            $user->setIsVerified(true);
            $user->setImage($this->faker->numberBetween(11,20));
            $user->setPassword(
                $this->passwordHasher->hashPassword(
                    $user,
                    'admin'
                )
            );
            $user->setExperience(0);
            $user->setLevel(10);

            return $user;
        });

        $this->manager->flush();
    }
}
