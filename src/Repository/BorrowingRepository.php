<?php
/**
 * Borrowing repository.
 */

namespace App\Repository;

use App\Entity\Borrowing;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method Borrowing|null find($id, $lockMode = null, $lockVersion = null)
 * @method Borrowing|null findOneBy(array $criteria, array $orderBy = null)
 * @method Borrowing[]    findAll()
 * @method Borrowing[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BorrowingRepository extends ServiceEntityRepository
{
    /**
     *Items per page.
     *
     * Use constants to define configuration options that rarely change instead
     * of specifying them in app/config/config.yml.
     * See https://symfony.com/doc/current/best_practices.html#configuration
     *
     * @constant int
     */
    const PAGINATOR_ITEMS_PER_PAGE = 3;

    /**
     * BorrowingRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Borrowing::class);
    }

    /**
     * Query all records.
     *
     * @return QueryBuilder Query builder
     */
    public function queryAll(): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder()
            ->select('borrowing', 'user', 'book')
            ->leftjoin('borrowing.user', 'user')
            ->leftjoin('borrowing.book', 'book')
            ->orderBy('borrowing.borrowDate', 'DESC');
    }

    /**
     * Save record.
     *
     * @param Borrowing $borrowing Borrowing entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Borrowing $borrowing): void
    {
        $this->_em->persist($borrowing);
        $this->_em->flush();
    }

    /**
     * Query borrowing by user.
     * @param UserInterface $user
     *
     * @return QueryBuilder
     */
    public function queryByUser(UserInterface $user): QueryBuilder
    {
        $queryBuilder = $this->queryAll();

        $queryBuilder->andWhere('borrowing.borrowDate IS NOT NULL');
        $queryBuilder->andWhere('borrowing.user = :user')
            ->setParameter('user', $user);
        $queryBuilder->orderBy('borrowing.borrowDate', 'DESC');

        return $queryBuilder;
    }

    /**
     * Query all borrowings for admin.
     *
     * @return QueryBuilder Query builder
     */
    public function queryForAdmin(): QueryBuilder
    {
        $queryBuilder = $this->queryAll();

        $queryBuilder->andWhere('borrowing.returnDate IS NULL');
        $queryBuilder->orderBy('borrowing.borrowDate', 'DESC');

        return $queryBuilder;
    }

    /**
     * Delete record.
     *
     * @param Borrowing $borrowing Borrowing entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(Borrowing $borrowing): void
    {
        $this->_em->remove($borrowing);
        $this->_em->flush();
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
        return $queryBuilder ?? $this->createQueryBuilder('borrowing');
    }

    // /**
    //  * @return Borrowing[] Returns an array of Borrowing objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Borrowing
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
