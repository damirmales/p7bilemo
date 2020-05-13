<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\View;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
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
    private $requestedProduct;
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
     * @Rest\Link()
     */
    public function products(Request $request, PaginatorInterface $paginator, TagAwareCacheInterface $cache)
    {
        $data = $cache->get('products' . $this->getUser()->getId(), function (ItemInterface $item) {
           $item->expiresAfter(1800);
            $loggedUser = $this->getUser();
            $repository = $this->manager->getRepository(Product::class);
            $query = $repository->createQueryBuilder('u')
                ->innerJoin('u.customers', 'c')
                ->where('c.id = :loggedUser')
                ->setParameter('loggedUser', $this->getUser()->getId())
                ->getQuery()->getResult();

            //return $query;

        });

        $pagineData = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            1/*limit per page*/
        );

        return $pagineData;
    }

    /**
     * @Get("/products/{id}", name="one_product")
     * @View
     * @param Product $product
     * @param TagAwareCacheInterface $cache
     * @return mixed
     * @throws \Psr\Cache\InvalidArgumentException
     * @Security("is_granted('ROLE_USER') ")
     */
    public function getOneProduct(Product $product, TagAwareCacheInterface $cache)
    {
        $this->setRequestedProduct($product);

        $loggedUser = $this->getUser();
        $repository = $this->manager->getRepository(Product::class);
        $query = $repository->createQueryBuilder('u')
            ->innerJoin('u.customers', 'c')
            ->where('c.id = :loggedUser')
            ->setParameter('loggedUser', $this->getUser()->getId())
            ->getQuery()->getResult();

        $count = count($query);
        $i = 0;
        while ($i < $count) {
            if ($query[$i]->getId() == $product->getId()) {
                return $query[$i];
            }
            $i++;
        }

        return new Response("L'article ne vous appartient pas", 403);
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

    /**
     * @Rest\Post("/products/create", name="create_product")
     * @Rest\View()
     * @param Product $product
     * @ParamConverter("product", converter="fos_rest.request_body")
     * @return mixed
     * @Security("is_granted('ROLE_USER') ")
     */
    public function postProduct(Product $product, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $errors = $validator->validate($product);
        if (count($errors) > 0) {
            $errorsString = (string)$errors;
            return new Response($errorsString, 403);
        }

        if ( $product->addCustomers($this->getUser())) {
            $entityManager->persist($product);
            $entityManager->flush();

            return $product;
        } else {
            return new Response("Erreur lors de l'ajout du client au produit", 403);
        }
    }
}
