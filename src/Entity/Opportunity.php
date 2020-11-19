<?php

namespace App\Entity;

use App\Doctrine\AccountAwareInterface;
use App\Repository\OpportunityRepository;
use App\Traits\HasTimeStamps;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OpportunityRepository::class)
 * @ORM\Table(name="opportunities", indexes={
 *      @ORM\Index(name="idx_opportunities_account_id", columns={"account_id"}),
 *      @ORM\Index(name="idx_opportunities_parent_lead_id", columns={"parent_lead_id"}),
 *      @ORM\Index(name="idx_opportunities_created_by_id", columns={"created_by_id"}),
 *      @ORM\Index(name="idx_opportunities_updated_by_id", columns={"updated_by_id"}),
 * })
 */
class Opportunity implements AccountAwareInterface
{
    use HasTimeStamps;
    
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="bigint")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180)
     */
    private $name;

    /**
     * @ORM\Embedded(class=Contact::class)
     */
    private Contact $contact;

    /**
     * @ORM\Embedded(class=Address::class)
     */
    private Address $address;

    /**
     * @ORM\ManyToOne(targetEntity=Account::class, inversedBy="opportunities")
     * @ORM\JoinColumn(nullable=false)
     */
    private $account;

    /**
     * @ORM\ManyToOne(targetEntity=Lead::class, inversedBy="opportunities")
     * @ORM\JoinColumn(nullable=false)
     */
    private $parentLead;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="opportunities")
     * @ORM\JoinColumn(nullable=false)
     */
    private $createdBy;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private $updatedBy;

    /**
     * @ORM\OneToMany(targetEntity=OpportunityItem::class, mappedBy="opportunity")
     */
    private $opportunityItems;

    public function __construct()
    {
        $this->opportunityItems = new ArrayCollection();
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

    public function setContact(Contact $contact): self
    {
        $this->contact = $contact;

        return $this;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(?Address $address): self
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

    public function getParentLead(): ?Lead
    {
        return $this->parentLead;
    }

    public function setParentLead(?Lead $parentLead): self
    {
        $this->parentLead = $parentLead;

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

    /**
     * @return Collection|OpportunityItem[]
     */
    public function getOpportunityItems(): Collection
    {
        return $this->opportunityItems;
    }

    public function addOpportunityItem(OpportunityItem $opportunityItem): self
    {
        if (!$this->opportunityItems->contains($opportunityItem)) {
            $this->opportunityItems[] = $opportunityItem;
            $opportunityItem->setOpportunity($this);
        }

        return $this;
    }

    public function removeOpportunityItem(OpportunityItem $opportunityItem): self
    {
        if ($this->opportunityItems->removeElement($opportunityItem)) {
            // set the owning side to null (unless already changed)
            if ($opportunityItem->getOpportunity() === $this) {
                $opportunityItem->setOpportunity(null);
            }
        }

        return $this;
    }
}
