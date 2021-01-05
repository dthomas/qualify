<?php

namespace App\Entity;

use App\Repository\AccountRepository;
use App\Traits\HasTimeStamps;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AccountRepository::class)
 * @ORM\Table(name="accounts")
 */
class Account
{
    use HasTimeStamps;
    
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="bigint")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=120)
     */
    private $name;

    /**
     * @ORM\OneToOne(targetEntity=User::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $owner;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="account", cascade={"persist"})
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="account")
     */
    private $products;

    /**
     * @ORM\OneToMany(targetEntity=Lead::class, mappedBy="account")
     */
    private $leads;

    /**
     * @ORM\OneToMany(targetEntity=LeadStage::class, mappedBy="account")
     */
    private $leadStages;

    /**
     * @ORM\OneToMany(targetEntity=LeadSource::class, mappedBy="account")
     */
    private $leadSources;

    /**
     * @ORM\OneToMany(targetEntity=LeadInteraction::class, mappedBy="account")
     */
    private $leadInteractions;

    /**
     * @ORM\OneToMany(targetEntity=Opportunity::class, mappedBy="account")
     */
    private $opportunities;

    /**
     * @ORM\OneToMany(targetEntity=OpportunityItem::class, mappedBy="account")
     */
    private $opportunityItems;

    /**
     * @ORM\Column(type="json_document", nullable=true)
     */
    private $settings;

    /**
     * @ORM\OneToMany(targetEntity=FacebookPage::class, mappedBy="account")
     */
    private $facebookPages;

    /**
     * @ORM\OneToMany(targetEntity=FacebookLeadUpdate::class, mappedBy="account")
     */
    private $facebookLeadUpdates;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->products = new ArrayCollection();
        $this->leads = new ArrayCollection();
        $this->leadStages = new ArrayCollection();
        $this->leadSources = new ArrayCollection();
        $this->leadInteractions = new ArrayCollection();
        $this->opportunities = new ArrayCollection();
        $this->opportunityItems = new ArrayCollection();
        $this->facebookPages = new ArrayCollection();
        $this->facebookLeadUpdates = new ArrayCollection();
    }

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

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setAccount($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getAccount() === $this) {
                $user->setAccount(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setAccount($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            // set the owning side to null (unless already changed)
            if ($product->getAccount() === $this) {
                $product->setAccount(null);
            }
        }

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
            $lead->setAccount($this);
        }

        return $this;
    }

    public function removeLead(Lead $lead): self
    {
        if ($this->leads->contains($lead)) {
            $this->leads->removeElement($lead);
            // set the owning side to null (unless already changed)
            if ($lead->getAccount() === $this) {
                $lead->setAccount(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|LeadStage[]
     */
    public function getLeadStages(): Collection
    {
        return $this->leadStages;
    }

    public function addLeadStatus(LeadStage $leadStage): self
    {
        if (!$this->leadStages->contains($leadStage)) {
            $this->leadStages[] = $leadStage;
            $leadStage->setAccount($this);
        }

        return $this;
    }

    public function removeLeadStatus(LeadStage $leadStage): self
    {
        if ($this->leadStagees->contains($leadStage)) {
            $this->leadStagees->removeElement($leadStage);
            // set the owning side to null (unless already changed)
            if ($leadStage->getAccount() === $this) {
                $leadStage->setAccount(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|LeadSource[]
     */
    public function getLeadSources(): Collection
    {
        return $this->leadSources;
    }

    public function addLeadSource(LeadSource $leadSource): self
    {
        if (!$this->leadSources->contains($leadSource)) {
            $this->leadSources[] = $leadSource;
            $leadSource->setAccount($this);
        }

        return $this;
    }

    public function removeLeadSource(LeadSource $leadSource): self
    {
        if ($this->leadSources->contains($leadSource)) {
            $this->leadSources->removeElement($leadSource);
            // set the owning side to null (unless already changed)
            if ($leadSource->getAccount() === $this) {
                $leadSource->setAccount(null);
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
            $leadInteraction->setAccount($this);
        }

        return $this;
    }

    public function removeLeadInteraction(LeadInteraction $leadInteraction): self
    {
        if ($this->leadInteractions->removeElement($leadInteraction)) {
            // set the owning side to null (unless already changed)
            if ($leadInteraction->getAccount() === $this) {
                $leadInteraction->setAccount(null);
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
            $opportunity->setAccount($this);
        }

        return $this;
    }

    public function removeOpportunity(Opportunity $opportunity): self
    {
        if ($this->opportunities->removeElement($opportunity)) {
            // set the owning side to null (unless already changed)
            if ($opportunity->getAccount() === $this) {
                $opportunity->setAccount(null);
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
            $opportunityItem->setAccount($this);
        }

        return $this;
    }

    public function removeOpportunityItem(OpportunityItem $opportunityItem): self
    {
        if ($this->opportunityItems->removeElement($opportunityItem)) {
            // set the owning side to null (unless already changed)
            if ($opportunityItem->getAccount() === $this) {
                $opportunityItem->setAccount(null);
            }
        }

        return $this;
    }

    public function getSettings()
    {
        return $this->settings;
    }

    public function setSettings($settings): self
    {
        $this->settings = $settings;

        return $this;
    }

    /**
     * @return Collection|FacebookPage[]
     */
    public function getFacebookPages(): Collection
    {
        return $this->facebookPages;
    }

    public function addFacebookPage(FacebookPage $facebookPage): self
    {
        if (!$this->facebookPages->contains($facebookPage)) {
            $this->facebookPages[] = $facebookPage;
            $facebookPage->setAccount($this);
        }

        return $this;
    }

    public function removeFacebookPage(FacebookPage $facebookPage): self
    {
        if ($this->facebookPages->removeElement($facebookPage)) {
            // set the owning side to null (unless already changed)
            if ($facebookPage->getAccount() === $this) {
                $facebookPage->setAccount(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|FacebookLeadUpdate[]
     */
    public function getFacebookLeadUpdates(): Collection
    {
        return $this->facebookLeadUpdates;
    }

    public function addFacebookLeadUpdate(FacebookLeadUpdate $facebookLeadUpdate): self
    {
        if (!$this->facebookLeadUpdates->contains($facebookLeadUpdate)) {
            $this->facebookLeadUpdates[] = $facebookLeadUpdate;
            $facebookLeadUpdate->setAccount($this);
        }

        return $this;
    }

    public function removeFacebookLeadUpdate(FacebookLeadUpdate $facebookLeadUpdate): self
    {
        if ($this->facebookLeadUpdates->removeElement($facebookLeadUpdate)) {
            // set the owning side to null (unless already changed)
            if ($facebookLeadUpdate->getAccount() === $this) {
                $facebookLeadUpdate->setAccount(null);
            }
        }

        return $this;
    }
}
