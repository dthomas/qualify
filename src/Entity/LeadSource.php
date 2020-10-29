<?php

namespace App\Entity;

use App\Doctrine\AccountAwareInterface;
use App\Repository\LeadSourceRepository;
use App\Traits\HasTimeStamps;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LeadSourceRepository::class)
 * @ORM\Table(name="lead_sources", indexes={
 *      @ORM\Index(name="idx_lead_sources_is_active", columns={"is_active"}),
 *      @ORM\Index(name="idx_lead_sources_account_id", columns={"account_id"}),
 * })
 */
class LeadSource implements AccountAwareInterface
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
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\ManyToOne(targetEntity=Account::class, inversedBy="leadSources")
     * @ORM\JoinColumn(nullable=false)
     */
    private $account;

    /**
     * @ORM\OneToMany(targetEntity=Lead::class, mappedBy="leadSource")
     */
    private $leads;

    public function __construct()
    {
        $this->leads = new ArrayCollection();
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

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

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

    /**
     * @return Collection|Lead[]
     */
    public function getLeads(): Collection
    {
        return $this->leads;
    }

    public function addLead(Lead $lead): self
    {
        if (!$this->leads->contains($lead)) {
            $this->leads[] = $lead;
            $lead->setLeadSource($this);
        }

        return $this;
    }

    public function removeLead(Lead $lead): self
    {
        if ($this->leads->removeElement($lead)) {
            // set the owning side to null (unless already changed)
            if ($lead->getLeadSource() === $this) {
                $lead->setLeadSource(null);
            }
        }

        return $this;
    }
}
