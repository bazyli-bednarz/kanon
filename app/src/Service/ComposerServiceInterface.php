<?php
/**
 * Composer service interface.
 */

namespace App\Service;

use App\Entity\Composer;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface ComposerServiceInterface.
 */
interface ComposerServiceInterface
{
    /**
     * Get paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page): PaginationInterface;

    /**
     * Get paginated list of pieces by composer.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedListByComposer(int $page, Composer $composer): PaginationInterface;

    /**
     * Save entity.
     *
     * @param Composer $composer Composer entity
     */
    public function save(Composer $composer): void;

    /**
     * Delete entity.
     *
     * @param Composer $composer Composer entity
     */
    public function delete(Composer $composer): void;

    /**
     * Can Composer object be deleted?
     *
     * @param Composer $composer Composer entity
     *
     * @return bool Result
     */
    public function canBeDeleted(Composer $composer): bool;
}
