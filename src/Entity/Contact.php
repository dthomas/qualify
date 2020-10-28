<?php

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable
 */
class Contact
{
    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private ?string $phone;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private ?bool $isPhoneVerified;

    /**
     * @ORM\Column(type="string", length=80, nullable=true)
     */
    private ?string $email;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private ?bool $isEmailVerified;

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone)
    {
        $this->phone = $phone;

        return $this;
    }

    public function getIsPhoneVerified(): ?bool
    {
        return $this->isPhoneVerified;
    }

    public function setIsPhoneVerified(bool $isPhoneVerified)
    {
        $this->isPhoneVerified = $isPhoneVerified;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email)
    {
        $this->email = $email;

        return $this;
    }

    public function getIsEmailVerified(): ?bool
    {
        return $this->isEmailVerified;
    }

    public function setIsEmailVerified(bool $isPhoneVerified)
    {
        $this->isEmailVerified = $isPhoneVerified;

        return $this;
    }
}