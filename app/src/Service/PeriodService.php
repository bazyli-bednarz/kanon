<?php
/**
 * Period service.
 */

namespace App\Service;

use App\Entity\Period;
use App\Repository\PeriodRepository;
use App\Repository\ComposerRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class PeriodService.
 */
class PeriodService implements PeriodServiceInterface
{
    /**
     * Period repository.
     */
    private PeriodRepository $periodRepository;

    /**
     * Composer repository.
     */
    private ComposerRepository $composerRepository;

    /**
     * Paginator.
     */
    private PaginatorInterface $paginator;

    /**
     * Constructor.
     *
     * @param PeriodRepository     $periodRepository Period repository
     * @param PaginatorInterface $paginator      Paginator
     */
    public function __construct(PeriodRepository $periodRepository, ComposerRepository $composerRepository, PaginatorInterface $paginator)
    {
        $this->periodRepository = $periodRepository;
        $this->composerRepository = $composerRepository;
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
            $this->periodRepository->queryAll(),
            $page,
            PeriodRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    public function getPaginatedListByPeriod(int $page, Period $period): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->composerRepository->queryByPeriod($period),
            $page,
            ComposerRepository::PAGINATOR_ITEMS_PER_PAGE_PERIOD
        );
    }

    /**
     * Save entity.
     *
     * @param Period $period Period entity
     */
    public function save(Period $period): void
    {
        $this->periodRepository->save($period);
    }

    public function delete(Period $period): void
    {
        $this->periodRepository->delete($period);
    }

    public function canBeDeleted(Period $period): bool
    {
        try {
            $result = $this->composerRepository->countByPeriod($period);

            return !($result > 0);
        } catch (NoResultException|NonUniqueResultException $exception) {
            return false;
        }
    }
}
