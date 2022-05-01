<?php
/**
 * Composer fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Composer;
use DateTimeImmutable;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

/**
 * Class ComposerFixtures.
 *
 * @psalm-suppress MissingConstructor
 */
class ComposerFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data.
     *
     * @psalm-suppress PossiblyNullReference
     * @psalm-suppress UnusedClosureParam
     */
    public function loadData(): void
    {
        $this->createMany(20, 'composers', function (int $i) {
            $composer = new Composer();
            $composer->setName($this->faker->firstName());
            $composer->setLastName($this->faker->lastName());
            $composer->setDescription($this->faker->realText());
            $year = intval($this->faker->year('-100 years'));
            $composer->setBirthYear($year);
            $composer->setDeathYear($year + $this->faker->numberBetween(20,100));

            $composer->setCreatedAt(
                DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-100 days', '-1 days')
                )
            );
            $composer->setUpdatedAt(
                DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-100 days', '-1 days')
                )
            );

            $period = $this->getRandomReference('periods');
            $composer->setPeriod($period);

            $author = $this->getRandomReference('users');
            $composer->setAuthor($author);
            $composer->setEditedBy($author);

            return $composer;
        });

        $this->manager->flush();
    }

    /**
     * @return string[] of depedencies
     */
    public function getDependencies() :array
    {
        return [PeriodFixtures::class, UserFixtures::class];
    }
}
