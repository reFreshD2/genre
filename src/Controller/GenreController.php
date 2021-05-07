<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Repository\GenreRepository;
use App\Repository\ValueOfFeatureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/genre")
 */
class GenreController extends AbstractController
{
    private $genreRepository;
    private $valueOfFeatureRepository;

    public function __construct(
        GenreRepository $genreRepository,
        ValueOfFeatureRepository $valueOfFeatureRepository
    )
    {
        $this->genreRepository = $genreRepository;
        $this->valueOfFeatureRepository = $valueOfFeatureRepository;
    }

    /**
     * @Route("/", name="genre_index", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('genre/index.html.twig', [
            'genres' => $this->genreRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="genre_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $genre = new Genre();
        $genre->setChangeAt(new \DateTime('now'));

        if ($request->request->has('submit')) {
            $name = $request->request->get('name');
            $genre->setName($name);
            $genre->setAlias();
            $entityManager = $this->getDoctrine()->getManager();
            if (count($this->genreRepository->findBy(['name' => $name])) !== 0) {
                return $this->redirectToRoute('genre_index');
            }
            $entityManager->persist($genre);
            $entityManager->flush();

            return $this->redirectToRoute('genre_index');
        }

        return $this->render('genre/new.html.twig');
    }

    /**
     * @Route("/{id}/edit", name="genre_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Genre $genre): Response
    {
        if ($request->request->has('submit')) {
            $genre->setName($request->request->get('name'));
            $genre->setAlias();
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('genre_index');
        }

        return $this->render('genre/edit.html.twig', [
            'genre' => $genre
        ]);
    }

    /**
     * @Route("/{id}", name="genre_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Genre $genre): Response
    {
        if ($this->isCsrfTokenValid('delete'.$genre->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();

            $valuesOfFeature = $this->valueOfFeatureRepository->findBy(['genre' => $genre]);
            foreach ($valuesOfFeature as $value) {
                $entityManager->remove($value);
            }

            $entityManager->remove($genre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('genre_index');
    }
}
