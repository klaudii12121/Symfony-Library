<?php

namespace App\Repository;

use App\Entity\BookTag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BookTag|null find($id, $lockMode = null, $lockVersion = null)
 * @method BookTag|null findOneBy(array $criteria, array $orderBy = null)
 * @method BookTag[]    findAll()
 * @method BookTag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookTagRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BookTag::class);
    }

    // /**
    //  * @return BookTag[] Returns an array of BookTag objects
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
    public function findOneBySomeField($value): ?BookTag
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
