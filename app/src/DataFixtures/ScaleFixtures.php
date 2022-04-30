<?php
/**
 * Scale fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Scale;
use DateTimeImmutable;

/**
 * Class ScaleFixtures.
 *
 * @psalm-suppress MissingConstructor
 */
class ScaleFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     * @psalm-suppress PossiblyNullReference
     * @psalm-suppress UnusedClosureParam
     */
    public function loadData(): void
    {
        $scales = [
            'scale.c_major',
            'scale.c_minor',

            'scale.g_major',
            'scale.g_minor',

            'scale.d_major',
            'scale.d_minor',

            'scale.a_major',
            'scale.a_minor',

            'scale.e_major',
            'scale.e_minor',

            'scale.b_major',
            'scale.b_minor',

            'scale.f_sharp_major',
            'scale.f_sharp_minor',

            'scale.c_sharp_major',
            'scale.c_sharp_minor',

            'scale.f_major',
            'scale.f_minor',

            'scale.b_flat_major',
            'scale.b_flat_minor',

            'scale.e_flat_major',
            'scale.e_flat_minor',

            'scale.a_flat_major',
            'scale.a_flat_minor',

            'scale.d_flat_major',
            'scale.d_flat_minor',

            'scale.g_flat_major',
            'scale.g_flat_minor',

            'scale.chromatic',
            'scale.pentatonic',
            'scale.whole_tone',
            'scale.acoustic',
            'scale.ionian',
            'scale.dorian',
            'scale.phrygian',
            'scale.lydian',
            'scale.mixolydian',
            'scale.aeolian',
            'scale.locrian',
            'scale.blues',
            'scale.pentatonic',
            'scale.atonal',
            'scale.unknown',
            'scale.other',


        ];

        $this->createMany(count($scales), 'scales', function (int $i) use ($scales) {
            $scale = new Scale();
            $scale->setName($scales[$i]);
            $scale->setCreatedAt(
                DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-100 days', '-1 days')
                )
            );
            $scale->setUpdatedAt(
                DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-100 days', '-1 days')
                )
            );

            return $scale;
        });

        $this->manager->flush();
    }
}
