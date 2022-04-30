<?php

namespace App\DataFixtures;

use App\Entity\Canon;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CanonFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{

    public function loadData(): void
    {
        if (null === $this->manager || null === $this->faker) {
            return;
        }

        $this->createMany(10, 'canons', function (int $i) {
            $canon = new Canon();
            $canon->setName($this->faker->word() . ' ' . $this->faker->emoji());
            $canon->setDescription($this->faker->realText());
            $canon->setCreatedAt(
                DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-100 days', '-1 days')
                )
            );
            $canon->setUpdatedAt(
                DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-100 days', '-1 days')
                )
            );
            $canon->setVisibility($this->faker->boolean());

            /** @var User $author */
            $author = $this->getRandomReference('users');
            $canon->setAuthor($author);

            return $canon;
        });

        $this->manager->flush();
    }

    public function getDependencies(): array
    {
        return [UserFixtures::class];
    }
}
