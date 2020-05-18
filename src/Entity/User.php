<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Hateoas\Configuration\Annotation as Hateoas;

/**
 * @ORM\Entity
 * @UniqueEntity("email")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @Hateoas\Relation("self", href = @Hateoas\Route("one_user",
 *      parameters = {"id" = "expr(object.getId())"}, absolute = true))
 *
 */
class User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Ce champ doit être renseigné")
     * @Assert\Regex(pattern="/^[^0-9][a-zA-Zàáâãäåçèéêëìíîïðòóôõöùúûüýÿ^'\x22][^'\x22&)(]+$/",
     *      message="Ce champ doit uniquement contenir des lettres de l'alphabet")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Ce champ doit être renseigné")
     * @Assert\Regex(pattern="/^[^0-9][a-zA-Zàáâãäåçèéêëìíîïðòóôõöùúûüýÿ^'\x22][^'\x22&)(]+$/",
     *      message="Ce champ doit uniquement contenir des lettres de l'alphabet")
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Ce champ doit être renseigné")
     * @Assert\Email( message = "Cet email '{{ value }}' n'est pas au format valide.")
     */
    private $email;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Customer", inversedBy="users",  cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $customer;


    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     * @return $this
     */
    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     * @return $this
     */
    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }


    /**
     * @return Customer
     */
    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    /**
     * @param Customer $customer
     * @return $this|Customer
     */
    public function setCustomer(Customer $customer)
    {
        $this->customer = $customer;

        return $this;
    }
}
