<?php
/**
 * User service.
 */

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\PieceRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class UserService.
 */
class UserService implements UserServiceInterface
{
    /**
     * User repository.
     */
    private UserRepository $userRepository;

    /**
     * Piece repository.
     */
    private PieceRepository $pieceRepository;

    /**
     * Paginator.
     */
    private PaginatorInterface $paginator;

    /**
     * Constructor.
     *
     * @param UserRepository     $userRepository User repository
     * @param PaginatorInterface $paginator      Paginator
     */
    public function __construct(UserRepository $userRepository, PieceRepository $pieceRepository, PaginatorInterface $paginator)
    {
        $this->userRepository = $userRepository;
        $this->pieceRepository = $pieceRepository;
        $this->paginator = $paginator;
    }

    /**
     * Get paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->userRepository->queryAll(),
            $page,
            UserRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    public function getPaginatedListByUserPieces(int $page, User $user): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->pieceRepository->queryByUser($user),
            $page,
            PieceRepository::PAGINATOR_ITEMS_PER_PAGE_USER
        );
    }

    public function getPaginatedListByUserCanons(int $page, User $user): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->pieceRepository->queryByUser($user),
            $page,
            PieceRepository::PAGINATOR_ITEMS_PER_PAGE_USER
        );
    }

}
