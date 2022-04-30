<?php
/**
 * Scale service interface.
 */

namespace App\Service;

use App\Entity\Scale;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface ScaleServiceInterface.
 */
interface ScaleServiceInterface
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
     * Get paginated list of pieces by scale.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedListByScale(int $page, Scale $scale): PaginationInterface;

    /**
     * Save entity.
     *
     * @param Scale $scale Scale entity
     */
    public function save(Scale $scale): void;

    /**
     * Delete entity.
     *
     * @param Scale $scale Scale entity
     */
    public function delete(Scale $scale): void;

    /**
     * Can Scale object be deleted?
     *
     * @param Scale $scale Scale entity
     *
     * @return bool Result
     */
    public function canBeDeleted(Scale $scale): bool;
}
