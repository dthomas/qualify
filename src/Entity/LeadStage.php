<?php

namespace App\Entity;

use App\Doctrine\AccountAwareInterface;
use App\Repository\LeadStageRepository;
use App\Traits\HasTimeStamps;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LeadStageRepository::class)
 * @ORM\Table(name="lead_stages", indexes={
 *      @ORM\Index(name="idx_lead_stages_is_active", columns={"is_active"}),
 *      @ORM\Index(name="idx_lead_stages_account_id", columns={"account_id"}),
 * })
 */
class LeadStage implements AccountAwareInterface
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
     * @ORM\ManyToOne(targetEntity=Account::class, inversedBy="leadStages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $account;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\OneToMany(targetEntity=LeadInteraction::class, mappedBy="leadStage")
     */
    private $leadInteractions;

    /**
     * @ORM\Column(type="string", length=16, options={"default": "follow-up"})
     */
    private $stageType;

    public function __construct()
    {
        $this->leadInteractions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

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
            $leadInteraction->setLeadStage($this);
        }

        return $this;
    }

    public function removeLeadInteraction(LeadInteraction $leadInteraction): self
    {
        if ($this->leadInteractions->removeElement($leadInteraction)) {
            // set the owning side to null (unless already changed)
            if ($leadInteraction->getLeadStage() === $this) {
                $leadInteraction->setLeadStage(null);
            }
        }

        return $this;
    }

    public function getStageType(): ?string
    {
        return $this->stageType;
    }

    public function setStageType(string $stageType): self
    {
        $this->stageType = $stageType;

        return $this;
    }
}
