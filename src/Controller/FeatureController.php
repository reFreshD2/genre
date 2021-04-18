<?php

namespace App\Controller;

use App\Entity\Feature;
use App\Repository\FeatureRepository;
use App\Repository\PossibleValueRepository;
use App\Repository\ValueOfFeatureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/feature")
 */
class FeatureController extends AbstractController
{
    private $featureRepository;
    private $possibleValueRepository;
    private $valueOfFeatureRepository;

    public function __construct(
        FeatureRepository $featureRepository,
        PossibleValueRepository $possibleValueRepository,
        ValueOfFeatureRepository $valueOfFeatureRepository
    )
    {
        $this->featureRepository = $featureRepository;
        $this->possibleValueRepository = $possibleValueRepository;
        $this->valueOfFeatureRepository = $valueOfFeatureRepository;
    }

    /**
     * @Route("/", name="feature_index", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('feature/index.html.twig', [
            'features' => $this->featureRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="feature_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $feature = new Feature();

        if ($request->request->has('submit')) {
            $feature->setName($request->request->get('name'));
            $feature->setAlias();
            $feature->setType($request->request->get('type'));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($feature);
            $entityManager->flush();

            return $this->redirectToRoute('feature_index');
        }

        return $this->render('feature/new.html.twig');
    }

    /**
     * @Route("/{id}/edit", name="feature_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Feature $feature): Response
    {
        if ($request->request->has('submit')) {
            if ($feature->getType() !== $request->request->get('type')) {
                $possibleValue = $this->possibleValueRepository->findOneBy(['feature' => $feature]);
                $valuesOfFeature = $this->valueOfFeatureRepository->findBy(['feature' => $feature]);

                if ($possibleValue) {
                    $possibleValue->setValue("");
                }
                foreach ($valuesOfFeature as $value) {
                    $value->setValue("");
                }
            }
            $feature->setName($request->request->get('name'));
            $feature->setAlias();
            $feature->setType($request->request->get('type'));

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('feature_index');
        }

        return $this->render('feature/edit.html.twig', [
            'feature' => $feature,
        ]);
    }

    /**
     * @Route("/{id}", name="feature_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Feature $feature): Response
    {
        if ($this->isCsrfTokenValid('delete'.$feature->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();

            $possibleValue = $this->possibleValueRepository->findOneBy(['feature' => $feature]);
            $valuesOfFeature = $this->valueOfFeatureRepository->findBy(['feature' => $feature]);

            if ($possibleValue) {
                $entityManager->remove($possibleValue);
            }
            foreach ($valuesOfFeature as $value) {
                $entityManager->remove($value);
            }

            $entityManager->remove($feature);
            $entityManager->flush();
        }

        return $this->redirectToRoute('feature_index');
    }
}
