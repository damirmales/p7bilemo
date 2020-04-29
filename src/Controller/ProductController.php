<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/products", name="list_products")
     * @param ProductRepository $productRepository
     * @param SerializerInterface $serialize
     * @return JsonResponse
     */
    public function products(ProductRepository $productRepository, SerializerInterface $serialize )
    {
        // collect all product data
        $products = $productRepository->findAll();

        //serialize collected data
        $productSerialized = $serialize->serialize($products, "json");

        return new JsonResponse($productSerialized, 200, [], true);
    }

    /**
     * @Route("/products/{id}", name="one_product")
     * @param $id
     * @param ProductRepository $productRepository
     * @param SerializerInterface $serialize
     * @return JsonResponse
     */
    public function getOneProduct($id, ProductRepository $productRepository, SerializerInterface $serialize )
    {
        // collect a specific product
        $product = $productRepository->findOneById($id);

        //serialize collected data
        $productSerialized = $serialize->serialize($product, "json");

        return new JsonResponse($productSerialized, 200, [], true);
    }
}
