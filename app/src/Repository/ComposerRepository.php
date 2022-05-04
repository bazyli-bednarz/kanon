<?php

namespace App\Repository;

use App\Entity\Composer;
use App\Entity\Period;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Composer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Composer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Composer[]    findAll()
 * @method Composer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComposerRepository extends ServiceEntityRepository
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
     * Items per page on Periods subpage.
     *
     * @constant int
     */
    public const PAGINATOR_ITEMS_PER_PAGE_PERIOD = 10;

    /**
     * Items per page on User subpage.
     *
     * @constant int
     */
    public const PAGINATOR_ITEMS_PER_PAGE_USER = 10;


    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Composer::class);
    }

    /**
     * Save entity.
     *
     * @param Composer $composer Composer entity
     */
    public function save(Composer $composer): void
    {
        $this->_em->persist($composer);
        $this->_em->flush();
    }

    /**
     * Delete entity.
     *
     * @param Composer $composer Composer entity
     */
    public function delete(Composer $composer): void
    {
        $this->_em->remove($composer);
        $this->_em->flush();
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Composer $entity, bool $flush = true): void
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
    public function remove(Composer $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
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
                'partial composer.{id, name, lastName, description, birthYear, deathYear, createdAt, author, updatedAt, slug}',
                'partial period.{id, name, slug}'
            )
            ->join('composer.period', 'period')
            ->orderBy('composer.updatedAt', 'DESC');
    }

    public function queryByUser(User $user): QueryBuilder
    {
        $queryBuilder = $this->queryAll();
        $queryBuilder->andWhere('composer.author = :author')
            ->setParameter('author', $user);
        return $queryBuilder;
    }

    public function queryByPeriod(Period $period): QueryBuilder
    {
        $queryBuilder = $this->queryAll();
        $queryBuilder->andWhere('composer.period = :period')
            ->setParameter('period', $period);
        return $queryBuilder;
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function countByPeriod(Period $period): QueryBuilder
    {
        $queryBuilder = $this->queryByPeriod($period);
        return $queryBuilder->getQuery()
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
        return $queryBuilder ?? $this->createQueryBuilder('composer');
    }


    // /**
    //  * @return Composer[] Returns an array of Composer objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Composer
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
