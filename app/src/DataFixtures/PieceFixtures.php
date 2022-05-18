<?php

namespace App\DataFixtures;

use App\Entity\Composer;
use App\Entity\Piece;
use App\Entity\Scale;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class PieceFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{

    public function loadData(): void
    {
        if (null === $this->manager || null === $this->faker) {
            return;
        }

        $this->createMany(100, 'pieces', function (int $i) {
            $piece = new Piece();
            $piece->setName($this->faker->sentence());
            $piece->setDescription($this->faker->realText());
            $piece->setYear(intval($this->faker->year('-100 years')));
            $link = substr($this->faker->youtubeShortUri(),17).'?start='.$this->faker->randomNumber(3);
            $piece->setLink($link);
            $piece->setCreatedAt(
                DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-100 days', '-1 days')
                )
            );
            $piece->setUpdatedAt(
                DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-100 days', '-1 days')
                )
            );
            /** @var Composer $composer */
            $composer = $this->getRandomReference('composers');
            $piece->setComposer($composer);

            /** @var Scale $scale */
            $scale = $this->getRandomReference('scales');
            $piece->setScale($scale);

            $tags = $this->getRandomReferences('tags', $this->faker->numberBetween(0,3));
            $tagsCount = count($tags);
            if ($tagsCount) {
                for ($i = 1; $i <= $tagsCount; $i++) {
                    $piece->addTag($tags[$i-1]);
                }
            }

            $canons = $this->getRandomReferences('canons', $this->faker->numberBetween(0,3));
            $canonsCount = count($canons);
            if ($canonsCount) {
                for ($i = 1; $i <= $canonsCount; $i++) {
                    $piece->addCanon($canons[$i-1]);
                }
            }
            /** @var User $author */
            $author = $this->getRandomReference('users');
            $piece->setAuthor($author);
            $piece->setEditedBy($author);

            return $piece;
        });

        $this->manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on.
     *
     * @return string[] of dependencies
     *
     * @psalm-return array{0: ComposerFixtures::class, 1: ScaleFixtures:class}
     */
    public function getDependencies(): array
    {
        return [ComposerFixtures::class, ScaleFixtures::class, TagFixtures::class, CanonFixtures::class, UserFixtures::class];
    }

//        $piece = new Piece();
//        $piece->setName('Preludium Deszczowe');
//        $piece->setDescription('Jedno z najpiękniejszych preludiów Chopina.');
//        $piece->setYear(1839);
//        $piece->setLink('https://youtu.be/bJmO1g1jlKQ?t=1');
//        $piece->setCreatedAt(DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-100 days', '-1 days')));
//        $piece->setUpdatedAt(DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-100 days', '-1 days')));
//
//        $this->manager->persist($piece);
//
//
//        $piece2 = new Piece();
//        $piece2->setName('Etiuda Rewolucyjna');
//        $piece2->setDescription('Dzieło powstało, kiedy kompozytor dowiedział się o upadku powstania listopadowego.');
//        $piece2->setYear(1831);
//        $piece2->setLink('https://youtu.be/X0UJEx9S1bU?t=3');
//        $piece2->setCreatedAt(DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-100 days', '-1 days')));
//        $piece2->setUpdatedAt(DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-100 days', '-1 days')));
//
//        $this->manager->persist($piece2);
//
//        $piece3 = new Piece();
//        $piece3->setName('Koncert fortepianowy');
//        $piece3->setDescription('Jest to jeden z dwóch koncertów Chopina. Wbrew numeracji opusowej to właśnie II Koncert fortepianowy f-moll op. 21 napisany został jako pierwszy.');
//        $piece3->setYear(1829);
//        $piece3->setLink('https://youtu.be/rawF-OsPE70?t=240');
//        $piece3->setCreatedAt(DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-100 days', '-1 days')));
//        $piece3->setUpdatedAt(DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-100 days', '-1 days')));
//
//        $this->manager->persist($piece3);
//
//        $piece4 = new Piece();
//        $piece4->setName('Nokturn op. 9 no. 2');
//        $piece4->setDescription('Najbardziej znany nokturn Chopina, jest w metrum 12/8.');
//        $piece4->setYear(1830);
//        $piece4->setLink('https://youtu.be/9E6b3swbnWg?t=1');
//        $piece4->setCreatedAt(DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-100 days', '-1 days')));
//        $piece4->setUpdatedAt(DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-100 days', '-1 days')));
//
//        $this->manager->persist($piece4);
//
//        $piece5 = new Piece();
//        $piece5->setName('Uwertura koncertowa “Coriolan” op. 62');
//        $piece5->setDescription('Muzyczna ilustracja dramatu austriackiego poety Heinricha von Collina.');
//        $piece5->setYear(1807);
//        $piece5->setLink('https://youtu.be/D1PKSoi9lNk?t=7');
//        $piece5->setCreatedAt(DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-100 days', '-1 days')));
//        $piece5->setUpdatedAt(DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-100 days', '-1 days')));
//
//        $this->manager->persist($piece5);
//
//
//        $piece6 = new Piece();
//        $piece6->setName('Sonata fortepianowa C-dur op. 53 “Waldsteinowska”');
//        $piece6->setDescription('Należy do kluczowych dzieł środkowego, "heroicznego" okresu twórczości kompozytora.');
//        $piece6->setYear(1804);
//        $piece6->setLink('https://youtu.be/TDagcm5Nl4s?t=15');
//        $piece6->setCreatedAt(DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-100 days', '-1 days')));
//        $piece6->setUpdatedAt(DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-100 days', '-1 days')));
//
//        $this->manager->persist($piece6);
//
//        $piece7 = new Piece();
//        $piece7->setName('Requiem d-moll KV 626');
//        $piece7->setDescription('Jest jednym z największych utworów sakralnych Mozarta, a zarazem jego ostatnią, niedokończoną kompozycją.');
//        $piece7->setYear(1791);
//        $piece7->setLink('https://youtu.be/O20HzXEX_xU?t=35');
//        $piece7->setCreatedAt(DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-100 days', '-1 days')));
//        $piece7->setUpdatedAt(DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-100 days', '-1 days')));
//
//        $this->manager->persist($piece7);
//
//        $this->manager->flush();

}
