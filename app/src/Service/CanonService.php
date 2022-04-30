<?php
/**
 * Canon service.
 */

namespace App\Service;

use App\Entity\Canon;
use App\Repository\CanonRepository;
use App\Repository\PieceRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class CanonService.
 */
class CanonService implements CanonServiceInterface
{
    /**
     * Canon repository.
     */
    private CanonRepository $canonRepository;

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
     * @param CanonRepository     $canonRepository Canon repository
     * @param PaginatorInterface $paginator      Paginator
     */
    public function __construct(CanonRepository $canonRepository, PieceRepository $pieceRepository, PaginatorInterface $paginator)
    {
        $this->canonRepository = $canonRepository;
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
            $this->canonRepository->queryAll(),
            $page,
            CanonRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    public function getPaginatedListByCanon(int $page, Canon $canon): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->pieceRepository->queryByCanon($canon),
            $page,
            PieceRepository::PAGINATOR_ITEMS_PER_PAGE_CANON
        );
    }

    /**
     * Save entity.
     *
     * @param Canon $canon Canon entity
     */
    public function save(Canon $canon): void
    {
        $this->canonRepository->save($canon);
    }

    public function delete(Canon $canon): void
    {
        $this->canonRepository->delete($canon);
    }

    public function canBeDeleted(Canon $canon): bool
    {
        try {
            $result = $this->pieceRepository->countByCanon($canon);

            return !($result > 0);
        } catch (NoResultException|NonUniqueResultException $exception) {
            return false;
        }
    }
}
