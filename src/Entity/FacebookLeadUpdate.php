<?php

namespace App\Entity;

use App\Repository\FacebookLeadUpdateRepository;
use App\Traits\HasTimeStamps;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Entity(repositoryClass=FacebookLeadUpdateRepository::class)
 * @ORM\Table(name="facebook_lead_updates", indexes={
 *      @ORM\Index(name="idx_facebook_lead_updates_facebook_page_id", columns={"facebook_page_id"}),
 *      @ORM\Index(name="idx_facebook_lead_updates_account_id", columns={"account_id"}),
 *      @ORM\Index(name="idx_facebook_lead_updates_created_lead_id", columns={"created_lead_id"}),
 * })
 */
class FacebookLeadUpdate
{
    use HasTimeStamps;

    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     */
    private $id;

    /**
     * @ORM\Column(type="bigint")
     */
    private $updateId;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updateTime;

    /**
     * @ORM\Column(type="bigint")
     */
    private $leadgenId;

    /**
     * @ORM\Column(type="bigint")
     */
    private $pageId;

    /**
     * @ORM\Column(type="bigint")
     */
    private $formId;

    /**
     * @ORM\Column(type="bigint")
     */
    private $adgroupId;

    /**
     * @ORM\Column(type="bigint")
     */
    private $adId;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdTime;

    /**
     * @ORM\Column(type="smallint", options={"default": 0})
     */
    private $processCount;

    /**
     * @ORM\ManyToOne(targetEntity=FacebookPage::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $facebookPage;

    /**
     * @ORM\ManyToOne(targetEntity=Account::class, inversedBy="facebookLeadUpdates")
     * @ORM\JoinColumn(nullable=false)
     */
    private $account;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $leadCreatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=Lead::class)
     */
    private $createdLead;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function setId(Uuid $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getUpdateId(): ?string
    {
        return $this->updateId;
    }

    public function setUpdateId(string $updateId): self
    {
        $this->updateId = $updateId;

        return $this;
    }

    public function getUpdateTime(): ?\DateTimeInterface
    {
        return $this->updateTime;
    }

    public function setUpdateTime(\DateTimeInterface $updateTime): self
    {
        $this->updateTime = $updateTime;

        return $this;
    }

    public function getLeadgenId(): ?string
    {
        return $this->leadgenId;
    }

    public function setLeadgenId(string $leadgenId): self
    {
        $this->leadgenId = $leadgenId;

        return $this;
    }

    public function getPageId(): ?string
    {
        return $this->pageId;
    }

    public function setPageId(string $pageId): self
    {
        $this->pageId = $pageId;

        return $this;
    }

    public function getFormId(): ?string
    {
        return $this->formId;
    }

    public function setFormId(string $formId): self
    {
        $this->formId = $formId;

        return $this;
    }

    public function getAdgroupId(): ?string
    {
        return $this->adgroupId;
    }

    public function setAdgroupId(string $adgroupId): self
    {
        $this->adgroupId = $adgroupId;

        return $this;
    }

    public function getAdId(): ?string
    {
        return $this->adId;
    }

    public function setAdId(string $adId): self
    {
        $this->adId = $adId;

        return $this;
    }

    public function getCreatedTime(): ?\DateTimeInterface
    {
        return $this->createdTime;
    }

    public function setCreatedTime(\DateTimeInterface $createdTime): self
    {
        $this->createdTime = $createdTime;

        return $this;
    }

    public function getProcessCount(): ?int
    {
        return $this->processCount;
    }

    public function setProcessCount(int $processCount): self
    {
        $this->processCount = $processCount;

        return $this;
    }

    public function getFacebookPage(): ?FacebookPage
    {
        return $this->facebookPage;
    }

    public function setFacebookPage(?FacebookPage $facebookPage): self
    {
        $this->facebookPage = $facebookPage;

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

    public function getLeadCreatedAt(): ?\DateTimeInterface
    {
        return $this->leadCreatedAt;
    }

    public function setLeadCreatedAt(?\DateTimeInterface $leadCreatedAt): self
    {
        $this->leadCreatedAt = $leadCreatedAt;

        return $this;
    }

    public function getCreatedLead(): ?Lead
    {
        return $this->createdLead;
    }

    public function setCreatedLead(?Lead $createdLead): self
    {
        $this->createdLead = $createdLead;

        return $this;
    }
}
