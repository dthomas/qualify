<?php

namespace App\Entity;

use App\Doctrine\AccountAwareInterface;
use App\Repository\OpportunityItemRepository;
use App\Traits\HasTimeStamps;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OpportunityItemRepository::class)
 * @ORM\Table(name="opportunity_items", indexes={
 *      @ORM\Index(name="idx_opportunity_items_account_id", columns={"account_id"}),
 *      @ORM\Index(name="idx_opportunity_items_product_id", columns={"product_id"}),
 *      @ORM\Index(name="idx_opportunity_items_opportunity_id", columns={"opportunity_id"}),
 *      @ORM\Index(name="idx_opportunity_items_created_by_id", columns={"created_by_id"}),
 *      @ORM\Index(name="idx_opportunity_items_updated_by_id", columns={"updated_by_id"}),
 * })
 */
class OpportunityItem implements AccountAwareInterface
{
    use HasTimeStamps;
    
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="bigint")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Account::class, inversedBy="opportunityItems")
     * @ORM\JoinColumn(nullable=false)
     */
    private $account;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="opportunityItems")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity=Opportunity::class, inversedBy="opportunityItems")
     * @ORM\JoinColumn(nullable=false)
     */
    private $opportunity;

    /**
     * @ORM\Column(type="string", length=180)
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $remarks;

    /**
     * @ORM\Column(type="json_document", nullable=true)
     */
    private $history = [];

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="opportunityItems")
     * @ORM\JoinColumn(nullable=false)
     */
    private $createdBy;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $updatedBy;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $callbackAt;

    /**
     * @ORM\Column(type="string", length=48)
     */
    private $status;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $appointmentAt;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getOpportunity(): ?Opportunity
    {
        return $this->opportunity;
    }

    public function setOpportunity(?Opportunity $opportunity): self
    {
        $this->opportunity = $opportunity;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getRemarks(): ?string
    {
        return $this->remarks;
    }

    public function setRemarks(?string $remarks): self
    {
        $this->remarks = $remarks;

        return $this;
    }

    public function getHistory(): ?array
    {
        return $this->history;
    }

    public function setHistory(?array $history): self
    {
        $this->history = $history;

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

    public function getCallbackAt(): ?\DateTimeInterface
    {
        return $this->callbackAt;
    }

    public function setCallbackAt(?\DateTimeInterface $callbackAt): self
    {
        $this->callbackAt = $callbackAt;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getAppointmentAt(): ?\DateTimeInterface
    {
        return $this->appointmentAt;
    }

    public function setAppointmentAt(?\DateTimeInterface $appointmentAt): self
    {
        $this->appointmentAt = $appointmentAt;

        return $this;
    }
}
