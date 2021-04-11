<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Repository\FeatureRepository;
use App\Repository\GenreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/features_of_genre")
 */
class FeaturesOfGenreController extends AbstractController
{
    private $genreRepository;
    private $featureRepository;

    public function __construct(GenreRepository $genreRepository, FeatureRepository $featureRepository)
    {
        $this->genreRepository = $genreRepository;
        $this->featureRepository =$featureRepository;
    }

    /**
     * @Route("/", name="features_of_genre_index", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('features_of_genre/index.html.twig', [
            'genres' => $this->genreRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="features_of_genre_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Genre $genre): Response
    {
        if ($request->request->has('submit')) {
            $genre->getFeatures()->clear();
            $featuresId = $request->request->get('features');
            foreach ($featuresId as $id) {
                $feature = $this->featureRepository->findOneBy(['id' => $id]);
                $genre->addFeature($feature);
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('features_of_genre_index');
        }

        return $this->render('features_of_genre/edit.html.twig', [
            'genre' => $genre,
            'features' => $this->featureRepository->findAll()
        ]);
    }
}
