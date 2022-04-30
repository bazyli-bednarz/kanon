<?php
/**
 * Tag service interface.
 */

namespace App\Service;

use App\Entity\Tag;

/**
 * Interface TagServiceInterface.
 */
interface TagServiceInterface
{
    /**
     * Save entity.
     *
     * @param Tag $tag Tag entity
     */
    public function save(Tag $tag): void;

    /**
     * Find by name.
     *
     * @param string $name Tag name
     *
     * @return Tag|null Tag entity
     */
    public function findOneByName(string $name): ?Tag;
}
