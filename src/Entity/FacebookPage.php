<?php

namespace App\Entity;

use App\Doctrine\AccountAwareInterface;
use App\Repository\FacebookPageRepository;
use App\Traits\HasTimeStamps;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FacebookPageRepository::class)
 * @ORM\Table(name="facebook_pages", indexes={
 *      @ORM\Index(name="idx_facebook_pages_account_id", columns={"account_id"}),
 *      @ORM\Index(name="idx_facebook_pages_lead_source_id", columns={"lead_source_id"}),
 * }, uniqueConstraints = {
 *      @ORM\UniqueConstraint(name="idx_facebook_pages_fbid_uniq", columns={"fbid"}),
 * })
 */
class FacebookPage implements AccountAwareInterface
{
    use HasTimeStamps;
    
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="bigint")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="bigint", unique=true)
     */
    private $fbid;

    /**
     * @ORM\Column(type="boolean", options={"default": false})
     */
    private $subscribed;

    /**
     * @ORM\ManyToOne(targetEntity=Account::class, inversedBy="facebookPages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $account;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $accessToken;

    /**
     * @ORM\ManyToOne(targetEntity=LeadSource::class, cascade={"persist", "remove"})
     */
    private $leadSource;

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

    public function getFbid(): ?string
    {
        return $this->fbid;
    }

    public function setFbid(string $fbid): self
    {
        $this->fbid = $fbid;

        return $this;
    }

    public function getSubscribed(): ?bool
    {
        return $this->subscribed;
    }

    public function setSubscribed(bool $subscribed): self
    {
        $this->subscribed = $subscribed;

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

    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    public function setAccessToken(?string $accessToken): self
    {
        $this->accessToken = $accessToken;

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
}
