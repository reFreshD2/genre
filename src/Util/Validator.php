<?php

namespace App\Util;

use App\Entity\Feature;
use App\Repository\FeatureRepository;
use App\Repository\GenreRepository;
use App\Repository\PossibleValueRepository;
use App\Repository\ValueOfFeatureRepository;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Validator
{
    private $genreRepository;
    private $featureRepository;
    private $possibleValueRepository;
    private $valueOfFeatureRepository;
    private $urlGenerator;

    public function __construct(
        GenreRepository $genreRepository,
        FeatureRepository $featureRepository,
        PossibleValueRepository $possibleValueRepository,
        ValueOfFeatureRepository $valueOfFeatureRepository,
        UrlGeneratorInterface $urlGenerator
    )
    {
        $this->genreRepository = $genreRepository;
        $this->featureRepository = $featureRepository;
        $this->possibleValueRepository = $possibleValueRepository;
        $this->valueOfFeatureRepository = $valueOfFeatureRepository;
        $this->urlGenerator = $urlGenerator;
    }

    public function validate(): array
    {
        $report = [];
        foreach ($this->genreRepository->findAll() as $genre) {
            if ($genre->getFeatures()->isEmpty()) {
                $report[$genre->getName()] = [
                    "У жанра \"$genre\" отсутствуют признаки",
                    $this->urlGenerator->generate('features_of_genre_edit', ['id' => $genre->getId()])
                ];
                continue;
            }
            foreach ($genre->getFeatures() as $feature) {
                if (!$this->valueOfFeatureRepository->findOneBy(['genre' => $genre, 'feature' => $feature])) {
                    $report[$genre->getName()] = [
                        "У жанра \"$genre\" для признака \"$feature\" отсутствует значение",
                        $this->urlGenerator->generate('value_of_feature_new', [
                            'genre' => $genre,
                            'feature' => $feature
                        ])
                    ];
                }
            }
        }
        foreach ($this->featureRepository->findAll() as $feature) {
            if ($feature->getType() === null) {
                $report[$feature->getName()] = [
                    "У признака \"$feature\" отсутствует тип",
                    $this->urlGenerator->generate('feature_edit', ['id' => $feature->getId()])
                ];
            }
            if ($feature->getPossibleValue() === null) {
                $report[$feature->getName()] = [
                    "У признака \"$feature\" отсутствуют возможные значения",
                    $this->urlGenerator->generate('possible_value_new', ['feature_id' => $feature->getId()])
                ];
            }
        }
        foreach ($this->possibleValueRepository->findAll() as $possibleValue) {
            $value = \json_decode($possibleValue->getValue(), true);
            if (empty($value)) {
                $feature = $possibleValue->getFeature();
                $report[$feature->getName()] = [
                    "У признака \"$feature\" возможное значение имеет пустое значение",
                    $this->urlGenerator->generate('possible_value_edit', ['id' => $possibleValue->getId()])
                ];
            }
        }
        foreach ($this->valueOfFeatureRepository->findAll() as $valueOfFeature) {
            $value = \json_decode($valueOfFeature->getValue(), true);
            $feature = $valueOfFeature->getFeature();
            $genre = $valueOfFeature->getGenre();
            if (empty($value)) {
                $report[$feature->getName()] = [
                    "Признак \"$feature\" для жанра \"$genre\" имеет пустое значение",
                    $this->urlGenerator->generate('value_of_feature_edit', ['id' => $valueOfFeature->getId()])
                ];
                continue;
            }
            $possibleValue = \json_decode($valueOfFeature->getFeature()->getPossibleValue()->getValue(), true);
            if (
                $valueOfFeature->getFeature()->getType() === Feature::QUANTITATIVE
                && ($value[0] < $possibleValue[0] || $value[1] > $possibleValue[1])
            ) {
                $report[$feature->getName()] = [
                    "Значение признака \"$feature\" для жанра \"$genre\" должно соответствовать возможным значениям." .
                    "Получено [$value[0]-$value[1]] : Необходимо [$possibleValue[0]-$possibleValue[1]].",
                    $this->urlGenerator->generate('value_of_feature_edit', ['id' => $valueOfFeature->getId()])
                ];
            }
        }
        return $report;
    }
}
