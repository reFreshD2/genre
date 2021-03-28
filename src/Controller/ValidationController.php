<?php

namespace App\Controller;

use App\Util\Validator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ValidationController extends AbstractController
{
    private $validator;

    public function __construct(Validator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @Route("admin/validation", name="validation")
     */
    public function index(): Response
    {
        return $this->render('validation/index.html.twig', [
            'report' => $this->validator->validate(),
        ]);
    }
}
