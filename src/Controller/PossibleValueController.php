<?php

namespace App\Controller;

use App\Entity\PossibleValue;
use App\Entity\Feature;
use App\Repository\FeatureRepository;
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
    public function index(FeatureRepository $featureRepository): Response
    {
        return $this->render('possible_value/index.html.twig', [
            'features' => $featureRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new/{feature_id}", name="possible_value_new", methods={"GET","POST"})
     */
    public function new(Request $request, FeatureRepository $featureRepository): Response
    {
        $feature = $featureRepository->findOneBy(['id' => $request->attributes->get('feature_id')]);
        $possibleValue = new PossibleValue();
        $possibleValue->setFeature($feature);

        if ($request->request->has('submit')) {
            if ($feature->getType() === Feature::QUALITATIVE) {
                $values = $request->request->get('values');
                $possibleValue->setValue(implode(',', $values));
            } else {
                $min = min($request->request->get('min'), $request->request->get('max'));
                $max = max($request->request->get('min'), $request->request->get('max'));
                $possibleValue->setValue("[$min-$max]");
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($possibleValue);
            $entityManager->flush();

            return $this->redirectToRoute('possible_value_index');
        }

        return $this->render('possible_value/new.html.twig', [
            'possible_value' => $possibleValue,
            'feature' => $feature
        ]);
    }

    /**
     * @Route("/{id}/edit", name="possible_value_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, PossibleValue $possibleValue): Response
    {
        if ($request->request->has('submit')) {
            if ($possibleValue->getFeature()->getType() === Feature::QUALITATIVE) {
                $values = $request->request->get('values');
                $possibleValue->setValue(implode(',', $values));
            } else {
                $min = min($request->request->get('min'), $request->request->get('max'));
                $max = max($request->request->get('min'), $request->request->get('max'));
                $possibleValue->setValue("[$min-$max]");
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('possible_value_index');
        }

        return $this->render('possible_value/edit.html.twig', [
            'possible_value' => $possibleValue,
            'values' => json_decode($possibleValue->getValue(), true)
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
