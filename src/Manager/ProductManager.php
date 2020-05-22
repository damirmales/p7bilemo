<?php


namespace App\Manager;

use Symfony\Component\HttpFoundation\JsonResponse;

class ProductManager
{
    /**
     * @param $product
     * @param $customer
     * @return JsonResponse
     */
    public function showProduct($product, $customer)
    {
        $this->product = $product;
        if ($this->product[0]->getCustomers()->contains($customer)) {
            return $this->product;
        }
        return new JsonResponse(['message' => 'this is not your Product'], 401);
    }
}