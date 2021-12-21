<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StaticController extends AbstractController
{
    #[Route('/cgv', name: 'cgv')]
    public function index(): Response
    {
        return $this->render('static/cgv.html.twig', [
            'controller_name' => 'StaticController',
        ]);
    }
}
