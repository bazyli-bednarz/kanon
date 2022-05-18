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
     * Tag service.
     */
    private TagServiceInterface $tagService;

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
    public function __construct(PieceRepository $pieceRepository, PaginatorInterface $paginator, TagServiceInterface $tagService)
    {
        $this->pieceRepository = $pieceRepository;
        $this->paginator = $paginator;
        $this->tagService = $tagService;
    }

    private function prepareFilters(array $filters): array
    {
        $result = [];
        if (!empty($filters['tag_slug'])) {
            $tag = $this->tagService->findOneBySlug($filters['tag_slug']);
            if ($tag !== null) {
                $result['tag'] = $tag;
            }
        }

        return $result;
    }

    /**
     * Get paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page, array $filters = []): PaginationInterface
    {
        $filters = $this->prepareFilters($filters);

        return $this->paginator->paginate(
            $this->pieceRepository->queryAll($filters),
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
