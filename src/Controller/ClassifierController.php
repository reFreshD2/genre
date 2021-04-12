<?php

namespace App\Controller;

use App\Entity\Feature;
use App\Repository\FeatureRepository;
use App\Repository\GenreRepository;
use App\Repository\PossibleValueRepository;
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
    private $possibleValueRepository;

    public function __construct(
        FeatureRepository $featureRepository,
        GenreRepository $genreRepository,
        Validator $validator,
        ValueOfFeatureRepository $valueOfFeatureRepository,
        PossibleValueRepository $possibleValueRepository
    )
    {
        $this->featureRepository = $featureRepository;
        $this->genreRepository = $genreRepository;
        $this->validator = $validator;
        $this->valueOfFeatureRepository = $valueOfFeatureRepository;
        $this->possibleValueRepository = $possibleValueRepository;
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
    public function classify(Request $request): Response
    {
        if ($request->request->has('submit')) {
            $explain = [];
            $genres = $this->genreRepository->findAll();
            $features = $this->featureRepository->findAll();
            $notValidItem = array_keys($this->validator->validate());

            $features = $this->deleteNotValid($features, $notValidItem);
            $genres = $this->deleteNotValid($genres, $notValidItem);

            foreach ($features as $feature) {
                if (!$request->request->has($feature->getAlias())) {
                    continue;
                }
                $value = $request->request->get($feature->getAlias());

                foreach ($genres as $genre) {
                    $valuesForGenre = $this->valueOfFeatureRepository->findOneBy([
                        'genre' => $genre,
                        'feature' => $feature
                    ]);
                    if (!$valuesForGenre) {
                        $explain[$genre->getName()] = "Жанр не подходит, т.к. признак \"$feature\" не задан у данного жанра";
                        unset($genres[array_search($genre,$genres)]);
                        continue;
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
                            $explain[$genre->getName()] = "Жанр не подходит, т.к. значение признака \"$feature\" - $value не входит во множество значений данного признака.";
                            unset($genres[array_search($genre,$genres)]);
                        }
                    } else {
                        if ($value < $valuesForGenre[0]) {
                            $explain[$genre->getName()] = "Жанр не подходит, т.к. значение признака \"$feature\" - $value меньше нижней границы ($valuesForGenre[0]) множества значений данного признака.";
                            unset($genres[array_search($genre,$genres)]);
                        }
                        if ($value > $valuesForGenre[1]) {
                            $explain[$genre->getName()] = "Жанр не подходит, т.к. значение признака \"$feature\" - $value больше верхней границы ($valuesForGenre[1]) множества значений данного признака.";
                            unset($genres[array_search($genre,$genres)]);
                        }
                    }
                    array_filter($genres);
                }
            }

            return $this->render('classifier/answer.html.twig', [
                'genres' => $genres,
                'explain' => $explain
            ]);
        }

        $featuresId = $request->query->get('features');
        $features = [];
        $possibleValues = [];
        if (!$featuresId) {
            return $this->render('classifier/answer.html.twig', [
                'genres' => $this->genreRepository->findAll(),
                'explain' => null
            ]);
        }
        foreach ($featuresId as $id) {
            $features[] = $this->featureRepository->findOneBy(['id' => $id]);
        }
        foreach ($features as $feature) {
            $possibleValue = $this->possibleValueRepository->findOneBy(['feature' => $feature]);
            $possibleValues[$feature->getAlias()] = $possibleValue->getValue();
        }

        return $this->render('classifier/form.html.twig', [
            'features' => $features,
            'possibleValues' => $possibleValues
        ]);
    }

    /**
     * @Route("/classifier_features", name="classifier_features")
     */
    public function selectFeature(Request $request): Response
    {
        if ($request->request->has('submit')) {
            return $this->redirectToRoute('classifier_make', ['features' => $request->request->get('features')]);
        }

        $features = $this->featureRepository->findAll();
        $notValidItems = array_keys($this->validator->validate());

        return $this->render('classifier/features.html.twig', [
            'features' => $this->deleteNotValid($features, $notValidItems)
        ]);
    }

    private function deleteNotValid(array $heystack, array $notValid): array {
        foreach ($heystack as $item) {
            foreach ($notValid as $notValidItem) {
                if ($item->getName() === $notValidItem) {
                    unset($heystack[array_search($item, $heystack)]);
                }
            }
        }

        return array_filter($heystack);
    }
}
