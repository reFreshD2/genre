<?php

namespace App\Entity;

use App\Repository\FeatureRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FeatureRepository::class)
 */
class Feature
{
    public const QUALITATIVE = 0;
    public const QUANTITATIVE = 1;

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
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $alias;

    /**
     * @ORM\OneToOne(targetEntity=PossibleValue::class, mappedBy="feature", cascade={"persist", "remove"})
     */
    private $possibleValue;

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

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(?int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function setAlias(string $alias): self
    {
        $this->alias = $alias;

        return $this;
    }

    public function getPossibleValue(): ?PossibleValue
    {
        return $this->possibleValue;
    }

    public function setPossibleValue(?PossibleValue $possibleValue): self
    {
        // unset the owning side of the relation if necessary
        if ($possibleValue === null && $this->possibleValue !== null) {
            $this->possibleValue->setFeature(null);
        }

        // set the owning side of the relation if necessary
        if ($possibleValue !== null && $possibleValue->getFeature() !== $this) {
            $possibleValue->setFeature($this);
        }

        $this->possibleValue = $possibleValue;

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
