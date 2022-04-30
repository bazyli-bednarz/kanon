<?php

namespace App\Repository;

use App\Entity\Canon;
use App\Entity\Composer;
use App\Entity\Piece;
use App\Entity\Scale;
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
    public const PAGINATOR_ITEMS_PER_PAGE_COMPOSER = 2;

    /**
     * Items per page on Scale subpage.
     *
     * @constant int
     */
    public const PAGINATOR_ITEMS_PER_PAGE_SCALE = 2;

    /**
     * Items per page on Canon subpage.
     *
     * @constant int
     */
    public const PAGINATOR_ITEMS_PER_PAGE_CANON = 2;

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
    public function queryAll(): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder()
            ->select(
                'partial piece.{id, name, description, year, link, createdAt, updatedAt, author, editedBy, slug}',
                'partial composer.{id, name, lastName, slug}',
                'partial scale.{id, name, slug}'
            )
            ->join('piece.composer', 'composer')
            ->join('piece.scale', 'scale')
            ->orderBy('piece.updatedAt', 'DESC');
    }

    public function queryByComposer(Composer $composer): QueryBuilder
    {
        $queryBuilder = $this->queryAll();
        $queryBuilder->andWhere('piece.composer = :composer')
            ->setParameter('composer', $composer);
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

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Piece $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Piece $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Piece[] Returns an array of Piece objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Piece
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
