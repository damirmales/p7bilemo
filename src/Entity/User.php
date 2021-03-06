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
 *  @Hateoas\Relation("list", href = @Hateoas\Route("list_users",
 *       absolute = true))
 *
 *  @Hateoas\Relation("update", href = @Hateoas\Route("update_user",
 *      parameters = {"id" = "expr(object.getId())"}, absolute = true))
 *
 * @Hateoas\Relation("create", href = @Hateoas\Route("create_user",
 *       absolute = true))
 *
 * @Hateoas\Relation("delete", href = @Hateoas\Route("delete_user",
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
     * @Assert\NotBlank(message="Must not be empty")
     * @Assert\Regex(pattern="/^[^0-9][a-zA-Zàáâãäåçèéêëìíîïðòóôõöùúûüýÿ^'\x22][^'\x22&)(]+$/",
     *      message="Must contains letters only")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Must not be empty")
     * @Assert\Regex(pattern="/^[^0-9][a-zA-Zàáâãäåçèéêëìíîïðòóôõöùúûüýÿ^'\x22][^'\x22&)(]+$/",
     *      message="Must contains letters only")
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Must not be empty")
     * @Assert\Email( message = "This email '{{ value }}' is not valid.")
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
