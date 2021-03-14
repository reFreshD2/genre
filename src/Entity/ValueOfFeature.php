<?php

namespace App\Entity;

use App\Repository\ValueOfFeatureRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ValueOfFeatureRepository::class)
 */
class ValueOfFeature
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Feature::class)
     */
    private $feature;

    /**
     * @ORM\Column(type="text")
     */
    private $value;

    /**
     * @ORM\ManyToOne(targetEntity=Genre::class, inversedBy="valuesOfFeatures")
     */
    private $genre;

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

    public function setValue(string $value): self
    {
        $matches = [];
        $result = [];
        if (preg_match('/^([ёа-яЁА-Я]+,|[а-яА-Я]+)+$/u', $value)) {
            $result = explode(',', $value);
        } elseif (preg_match('/^\[(\d+)-(\d+)\]$/', $value, $matches)) {
            $result = [$matches[1], $matches[2]];
        }
        $this->value = \json_encode($result);

        return $this;
    }

    public function getGenre(): ?Genre
    {
        return $this->genre;
    }

    public function setGenre(?Genre $genre): self
    {
        $this->genre = $genre;

        return $this;
    }
}
