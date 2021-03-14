<?php

namespace App\Controller;

use App\Entity\ValueOfFeature;
use App\Form\ValueOfFeatureType;
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
     * @Route("/", name="value_of_feature_index", methods={"GET"})
     */
    public function index(GenreRepository $genreRepository, ValueOfFeatureRepository $valueOfFeatureRepository): Response
    {
        return $this->render('value_of_feature/index.html.twig', [
            'genres' => $genreRepository->findAll(),
            'valueOfFeature' => $valueOfFeatureRepository->findAll()
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
        $valueOfFeature->setFeature($featureRepository->findOneBy([
            'name' => $request->attributes->get('feature')
        ]));
        $form = $this->createForm(ValueOfFeatureType::class, $valueOfFeature);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($valueOfFeature);
            $entityManager->flush();

            return $this->redirectToRoute('value_of_feature_index');
        }

        return $this->render('value_of_feature/new.html.twig', [
            'value_of_feature' => $valueOfFeature,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="value_of_feature_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ValueOfFeature $valueOfFeature): Response
    {
        $form = $this->createForm(ValueOfFeatureType::class, $valueOfFeature);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('value_of_feature_index');
        }

        return $this->render('value_of_feature/edit.html.twig', [
            'value_of_feature' => $valueOfFeature,
            'form' => $form->createView(),
        ]);
    }
}
