<?php

namespace App\Traits;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

trait HasTimeStamps
{
    /**
     * @ORM\Column(type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    public DateTime $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    public DateTime $updatedAt;

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
}