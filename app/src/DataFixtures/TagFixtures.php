<?php
/**
 * Tag fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Tag;
use DateTimeImmutable;

/**
 * Class TagFixtures.
 *
 * @psalm-suppress MissingConstructor
 */
class TagFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     * @psalm-suppress PossiblyNullReference
     * @psalm-suppress UnusedClosureParam
     */
    public function loadData(): void
    {
        $tags = [
            'symfonia',
            'witalizm',
            'ekspresjonizm',
            'rondo',
            'sonata',
            'sonoryzm',
            'aleatoryzm',
            'impresjonizm',
            'fuga',
            'dodekafonia',
            'fortepian',
            'skrzypce',
            'fortepian preparowany',
            'altówka',
            'kontrabas',
        ];

        $this->createMany(count($tags), 'tags', function (int $i) use ($tags) {
            $tag = new Tag();
            $tag->setName($tags[$i]);
            $tag->setCreatedAt(
                DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-100 days', '-1 days')
                )
            );
            $tag->setUpdatedAt(
                DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-100 days', '-1 days')
                )
            );

            return $tag;
        });

        $this->manager->flush();
    }
}
