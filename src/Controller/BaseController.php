<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BaseController extends AbstractController
{
      // Accueil
    #[Route('', name: 'home')]
    public function home(): Response
    {

        return $this->render('base/home.html.twig');

    }
        // Apropos
    #[Route('/a-propos', name: 'about')]
    public function about(): Response
    {
    
            return $this->render('base/about.html.twig');
    
    }


    public function _header(): Response  
    {
           //Requete BDD
           $articles = [
              ['title' => "Article 1"],
              ['title' => "Article 2"],
              ['title' => "Article 3"],
              
           ];


    return $this->render('partials/_header.html.twig', [
        'articles' => $articles

    ]);        
       
        
    }
}
