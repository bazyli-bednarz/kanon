<?php

namespace App\DataFixtures;

use App\Entity\Canon;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

/**
 * Class CanonFixtures
 */
class CanonFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{

    /**
     * Loads canon data.
     */
    public function loadData(): void
    {
        if (null === $this->manager || null === $this->faker) {
            return;
        }
        $sampleCanons = [
            ['Twórczość Fryderyka Chopina', 'Materiały do przygotowania na konkurs wiedzy o Fryderyku Chopinie.'],
            ['Utwory XX wieku', ''],
            ['Klasycyzm klasa V', 'Lista utworów z kanonu historii muzyki dla uczniów klasy V'],
            ['Kanon końcowy', 'Kanon z utworami wymaganymi na egzamin dyplomowy z historii muzyki'],
        ];

        $this->createMany(count($sampleCanons), 'canons', function (int $i) use ($sampleCanons) {
            $canon = new Canon();
            $canon->setName($sampleCanons[$i][0]);
            $canon->setDescription($sampleCanons[$i][1]);
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
