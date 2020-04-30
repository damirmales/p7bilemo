<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\View;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Get("/products", name="list_products")
     * @View
     * @param ProductRepository $productRepository
     * @return Product[]
     */
    public function products(ProductRepository $productRepository)
    {
        $allProduct = $productRepository->findAll();
        return $allProduct;
    }

    /**
     * @Get("/products/{id}", name="one_product")
     * @View
     * @param Product $product
     * @return Product
     */
    public function getOneProduct(Product $product)
    {
        return $product;
    }
}
