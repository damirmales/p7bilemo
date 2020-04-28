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
    /**
     * CustomerProductRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CustomerProduct::class);
    }

}
