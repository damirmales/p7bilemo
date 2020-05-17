<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product|null findOneById(string $criteria)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    /**
     * ProductRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * Retrieve all products associated with a logged customer
     * @param $repository
     * @param $userId
     * @return mixed
     */
    public function getProducts($repository,$userId)
    {
        $query= $repository->createQueryBuilder('u')
             ->innerJoin('u.customers', 'c')
             ->where('c.id = :loggedUser')
             ->setParameter('loggedUser', $userId)
             ->getQuery()->getResult();

             return $query;
    }
}
