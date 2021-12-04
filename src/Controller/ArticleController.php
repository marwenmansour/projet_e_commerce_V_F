<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
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
}
