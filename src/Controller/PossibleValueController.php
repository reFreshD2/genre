<?php

namespace App\Controller;

use App\Entity\PossibleValue;
use App\Form\PossibleValueType;
use App\Repository\PossibleValueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/possible_value")
 */
class PossibleValueController extends AbstractController
{
    /**
     * @Route("/", name="possible_value_index", methods={"GET"})
     */
    public function index(PossibleValueRepository $possibleValueRepository): Response
    {
        return $this->render('possible_value/index.html.twig', [
            'possible_values' => $possibleValueRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="possible_value_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $possibleValue = new PossibleValue();
        $form = $this->createForm(PossibleValueType::class, $possibleValue);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($possibleValue);
            $entityManager->flush();

            return $this->redirectToRoute('possible_value_index');
        }

        return $this->render('possible_value/new.html.twig', [
            'possible_value' => $possibleValue,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="possible_value_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, PossibleValue $possibleValue): Response
    {
        $form = $this->createForm(PossibleValueType::class, $possibleValue);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('possible_value_index');
        }

        return $this->render('possible_value/edit.html.twig', [
            'possible_value' => $possibleValue,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="possible_value_delete", methods={"DELETE"})
     */
    public function delete(Request $request, PossibleValue $possibleValue): Response
    {
        if ($this->isCsrfTokenValid('delete'.$possibleValue->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($possibleValue);
            $entityManager->flush();
        }

        return $this->redirectToRoute('possible_value_index');
    }
}
