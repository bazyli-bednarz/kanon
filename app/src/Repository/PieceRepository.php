<?php

namespace App\Repository;

use App\Entity\Canon;
use App\Entity\Composer;
use App\Entity\Piece;
use App\Entity\Scale;
use App\Entity\Tag;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Piece|null find($id, $lockMode = null, $lockVersion = null)
 * @method Piece|null findOneBy(array $criteria, array $orderBy = null)
 * @method Piece[]    findAll()
 * @method Piece[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PieceRepository extends ServiceEntityRepository
{
    /**
     * Items per page.
     *
     * Use constants to define configuration options that rarely change instead
     * of specifying them in app/config/config.yml.
     * See https://symfony.com/doc/current/best_practices.html#configuration
     *
     * @constant int
     */
    public const PAGINATOR_ITEMS_PER_PAGE = 10;

    /**
     * Items per page on Composer subpage.
     *
     * @constant int
     */
    public const PAGINATOR_ITEMS_PER_PAGE_COMPOSER = 10;

    /**
     * Items per page on User subpage.
     *
     * @constant int
     */
    public const PAGINATOR_ITEMS_PER_PAGE_USER = 10;

    /**
     * Items per page on Scale subpage.
     *
     * @constant int
     */
    public const PAGINATOR_ITEMS_PER_PAGE_SCALE = 10;

    /**
     * Items per page on Canon subpage.
     *
     * @constant int
     */
    public const PAGINATOR_ITEMS_PER_PAGE_CANON = 10;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Piece::class);
    }

    /**
     * Save entity.
     *
     * @param Piece $piece Piece entity
     */
    public function save(Piece $piece): void
    {
        $this->_em->persist($piece);
        $this->_em->flush();
    }

    /**
     * Delete entity.
     *
     * @param Piece $piece Piece entity
     */
    public function delete(Piece $piece): void
    {
        $this->_em->remove($piece);
        $this->_em->flush();
    }

    /**
     * Query all records.
     *
     * @return QueryBuilder Query builder
     */
    public function queryAll(array $filters = []): QueryBuilder
    {
        $queryBuilder = $this->getOrCreateQueryBuilder()
            ->select(
                'partial piece.{id, name, description, year, link, createdAt, updatedAt, author, editedBy, slug}',
                'partial composer.{id, name, lastName, slug}',
                'partial period.{id, name, slug}',
                'partial scale.{id, name, slug}',
                'partial tags.{id, name, slug}'

            )
            ->join('piece.composer', 'composer')
            ->join('composer.period', 'period')
            ->join('piece.scale', 'scale')
            ->leftJoin('piece.tags', 'tags')
            ->orderBy('piece.updatedAt', 'DESC');

        return $this->applyFilters($queryBuilder, $filters);
    }

    public function queryByComposer(Composer $composer): QueryBuilder
    {
        $queryBuilder = $this->queryAll();
        $queryBuilder->andWhere('piece.composer = :composer')
            ->setParameter('composer', $composer);
        return $queryBuilder;
    }

    public function queryByUser(User $user): QueryBuilder
    {
        $queryBuilder = $this->queryAll();
        $queryBuilder->andWhere('piece.author = :author')
            ->setParameter('author', $user);
        return $queryBuilder;
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function countByComposer(Composer $composer): int
    {
        $queryBuilder = $this->getOrCreateQueryBuilder();
        return $queryBuilder
            ->select($queryBuilder->expr()->countDistinct('piece.id'))
            ->where('piece.composer = :composer')
            ->setParameter(':composer', $composer)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function queryByScale(Scale $scale): QueryBuilder
    {
        $queryBuilder = $this->queryAll();
        $queryBuilder->andWhere('piece.scale = :scale')
            ->setParameter('scale', $scale);
        return $queryBuilder;
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function countByScale(Scale $scale): QueryBuilder
    {
        $queryBuilder = $this->queryByScale($scale);
        return $queryBuilder->getQuery()
            ->getSingleScalarResult();
    }

    public function queryByCanon(Canon $canon): QueryBuilder
    {
        return $this->queryAll()
            ->where(':canon MEMBER OF piece.canons')
            ->setParameter('canon', $canon);
    }

    /**
     * @throws NonUniqueResultException
     */
    public function getRandomPieceByCanon(Canon $canon): ?Piece
    {
        return $this->queryByCanon($canon)
            ->orderBy('RAND()')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @throws NonUniqueResultException
     */
    public function getRandomPiece(): ?Piece
    {
        return $this->queryAll()
            ->orderBy('RAND()')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function countByCanon(Canon $canon): int
    {
        $queryBuilder = $this->getOrCreateQueryBuilder();
        return $queryBuilder
            ->select($queryBuilder->expr()->countDistinct('piece.id'))
            ->where(':canon MEMBER OF piece.canons')
            ->setParameter(':canon', $canon)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Get or create new query builder.
     *
     * @param QueryBuilder|null $queryBuilder Query builder
     *
     * @return QueryBuilder Query builder
     */
    private function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?? $this->createQueryBuilder('piece');
    }

    public function add(Piece $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }


    public function remove(Piece $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    private function applyFilters(QueryBuilder $queryBuilder, array $filters = []): QueryBuilder
    {
        if (isset($filters['tag']) && $filters['tag'] instanceof Tag) {
            $queryBuilder->andWhere('tags IN (:tag)')
                ->setParameter('tag', $filters['tag']);
        }

        return $queryBuilder;
    }
}
