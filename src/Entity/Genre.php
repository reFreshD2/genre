<?php

namespace App\Entity;

use App\Repository\GenreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GenreRepository::class)
 */
class Genre
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $changeAt;

    /**
     * @ORM\ManyToMany(targetEntity=Feature::class)
     */
    private $features;

    /**
     * @ORM\OneToMany(targetEntity=ValueOfFeature::class, mappedBy="genre")
     */
    private $valuesOfFeatures;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $alias;

    public function __construct()
    {
        $this->features = new ArrayCollection();
        $this->valuesOfFeatures = new ArrayCollection();
    }

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

    public function getChangeAt(): ?\DateTimeInterface
    {
        return $this->changeAt;
    }

    public function setChangeAt(\DateTimeInterface $changeAt): self
    {
        $this->changeAt = $changeAt;

        return $this;
    }

    /**
     * @return Collection|Feature[]
     */
    public function getFeatures(): Collection
    {
        return $this->features;
    }

    public function addFeature(Feature $feature): self
    {
        if (!$this->features->contains($feature)) {
            $this->features[] = $feature;
        }

        return $this;
    }

    public function removeFeature(Feature $feature): self
    {
        $this->features->removeElement($feature);

        return $this;
    }

    /**
     * @return Collection|ValueOfFeature[]
     */
    public function getValuesOfFeatures(): Collection
    {
        return $this->valuesOfFeatures;
    }

    public function addValuesOfFeature(ValueOfFeature $valuesOfFeature): self
    {
        if (!$this->valuesOfFeatures->contains($valuesOfFeature)) {
            $this->valuesOfFeatures[] = $valuesOfFeature;
            $valuesOfFeature->setGenre($this);
        }

        return $this;
    }

    public function removeValuesOfFeature(ValueOfFeature $valuesOfFeature): self
    {
        if ($this->valuesOfFeatures->removeElement($valuesOfFeature)) {
            // set the owning side to null (unless already changed)
            if ($valuesOfFeature->getGenre() === $this) {
                $valuesOfFeature->setGenre(null);
            }
        }

        return $this;
    }

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function setAlias(): self
    {
        $this->alias = "genre_" . (new \DateTimeImmutable())->format("dmY_His");

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
