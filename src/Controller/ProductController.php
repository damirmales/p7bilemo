<?php

namespace App\Controller;

use App\Entity\Product;
use App\Manager\Paginate;
use App\Manager\ProductManager;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\View;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    private $product;
    private $paginator;

    /**
     * ProductController constructor.
     * @param ProductRepository $productRepository
     * @param EntityManagerInterface $entityManager
     * @param PaginatorInterface $paginator
     */
    public function __construct(ProductRepository $productRepository,
                                EntityManagerInterface $entityManager,
                                PaginatorInterface $paginator)
    {
        $this->productRepo = $productRepository;
        $this->manager = $entityManager;
        $this->paginator = $paginator;
    }

    /**
     * @Get("/products", name="list_products")
     * @View
     * @param ProductRepository $productRepository
     * @param TagAwareCacheInterface $cache
     * @return mixed
     * @throws \Psr\Cache\InvalidArgumentException
     * @Security("is_granted('ROLE_USER')")     *
     */
    public function products(Request $request, TagAwareCacheInterface $cache)
    {
        $limitPerPage = 10; //number of product per page paginate

        $data = $cache->get('products' . $this->getUser()->getId(), function (ItemInterface $item) {
            $item->expiresAfter(1800);

            return $this->productRepo->getProducts($this->productRepo, $this->getUser()->getId());

        });

        $pagine = new Paginate($this->paginator, $data, $request);

        return $pagine->pagination($limitPerPage);
    }

    /**
     * @Get("/products/{id}", name="one_product")
     * @View
     * @Security("is_granted('ROLE_USER') ")
     * @param Product $product
     * @return Product
     */
    public function getOneProduct(Product $product, TagAwareCacheInterface $cache)
    {
        $this->product = $product;

        return $cache->get('products' . $product->getId(), function (ItemInterface $item) {
            $item->expiresAfter(1800);
            $productManager = new ProductManager();
            $productManager->showProduct($this->product, $this->getUser());
        });
    }
}
