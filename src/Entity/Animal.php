<?php

namespace App\Entity;

use App\Repository\AnimalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnimalRepository::class)]
class Animal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $averageSize = null;

    #[ORM\Column(length: 255)]
    private ?string $averageLifespan = null;

    #[ORM\Column(length: 255)]
    private ?string $martialArt = null;

    #[ORM\Column(length: 255)]
    private ?string $phoneNumber = null;

    /**
     * @var Collection<int, Country>
     */
    #[ORM\ManyToMany(targetEntity: Country::class, inversedBy: 'animals')]
    private Collection $country;

    public function __construct()
    {
        $this->country = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getAverageSize(): ?int
    {
        return $this->averageSize;
    }

    public function setAverageSize(int $averageSize): static
    {
        $this->averageSize = $averageSize;

        return $this;
    }

    public function getAverageLifespan(): ?int
    {
        return $this->averageLifespan;
    }

    public function setAverageLifespan(int $averageLifespan): static
    {
        $this->averageLifespan = $averageLifespan;

        return $this;
    }

    public function getMartialArt(): ?string
    {
        return $this->martialArt;
    }

    public function setMartialArt(string $martialArt): static
    {
        $this->martialArt = $martialArt;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): static
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * @return Collection<int, Country>
     */
    public function getCountry(): Collection
    {
        return $this->country;
    }

    public function addCountry(Country $country): static
    {
        if (!$this->country->contains($country)) {
            $this->country->add($country);
        }

        return $this;
    }

    public function removeCountry(Country $country): static
    {
        $this->country->removeElement($country);

        return $this;
    }

    public function toArray()
    {
    }
}
