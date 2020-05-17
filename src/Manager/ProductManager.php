<?php


namespace App\Manager;


use Symfony\Component\HttpFoundation\JsonResponse;

class ProductManager
{

    public function showProduct($product, $customer)
    {
        $this->product = $product;

        if ($this->product->getCustomers()->contains($customer)) {
            return $this->product;
        }
        return new JsonResponse(['message' => 'L\'article ne vous appartient pas', 'status' => 403]);

    }

}