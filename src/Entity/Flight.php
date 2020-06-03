<?php

namespace App\Entity;

use App\Repository\FlightRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FlightRepository::class)
 */
class Flight
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $from_city;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $toCity;

    /**
     * @ORM\Column(type="integer")
     */
    private $seatsCount;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="smallint")
     */
    private $status;

    public const STATUS_ACTUAL = 1;
    public const STATUS_CANCALED = 0;
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFromCity(): ?string
    {
        return $this->from_city;
    }

    public function setFromCity(string $from_city): self
    {
        $this->from_city = $from_city;

        return $this;
    }

    public function getToCity(): ?string
    {
        return $this->toCity;
    }

    public function setToCity(string $toCity): self
    {
        $this->toCity = $toCity;

        return $this;
    }

    public function getSeatsCount(): ?int
    {
        return $this->seatsCount;
    }

    public function setSeatsCount(int $seatsCount): self
    {
        $this->seatsCount = $seatsCount;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }
}
