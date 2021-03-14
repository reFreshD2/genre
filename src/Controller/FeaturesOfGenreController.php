<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Form\FeaturesOfGenreType;
use App\Form\GenreType;
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
    /**
     * @Route("/", name="features_of_genre_index", methods={"GET"})
     */
    public function index(GenreRepository $genreRepository): Response
    {
        return $this->render('features_of_genre/index.html.twig', [
            'genres' => $genreRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="features_of_genre_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Genre $genre): Response
    {
        $form = $this->createForm(FeaturesOfGenreType::class, $genre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('features_of_genre_index');
        }

        return $this->render('features_of_genre/edit.html.twig', [
            'genre' => $genre,
            'form' => $form->createView(),
        ]);
    }
}
