<?php

namespace App\Entity;

use App\Repository\UserRepository;
use App\Traits\HasTimeStamps;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="users")
 * @UniqueEntity("email", message="You already have an account.")
 */
class User implements UserInterface
{
    use HasTimeStamps;
    
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="bigint")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=120)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private $phone;

    /**
     * @ORM\ManyToOne(targetEntity=Account::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     */
    private $account;

    /**
     * @ORM\OneToMany(targetEntity=Lead::class, mappedBy="createdBy")
     */
    private $leads;

    /**
     * @ORM\OneToMany(targetEntity=LeadInteraction::class, mappedBy="user")
     */
    private $leadInteractions;

    /**
     * @ORM\OneToMany(targetEntity=Opportunity::class, mappedBy="createdBy")
     */
    private $opportunities;

    /**
     * @ORM\OneToMany(targetEntity=OpportunityItem::class, mappedBy="createdBy")
     */
    private $opportunityItems;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->leads = new ArrayCollection();
        $this->leadInteractions = new ArrayCollection();
        $this->opportunities = new ArrayCollection();
        $this->opportunityItems = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

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
            $lead->setCreatedBy($this);
        }

        return $this;
    }

    public function removeLead(Lead $lead): self
    {
        if ($this->leads->contains($lead)) {
            $this->leads->removeElement($lead);
            // set the owning side to null (unless already changed)
            if ($lead->getCreatedBy() === $this) {
                $lead->setCreatedBy(null);
            }
        }

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
            $leadInteraction->setUser($this);
        }

        return $this;
    }

    public function removeLeadInteraction(LeadInteraction $leadInteraction): self
    {
        if ($this->leadInteractions->removeElement($leadInteraction)) {
            // set the owning side to null (unless already changed)
            if ($leadInteraction->getUser() === $this) {
                $leadInteraction->setUser(null);
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
            $opportunity->setCreatedBy($this);
        }

        return $this;
    }

    public function removeOpportunity(Opportunity $opportunity): self
    {
        if ($this->opportunities->removeElement($opportunity)) {
            // set the owning side to null (unless already changed)
            if ($opportunity->getCreatedBy() === $this) {
                $opportunity->setCreatedBy(null);
            }
        }

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
            $opportunityItem->setCreatedBy($this);
        }

        return $this;
    }

    public function removeOpportunityItem(OpportunityItem $opportunityItem): self
    {
        if ($this->opportunityItems->removeElement($opportunityItem)) {
            // set the owning side to null (unless already changed)
            if ($opportunityItem->getCreatedBy() === $this) {
                $opportunityItem->setCreatedBy(null);
            }
        }

        return $this;
    }
}
