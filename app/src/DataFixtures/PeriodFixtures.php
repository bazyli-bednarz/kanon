<?php
/**
 * Period fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Period;
use DateTimeImmutable;

/**
 * Class PeriodFixtures.
 *
 * @psalm-suppress MissingConstructor
 */
class PeriodFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     * @psalm-suppress PossiblyNullReference
     * @psalm-suppress UnusedClosureParam
     */
    public function loadData(): void
    {
        $periods = [
            ['period.ancient', 'period.ancient_description', -3000, 1150],
            ['period.medieval', 'period.medieval_description', 1150, 1400],
            ['period.renaissance', 'period.renaissance_description', 1400, 1600],
            ['period.baroque', 'period.baroque_description', 1600, 1750],
            ['period.classical', 'period.classical_description', 1750, 1830],
            ['period.early_romantic', 'period.early_romantic_description', 1830, 1860],
            ['period.romantic', 'period.romantic_description', 1860, 1920],
            ['period.contemporary', 'period.contemporary_description', 1920, null],
            ['period.unknown',  'period.unknown_description', null, null],
            ['period.other', 'period.other_description', null, null]
        ];

        $this->createMany(count($periods), 'periods', function (int $i) use ($periods) {
            $period = new Period();
            $period->setName($periods[$i][0]);
            $period->setDescription($periods[$i][1]);
            $period->setStartYear($periods[$i][2]);
            $period->setEndYear($periods[$i][3]);
            $period->setCreatedAt(
                DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-100 days', '-1 days')
                )
            );
            $period->setUpdatedAt(
                DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-100 days', '-1 days')
                )
            );

            return $period;
        });

        $this->manager->flush();
    }
}
