<?php
/**
 * Composer service.
 */

namespace App\Service;

use App\Entity\Composer;
use App\Repository\ComposerRepository;
use App\Repository\PieceRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class ComposerService.
 */
class ComposerService implements ComposerServiceInterface
{
    /**
     * Composer repository.
     */
    private ComposerRepository $composerRepository;

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
     * @param ComposerRepository     $composerRepository Composer repository
     * @param PaginatorInterface $paginator      Paginator
     */
    public function __construct(ComposerRepository $composerRepository, PieceRepository $pieceRepository, PaginatorInterface $paginator)
    {
        $this->composerRepository = $composerRepository;
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
            $this->composerRepository->queryAll(),
            $page,
            ComposerRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    public function getPaginatedListByComposer(int $page, Composer $composer): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->pieceRepository->queryByComposer($composer),
            $page,
            PieceRepository::PAGINATOR_ITEMS_PER_PAGE_COMPOSER
        );
    }

    /**
     * Save entity.
     *
     * @param Composer $composer Composer entity
     */
    public function save(Composer $composer): void
    {
        $this->composerRepository->save($composer);
    }

    public function delete(Composer $composer): void
    {
        $this->composerRepository->delete($composer);
    }

    public function canBeDeleted(Composer $composer): bool
    {
        try {
            $result = $this->pieceRepository->countByComposer($composer);

            return !($result > 0);
        } catch (NoResultException|NonUniqueResultException $exception) {
            return false;
        }
    }
}
