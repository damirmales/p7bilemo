<?php

namespace App\Repository;

use App\Entity\CustomerProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CustomerProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method CustomerProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method CustomerProduct[]    findAll()
 * @method CustomerProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CustomerProduct::class);
    }

    // /**
    //  * @return CustomerProduct[] Returns an array of CustomerProduct objects
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
    public function findOneBySomeField($value): ?CustomerProduct
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
