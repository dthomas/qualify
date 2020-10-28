<?php

namespace App\Entity;

use App\Doctrine\AccountAwareInterface;
use App\Repository\ProductRepository;
use App\Traits\HasTimeStamps;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 * @ORM\Table(name="products")
 */
class Product implements AccountAwareInterface
{
    use HasTimeStamps;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="bigint")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=180)
     */
    private $name;

    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default": true})
     */
    private $isAvailable;

    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default": false})
     */
    private $isDiscontinued;

    /**
     * @ORM\ManyToOne(targetEntity=Account::class, inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $account;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getIsAvailable(): ?bool
    {
        return $this->isAvailable;
    }

    public function setIsAvailable(?bool $isAvailable): self
    {
        $this->isAvailable = $isAvailable;

        return $this;
    }

    public function getIsDiscontinued(): ?bool
    {
        return $this->isDiscontinued;
    }

    public function setIsDiscontinued(?bool $isDiscontinued): self
    {
        $this->isDiscontinued = $isDiscontinued;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account): self
    {
        $this->account = $account;

        return $this;
    }
}