<?php

namespace App\Controller;

use App\Repository\FeatureRepository;
use App\Repository\GenreRepository;
use App\Repository\PossibleValueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ValidationController extends AbstractController
{
    private $genreRepository;
    private $featureRepository;
    private $possibleValueRepository;

    public function __construct(
        GenreRepository $genreRepository,
        FeatureRepository $featureRepository,
        PossibleValueRepository $possibleValueRepository
    )
    {
        $this->genreRepository = $genreRepository;
        $this->featureRepository = $featureRepository;
        $this->possibleValueRepository = $possibleValueRepository;
    }

    /**
     * @Route("admin/validation", name="validation")
     */
    public function index(): Response
    {
        return $this->render('validation/index.html.twig', [
            'report' => $this->validate(),
        ]);
    }

    private function validate(): array
    {
        $report = [];
        foreach ($this->genreRepository->findAll() as $genre) {
            if (empty($genre->getFeatures())) {
                $report[$genre->getName()] = [
                    "У жанра \"$genre\" отсутствуют признаки",
                    $this->generateUrl('features_of_genre_edit', ['id' => $genre->getId()])
                ];
            }
        }
        foreach ($this->featureRepository->findAll() as $feature) {
            if ($feature->getType() === null) {
                $report[$feature->getName()] = [
                    "У признака \"$feature\" отсутствует тип",
                    $this->generateUrl('feature_edit', ['id' => $feature->getId()])
                ];
            }
            if ($feature->getPossibleValue() === null) {
                $report[$feature->getName()] = [
                    "У признака \"$feature\" отсутствуют возможные значения",
                    $this->generateUrl('possible_value_new')
                ];
            }
        }
        foreach ($this->possibleValueRepository->findAll() as $possibleValue) {
            $value = \json_decode($possibleValue->getValue(), true);
            if (empty($value)) {
                $feature = $possibleValue->getFeature();
                $report[$feature->getName()] = [
                    "У признака \"$feature\" возможное значение имеет пустое значение",
                    $this->generateUrl('possible_value_edit', ['id' => $possibleValue->getId()])
                ];
            }
        }
        return $report;
    }
}
