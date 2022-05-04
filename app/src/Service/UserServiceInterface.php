<?php
/**
 * User service interface.
 */

namespace App\Service;

use App\Entity\User;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface UserServiceInterface.
 */
interface UserServiceInterface
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
     * Get paginated list of pieces by user.
     *
     * @param int $page Page number
     * @param User $user User
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedListByUserPieces(int $page, User $user): PaginationInterface;

    /**
     * Get paginated list of canons by user.
     *
     * @param int $page Page number
     * @param User $user User
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedListByUserCanons(int $page, User $user): PaginationInterface;

    /**
     * Get paginated list of composers by user.
     *
     * @param int $page Page number
     * @param User $user User
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */

    public function getPaginatedListByUserComposers(int $page, User $user): PaginationInterface;

}
