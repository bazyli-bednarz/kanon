<?php
/**
 * Canon service interface.
 */

namespace App\Service;

use App\Entity\Canon;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface CanonServiceInterface.
 */
interface CanonServiceInterface
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
     * Get paginated list of pieces by canon.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedListByCanon(int $page, Canon $canon): PaginationInterface;

    /**
     * Save entity.
     *
     * @param Canon $canon Canon entity
     */
    public function save(Canon $canon): void;

    /**
     * Delete entity.
     *
     * @param Canon $canon Canon entity
     */
    public function delete(Canon $canon): void;

    /**
     * Can Canon object be deleted?
     *
     * @param Canon $canon Canon entity
     *
     * @return bool Result
     */
    public function canBeDeleted(Canon $canon): bool;
}
