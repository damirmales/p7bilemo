<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class ProductController extends AbstractController
{

    private $productRepo;
    private $requestedProduct;


    /**
     * ProductController constructor.
     * @param ProductRepository $productRepository
     */
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepo = $productRepository;

    }

    /**
     * @Get("/products", name="list_products")
     * @View
     * @param ProductRepository $productRepository
     * @param TagAwareCacheInterface $cache
     * @return mixed
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function products(ProductRepository $productRepository, TagAwareCacheInterface $cache)
    {
        return $cache->get('products', function (ItemInterface $item) {
            $item->expiresAfter(1800);
            $item->tag(['products']);
            $allProduct = $this->productRepo->findAll();
            return $allProduct;
        });

    }

    /**
     * @Get("/products/{id}", name="one_product")
     * @View
     * @param Product $product
     * @param TagAwareCacheInterface $cache
     * @return mixed
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function getOneProduct(Product $product, TagAwareCacheInterface $cache)
    {
        $this->setRequestedProduct($product);
        return $cache->get('product'.$product->getId(), function (ItemInterface $item) {
            $item->expiresAfter(1800);
            $item->tag(['product']);
            return $this->getRequestedProduct();
        });


    }

    /**
     * @Rest\Post("/products/create", name="create_product")
     * @Rest\View()
     * @param Product $product
     * @ParamConverter("product", converter="fos_rest.request_body")
     * @return Product
     */
    public function postProduct(Product $product, EntityManagerInterface $entityManager)
    {
        {
            $entityManager->persist($product);
            $entityManager->flush();

            return $product;
        }

    }

    /**
     * @return mixed
     */
    public function getRequestedProduct()
    {
        return $this->requestedProduct;
    }

    /**
     * @param mixed $requestedProduct
     */
    public function setRequestedProduct($requestedProduct)
    {
        $this->requestedProduct = $requestedProduct;
        return $this;
    }
}
