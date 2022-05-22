<?php
/**
 * Composer fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Composer;
use App\Repository\PeriodRepository;
use App\Service\PeriodService;
use DateTimeImmutable;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

/**
 * Class ComposerFixtures.
 *
 * @psalm-suppress MissingConstructor
 */
class ComposerFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    private PeriodService $periodService;

    public function __construct(PeriodService $periodService)
    {
        $this->periodService = $periodService;
    }

    /**
     * Load data.
     *
     * @psalm-suppress PossiblyNullReference
     * @psalm-suppress UnusedClosureParam
     */
    public function loadData(): void
    {
        $sampleComposer = [
            ['Fryderyk', 'Chopin', 1810, 1849, 'Jeden z najwybitniejszych kompozytorów epoki romantyzmu i najważniejszy kompozytor w historii Polski. Wybitny pianista i kompozytor.', 'period-romantic'],
            ['Ludwig', 'van Beethoven', 1770, 1827, 'Jest uznawany na jednego z najwybitniejszych twórców wszechczasów. Tworzył muzykę nawet mimo postępującej głuchoty. Rozwinął formę sonatową i poszerzył obsadę orkiestry symfonicznej.', 'period-early-romantic'],
            ['Wolfgang Amadeus', 'Mozart', 1756, 1791, 'Austriacki kompozytor i wirtuoz. Był najwybitniejszym przedstawicielem epoki klasycyzmu.', 'period-classical'],
            ['Darius', 'Milhaud', 1892, 1974, 'Francuski kompozytor. Skomponował ponad 400 utworów, w tym opery, symfonie, balety i pieśni.', 'period-contemporary'],
            ['Gérard', 'Grisey', 1892, 1974, 'Francuski kompozytor i najwybitniejszy przedstawiciel techniki spektralizmu.', 'period-contemporary'],
            ['Igor', 'Stravinsky', 1892, 1974, 'Rosyjski kompozytor, pianista i dyrygent. Dał podwaliny dla modernizmu i współczesnej muzyki klasycznej.', 'period-contemporary'],
            ['Johann Sebastian', 'Bach', 1685, 1750, 'Niemiecki kompozytor i organista, twórca najsłynniejszych fug, mszy, oratoriów i kantat. Jedna z najważniejszych postaci w historii muzyki.', 'period-baroque'],
            ['Joseph', 'Haydn', 1685, 1750, 'Austriacki kompozytor, był jedną z osób, które zdefiniowały główne formy klasycyzmu: sonatę i symfonię.', 'period-classical'],
            ['Olivier', 'Messiaen', 1908, 1992, 'Francuski kompozytor i organista. Stworzył 7 własnych skali harmonicznych, które nazwał modi.', 'period-contemporary'],
        ];


        $this->createMany(count($sampleComposer), 'composers', function (int $i) use ($sampleComposer) {
            $composer = new Composer();
            $composer->setName($sampleComposer[$i][0]);
            $composer->setLastName($sampleComposer[$i][1]);
            $composer->setBirthYear($sampleComposer[$i][2]);
            $composer->setDeathYear($sampleComposer[$i][3]);
            $composer->setDescription($sampleComposer[$i][4]);

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

            $composer->setPeriod($this->periodService->findOneBySlug($sampleComposer[$i][5]));

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
