<?php

namespace App\Entity;

use App\Doctrine\AccountAwareInterface;
use App\Repository\LeadInteractionRepository;
use App\Traits\HasTimeStamps;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LeadInteractionRepository::class)
 * @ORM\Table(name="lead_interactions", indexes={
 *      @ORM\Index(name="idx_lead_interactions_account_id", columns={"account_id"}),
 *      @ORM\Index(name="idx_lead_interactions_parent_lead_id", columns={"parent_lead_id"}),
 *      @ORM\Index(name="idx_lead_interactions_lead_stage_id", columns={"lead_stage_id"}),
 * })
 */
class LeadInteraction implements AccountAwareInterface
{
    use HasTimeStamps;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="bigint")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $callbackAt;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $remarks;

    /**
     * @ORM\ManyToOne(targetEntity=Lead::class, inversedBy="leadInteractions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $parentLead;

    /**
     * @ORM\ManyToOne(targetEntity=LeadStage::class, inversedBy="leadInteractions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $leadStage;

    /**
     * @ORM\ManyToOne(targetEntity=Account::class, inversedBy="leadInteractions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $account;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="leadInteractions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getRemarks(): ?string
    {
        return $this->remarks;
    }

    public function setRemarks(?string $remarks): self
    {
        $this->remarks = $remarks;

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

    public function getLeadStage(): ?LeadStage
    {
        return $this->leadStage;
    }

    public function setLeadStage(?LeadStage $leadStage): self
    {
        $this->leadStage = $leadStage;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
