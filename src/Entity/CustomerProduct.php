<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CustomerProductRepository")
 */
class CustomerProduct
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Product", mappedBy="customerProduct")
     */
    private $product;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Customer", mappedBy="customerProduct")
     */
    private $customer;

    public function __construct()
    {
        $this->product = new ArrayCollection();
        $this->customer = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProduct(): Collection
    {
        return $this->product;
    }

    public function addProduct(Product $oneToMany): self
    {
        if (!$this->product->contains($oneToMany)) {
            $this->product[] = $oneToMany;
            $oneToMany->setCustomerProduct($this);
        }

        return $this;
    }

    public function removeProduct(Product $oneToMany): self
    {
        if ($this->product->contains($oneToMany)) {
            $this->product->removeElement($oneToMany);
            // set the owning side to null (unless already changed)
            if ($oneToMany->getCustomerProduct() === $this) {
                $oneToMany->setCustomerProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Customer[]
     */
    public function getCustomer(): Collection
    {
        return $this->customer;
    }

    public function addCustomer(Customer $customer): self
    {
        if (!$this->customer->contains($customer)) {
            $this->customer[] = $customer;
            $customer->setCustomerProduct($this);
        }

        return $this;
    }

    public function removeCustomer(Customer $customer): self
    {
        if ($this->customer->contains($customer)) {
            $this->customer->removeElement($customer);
            // set the owning side to null (unless already changed)
            if ($customer->getCustomerProduct() === $this) {
                $customer->setCustomerProduct(null);
            }
        }

        return $this;
    }
}
