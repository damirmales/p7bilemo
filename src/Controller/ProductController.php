<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\View;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

/**
 * Permet de gérer l'affichage des produits
 * Class ProductController
 * @package App\Controller
 */
class ProductController extends AbstractController
{
    private $productRepo;
    private $manager;

    /**
     * ProductController constructor.
     * @param ProductRepository $productRepository
     */
    public function __construct(ProductRepository $productRepository, EntityManagerInterface $entityManager)
    {
        $this->productRepo = $productRepository;
        $this->manager = $entityManager;
    }

    /**
     * @Get("/products", name="list_products")
     * @View
     * @param ProductRepository $productRepository
     * @param TagAwareCacheInterface $cache
     * @return mixed
     * @throws \Psr\Cache\InvalidArgumentException
     * @Security("is_granted('ROLE_USER')")
     *
     */
    public function products(Request $request, PaginatorInterface $paginator, TagAwareCacheInterface $cache)
    {
        $data = $cache->get('products' . $this->getUser()->getId(), function (ItemInterface $item) {
            $item->expiresAfter(1800);

            $repository = $this->manager->getRepository(Product::class);
            $repository->createQueryBuilder('u')
                ->innerJoin('u.customers', 'c')
                ->where('c.id = :loggedUser')
                ->setParameter('loggedUser', $this->getUser()->getId())
                ->getQuery()->getResult();
        });
        $pagineData = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            20/*limit per page*/
        );

        return $pagineData;
    }

    /**
     * @Get("/products/{id}", name="one_product")
     * @View
     * @Security("is_granted('ROLE_USER') ")
     * @Cache(expires="tomorrow")
     * @param Product $product
     * @return Product
     */
    public function getOneProduct(Product $product)
    {
            foreach ($product->getCustomers() as $item) {
                if ($item === $this->getUser()) {
                    return $product;
                }
            }
            return new JsonResponse(['message' => 'L\'article ne vous appartient pas', 'status' => 403]);
    }
}
