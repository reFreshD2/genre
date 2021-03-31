<?php

namespace App\Controller;

use App\Entity\Feature;
use App\Repository\FeatureRepository;
use App\Repository\GenreRepository;
use App\Repository\ValueOfFeatureRepository;
use App\Util\Validator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClassifierController extends AbstractController
{
    private $featureRepository;
    private $genreRepository;
    private $validator;
    private $valueOfFeatureRepository;

    public function __construct(
        FeatureRepository $featureRepository,
        GenreRepository $genreRepository,
        Validator $validator,
        ValueOfFeatureRepository $valueOfFeatureRepository
    )
    {
        $this->featureRepository = $featureRepository;
        $this->genreRepository = $genreRepository;
        $this->validator = $validator;
        $this->valueOfFeatureRepository = $valueOfFeatureRepository;
    }

    /**
     * @Route("/", name="classifier")
     */
    public function index(): Response
    {
        return $this->render('classifier/index.html.twig');
    }

    /**
     * @Route("/classifier", name="classifier_make")
     */
    public function classify(Request $request, array $features): Response
    {
        if ($request->request->has('submit')) {
            $explain = [];
            $genres = $this->genreRepository->findAll();
            $notValidItem = array_keys($this->validator->validate());

            array_map(function ($genre) use ($notValidItem) {
                return !in_array($genre->getName(), $notValidItem) ? $genre : null;
            }, $genres);
            array_filter($genres);

            foreach ($features as $feature) {
                if (!$request->request->has($feature->getAlias())) {
                    $explain[] = "Признак \"$feature\" не участвовал в классификации, т.к. было передано пустое значение";
                    break;
                }
                $value = $request->request->get($feature->getAlias());
                array_map(function ($genre) use ($value, $feature, &$explain) {
                    $valuesForGenre = $this->valueOfFeatureRepository->findOneBy([
                        'genre' => $genre,
                        'feature' => $feature
                    ]);
                    if (!$valuesForGenre) {
                        $explain[] = "Жанр \"$genre\" не подходит, т.к. признак\"$feature\" не задан у данного жанра";
                        return null;
                    }
                    $valuesForGenre = json_decode($valuesForGenre->getValue(), true);
                    if ($feature->getType() === Feature::QUALITATIVE) {
                        $isFind = false;
                        foreach ($valuesForGenre as $item) {
                            if (strtolower($item) === strtolower($value)) {
                                $isFind = true;
                                break;
                            }
                        }
                        if (!$isFind) {
                            $explain[] = "Жанр \"$genre\" не подходит, т.к. значение признака \"$feature\" - $value не входит во множество значений данного признака.";
                            return null;
                        }
                    } else {
                        if ($value < $valuesForGenre[0]) {
                            $explain[] = "Жанр \"$genre\" не подходит, т.к. значение признака \"$feature\" - $value меньше нижней границы ($valuesForGenre[0]) множества значений данного признака.";
                            return null;
                        }
                        if ($value > $valuesForGenre[1]) {
                            $explain[] = "Жанр \"$genre\" не подходит, т.к. значение признака \"$feature\" - $value больше верхней границы ($valuesForGenre[1]) множества значений данного признака.";
                            return null;
                        }
                    }
                    return $genre;
                }, $genres);
                array_filter($genres);
            }

            return $this->render('classifier/answer.html.twig', [
                'genres' => $genres,
                'explain' => $explain
            ]);
        }

        return $this->render('classifier/form.html.twig', [
            'features' => $this->features
        ]);
    }

    /**
     * @Route("/classifier_features", name="classifier_features")
     */
    public function selectFeature(Request $request): Response
    {
        if ($request->request->has('submit')) {
            $features = [];
            foreach ($request->request->get('features') as $featureId) {
                $features[] = $this->featureRepository->findOneBy(['id' => $featureId]);
            }
            $this->redirectToRoute('classifier_make', ['features' => $features]);
        }

        $features = $this->featureRepository->findAll();
        $notValidItem = array_keys($this->validator->validate());

        array_map(function ($feature) use ($notValidItem) {
            return !in_array($feature->getName(), $notValidItem) ? $feature : null;
        }, $features);
        array_filter($features);

        return $this->render('classifier/features.html.twig', [
            'features' => $features
        ]);
    }
}
