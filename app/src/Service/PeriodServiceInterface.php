<?php
/**
 * Period service interface.
 */

namespace App\Service;

use App\Entity\Period;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface PeriodServiceInterface.
 */
interface PeriodServiceInterface
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
     * Get paginated list of pieces by period.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedListByPeriod(int $page, Period $period): PaginationInterface;

    /**
     * Save entity.
     *
     * @param Period $period Period entity
     */
    public function save(Period $period): void;

    /**
     * Delete entity.
     *
     * @param Period $period Period entity
     */
    public function delete(Period $period): void;

    /**
     * Can Period object be deleted?
     *
     * @param Period $period Period entity
     *
     * @return bool Result
     */
    public function canBeDeleted(Period $period): bool;
}
