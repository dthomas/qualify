<?php

namespace App\Entity;

use App\Doctrine\AccountAwareInterface;
use App\Repository\LeadRepository;
use App\Traits\HasTimeStamps;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LeadRepository::class)
 * @ORM\Table(name="leads", indexes={
 *      @ORM\Index(name="idx_leads_product_id", columns={"product_id"}),
 *      @ORM\Index(name="idx_leads_account_id", columns={"account_id"}),
 *      @ORM\Index(name="idx_leads_lead_source_id", columns={"lead_source_id"}),
 *      @ORM\Index(name="idx_leads_lead_stage_id", columns={"lead_stage_id"}),
 * })
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

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="leads")
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity=LeadSource::class, inversedBy="leads")
     */
    private $leadSource;

    /**
     * @ORM\OneToMany(targetEntity=LeadInteraction::class, mappedBy="parentLead")
     */
    private $leadInteractions;

    /**
     * @ORM\OneToMany(targetEntity=Opportunity::class, mappedBy="parentLead")
     */
    private $opportunities;

    /**
     * @ORM\ManyToOne(targetEntity=LeadStage::class, inversedBy="leads")
     */
    private $leadStage;

    public function __construct()
    {
        $this->contact = new Contact();
        $this->address = new Address();
        $this->leadInteractions = new ArrayCollection();
        $this->opportunities = new ArrayCollection();
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

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getLeadSource(): ?LeadSource
    {
        return $this->leadSource;
    }

    public function setLeadSource(?LeadSource $leadSource): self
    {
        $this->leadSource = $leadSource;

        return $this;
    }

    /**
     * @return Collection|LeadInteraction[]
     */
    public function getLeadInteractions(): Collection
    {
        return $this->leadInteractions;
    }

    public function addLeadInteraction(LeadInteraction $leadInteraction): self
    {
        if (!$this->leadInteractions->contains($leadInteraction)) {
            $this->leadInteractions[] = $leadInteraction;
            $leadInteraction->setParentLead($this);
        }

        return $this;
    }

    public function removeLeadInteraction(LeadInteraction $leadInteraction): self
    {
        if ($this->leadInteractions->removeElement($leadInteraction)) {
            // set the owning side to null (unless already changed)
            if ($leadInteraction->getParentLead() === $this) {
                $leadInteraction->setParentLead(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Opportunity[]
     */
    public function getOpportunities(): Collection
    {
        return $this->opportunities;
    }

    public function addOpportunity(Opportunity $opportunity): self
    {
        if (!$this->opportunities->contains($opportunity)) {
            $this->opportunities[] = $opportunity;
            $opportunity->setParentLead($this);
        }

        return $this;
    }

    public function removeOpportunity(Opportunity $opportunity): self
    {
        if ($this->opportunities->removeElement($opportunity)) {
            // set the owning side to null (unless already changed)
            if ($opportunity->getParentLead() === $this) {
                $opportunity->setParentLead(null);
            }
        }

        return $this;
    }

    public function getLeadStage(): ?LeadStage
    {
        return $this->leadStage;
    }

    public function setLeadStage(?LeadStage $leadStage): self
    {
        $this->leadStage = $leadStage;

        return $this;
    }
}
