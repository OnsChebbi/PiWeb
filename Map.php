<?php

namespace App\Entity;

use App\Repository\MapRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MapRepository::class)
 */
class Map
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Ville;

    /**
     * @ORM\Column(type="float")
     */
    private $Longitude;

    /**
     * @ORM\Column(type="float")
     */
    private $Latitude;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVille(): ?string
    {
        return $this->Ville;
    }

    public function setVille(string $Ville): self
    {
        $this->Ville = $Ville;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->Longitude;
    }

    public function setLongitude(float $Longitude): self
    {
        $this->Longitude = $Longitude;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->Latitude;
    }

    public function setLatitude(float $Latitude): self
    {
        $this->Latitude = $Latitude;

        return $this;
    }
}
