<?php

namespace App\Entity;

use App\Doctrine\AccountAwareInterface;
use App\Repository\LeadRepository;
use App\Traits\HasTimeStamps;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LeadRepository::class)
 * @ORM\Table(name="`leads`")
 */
class Lead implements AccountAwareInterface
{
    use HasTimeStamps;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="bigint")
     */
    private string $id;

    /**
     * @ORM\Column(type="string", length=120)
     */
    private string $name;

    /**
     * @ORM\Embedded(class=Contact::class)
     */
    private Contact $contact;

    /**
     * @ORM\Embedded(class=Address::class)
     */
    private Address $address;

    /**
     * @ORM\ManyToOne(targetEntity=Account::class, inversedBy="leads")
     * @ORM\JoinColumn(nullable=false)
     */
    private $account;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isQualified;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="leads")
     * @ORM\JoinColumn(nullable=false)
     */
    private $createdBy;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $updatedBy;

    public function __construct()
    {
        $this->contact = new Contact();
        $this->address = new Address();
    }

    public function getId(): ?int
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

    public function getContact(): ?Contact
    {
        return $this->contact;
    }

    public function setContact(Contact $contact)
    {
        $this->contact = $contact;

        return $this;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(Address $address)
    {
        $this->address = $address;

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

    public function getIsQualified(): ?bool
    {
        return $this->isQualified;
    }

    public function setIsQualified(?bool $isQualified): self
    {
        $this->isQualified = $isQualified;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getUpdatedBy(): ?User
    {
        return $this->updatedBy;
    }

    public function setUpdatedBy(?User $updatedBy): self
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }
}
