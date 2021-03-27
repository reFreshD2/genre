<?php

namespace App\Entity;

use App\Repository\PossibleValueRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PossibleValueRepository::class)
 */
class PossibleValue
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=Feature::class, inversedBy="possibleValue", cascade={"persist", "remove"})
     */
    private $feature;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $value;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFeature(): ?Feature
    {
        return $this->feature;
    }

    public function setFeature(?Feature $feature): self
    {
        $this->feature = $feature;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): self
    {
        $matches = [];
        $result = [];
        if (preg_match('/^\[(\d+)-(\d+)\]$/', $value, $matches)) {
            $result = [$matches[1], $matches[2]];
        } elseif ($value) {
            $result = explode(',', $value);
        }
        $this->value = \json_encode($result);

        return $this;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
