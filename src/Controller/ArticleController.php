<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{

    //  Définition des controlleurs des pages statiques { Articles }

    #[Route('/les_bonnes_pratiques', name: 'pratiques')]
    public function bonnes_pratiques(): Response
    {
        return $this->render('article/bonnes_pratiques.html.twig', [
            'controller_name' => 'ArticleController',
        ]);
    }

    #[Route('/la_diversification_alimentaire', name: 'diversification')]
    public function diversification_alimentaire(): Response
    {
        return $this->render('article/diversification_alimentaire.html.twig', [
            'controller_name' => 'ArticleController',
        ]);
    }

    #[Route('/l_alimentation_des_adolescents', name: 'alimentation')]
    public function alimentation_adolescents(): Response
    {
        return $this->render('article/alimentation_adolescents.html.twig', [
            'controller_name' => 'ArticleController',
        ]);
    }
}
