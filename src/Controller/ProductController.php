<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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

    /**
     * @Rest\Post("/products/create", name="create_product")
     * @Rest\View()
     * @param Product $product
     * @ParamConverter("product", converter="fos_rest.request_body")
     * @return Product
     */
    public function postProduct(Product $product, EntityManagerInterface $entityManager)
    {
        $entityManager->persist($product);
        $entityManager->flush();

        return $product;
    }

}
