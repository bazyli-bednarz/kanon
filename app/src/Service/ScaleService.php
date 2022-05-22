<?php
/**
 * Scale service.
 */

namespace App\Service;

use App\Entity\Scale;
use App\Repository\ScaleRepository;
use App\Repository\PieceRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class ScaleService.
 */
class ScaleService implements ScaleServiceInterface
{
    /**
     * Scale repository.
     */
    private ScaleRepository $scaleRepository;

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
     * @param ScaleRepository     $scaleRepository Scale repository
     * @param PaginatorInterface $paginator      Paginator
     */
    public function __construct(ScaleRepository $scaleRepository, PieceRepository $pieceRepository, PaginatorInterface $paginator)
    {
        $this->scaleRepository = $scaleRepository;
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
            $this->scaleRepository->queryAll(),
            $page,
            ScaleRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    public function getPaginatedListByScale(int $page, Scale $scale): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->pieceRepository->queryByScale($scale),
            $page,
            PieceRepository::PAGINATOR_ITEMS_PER_PAGE_SCALE
        );
    }

    /**
     * Save entity.
     *
     * @param Scale $scale Scale entity
     */
    public function save(Scale $scale): void
    {
        $this->scaleRepository->save($scale);
    }

    public function delete(Scale $scale): void
    {
        $this->scaleRepository->delete($scale);
    }

    public function findOneBySlug(string $slug): ?Scale
    {
        return $this->scaleRepository->findOneBySlug($slug);
    }

    public function canBeDeleted(Scale $scale): bool
    {
        try {
            $result = $this->pieceRepository->countByScale($scale);

            return !($result > 0);
        } catch (NoResultException|NonUniqueResultException $exception) {
            return false;
        }
    }
}
