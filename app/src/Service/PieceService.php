<?php
/**
 * Piece service.
 */

namespace App\Service;

use App\Entity\Piece;
use App\Repository\PieceRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class PieceService.
 */
class PieceService implements PieceServiceInterface
{
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
     * @param PieceRepository     $pieceRepository Piece repository
     * @param PaginatorInterface $paginator      Paginator
     */
    public function __construct(PieceRepository $pieceRepository, PaginatorInterface $paginator)
    {
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
            $this->pieceRepository->queryAll(),
            $page,
            PieceRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    public function save(Piece $piece): void
    {
        $this->pieceRepository->save($piece);
    }

    public function delete(Piece $piece): void
    {
        $this->pieceRepository->delete($piece);
    }

    public function canBeDeleted(Piece $piece): bool
    {
        try {
            return !count($piece->getCanons());
        } catch (NoResultException|NonUniqueResultException $exception) {
            return false;
        }
    }
}
