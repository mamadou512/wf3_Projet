<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    #[Route('/test', name: 'test')]
    public function index(): Response
    {
        $prenom = 'Mamadou';
        $nom    = 'BAH';


        return $this->render('test/index', [
            'prenom' => $prenom,
            'lastname' => $nom,
        ]);
    }

    #[Route('/exercice-twig', name: 'exercice_twig')]
    public function exerciceTwig(): Response

    {
        return $this->render('test/exercice_twig.html.twig');
    }
}
