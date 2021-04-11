<?php

namespace App\Controller;

use App\Entity\Feature;
use App\Entity\ValueOfFeature;
use App\Repository\FeatureRepository;
use App\Repository\GenreRepository;
use App\Repository\ValueOfFeatureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/value_of_feature")
 */
class ValueOfFeatureController extends AbstractController
{
    /**
     * @Route("/{genreId}", name="value_of_feature_index", methods={"GET", "POST"}, requirements={"genreId"="\d+"})
     */
    public function index(
        GenreRepository $genreRepository,
        ValueOfFeatureRepository $valueOfFeatureRepository,
        Request $request,
        $genreId = null
    ): Response
    {
        $genre = $genreId;
        if ($request->request->has('submit')) {
            $genre = $genreRepository->findOneBy(['id' => $request->request->get('select')]);
        }
        if ($genreId) {
            $genre = $genreRepository->findOneBy(['id' => $genreId]);
        }

        return $this->render('value_of_feature/index.html.twig', [
            'genres' => $genreRepository->findAll(),
            'valueOfFeature' => $valueOfFeatureRepository->findAll(),
            'genre' => $genre
        ]);
    }

    /**
     * @Route("/new_{genre}_{feature}", name="value_of_feature_new", methods={"GET","POST"})
     */
    public function new(Request $request, GenreRepository $genreRepository, FeatureRepository $featureRepository): Response
    {
        $valueOfFeature = new ValueOfFeature();
        $valueOfFeature->setGenre($genreRepository->findOneBy([
            'name' => $request->attributes->get('genre')
        ]));
        $feature = $featureRepository->findOneBy(['name' => $request->attributes->get('feature')]);
        $valueOfFeature->setFeature($feature);

        if ($request->request->has('submit')) {
            if ($feature->getType() === Feature::QUALITATIVE) {
                $values = $request->request->get('values');
                if (!empty($values)) {
                    $valueOfFeature->setValue(implode(',', $values));
                } else {
                    $valueOfFeature->setValue("");
                }
            } else {
                $min = min($request->request->get('min'), $request->request->get('max'));
                $max = max($request->request->get('min'), $request->request->get('max'));
                $valueOfFeature->setValue("[$min-$max]");
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($valueOfFeature);
            $entityManager->flush();

            return $this->redirectToRoute('value_of_feature_index', [
                'genreId' => $valueOfFeature->getGenre()->getId()
            ]);
        }

        return $this->render('value_of_feature/new.html.twig', [
            'value_of_feature' => $valueOfFeature,
            'possible_values' => json_decode($feature->getPossibleValue()->getValue(), true)
        ]);
    }

    /**
     * @Route("/{id}/edit", name="value_of_feature_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ValueOfFeature $valueOfFeature): Response
    {
        if ($request->request->has('submit')) {
            if ($valueOfFeature->getFeature()->getType() === Feature::QUALITATIVE) {
                $values = $request->request->get('values');
                if (!empty($values)) {
                    $valueOfFeature->setValue(implode(',', $values));
                } else {
                    $valueOfFeature->setValue("");
                }
            } else {
                $min = min($request->request->get('min'), $request->request->get('max'));
                $max = max($request->request->get('min'), $request->request->get('max'));
                $valueOfFeature->setValue("[$min-$max]");
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('value_of_feature_index', [
                'genreId' => $valueOfFeature->getGenre()->getId()
            ]);
        }

        return $this->render('value_of_feature/edit.html.twig', [
            'value_of_feature' => $valueOfFeature,
            'values' => json_decode($valueOfFeature->getValue(), true),
            'possible_values' => json_decode($valueOfFeature->getFeature()->getPossibleValue()->getValue(), true)
        ]);
    }
}
