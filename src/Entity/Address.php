<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable
 */
class Address
{
    /**
     * @ORM\Column(type="string", length=180, nullable=true)
     */
    private ?string $street;

    /**
     * @ORM\Column(type="string", length=180, nullable=true)
     */
    private ?string $landmark;

    /**
     * @ORM\Column(type="string", length=180, nullable=true)
     */
    private ?string $locality;

    /**
     * @ORM\Column(type="string", length=180, nullable=true)
     */
    private ?string $district;

    /**
     * @ORM\Column(type="string", length=80, nullable=true)
     */
    private ?string $state;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private ?string $postalCode;

    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    private ?string $plusCode;

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street)
    {
        $this->street = $street;

        return $this;
    }

    public function getLandmark(): ?string
    {
        return $this->landmark;
    }

    public function setLandmark(string $landmark)
    {
        $this->landmark = $landmark;

        return $this;
    }

    public function getLocality(): ?string
    {
        return $this->locality;
    }

    public function setLocality(string $locality)
    {
        $this->locality = $locality;

        return $this;
    }

    public function getDistrict(): ?string
    {
        return $this->district;
    }

    public function setDistrict(string $district)
    {
        $this->district = $district;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state)
    {
        $this->state = $state;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode)
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getPlusCode(): ?string
    {
        return $this->plusCode;
    }

    public function setPlusCode(string $plusCode)
    {
        $this->plusCode = $plusCode;

        return $this;
    }
}
