<?php

namespace App\Controller;

use App\Entity\FruitsLegumes;
use App\Form\FruitsLegumesType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\FruitsLegumesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CalendrierController extends AbstractController
{
    #[Route('/calendrier', name: 'calendrier')]
    public function affiche(FruitsLegumesRepository $repository): Response
    {
        $legumes_hiv = $repository->findBy(
                array('type' =>'légumes','saison' =>'hiver')

            );
        $fruits_hiv = $repository->findBy(
                array('type' =>'fruits','saison' =>'hiver')

            );

        $legumes_prin = $repository->findBy(
                array('type' =>'légumes','saison' =>'printemps')

            );
        $fruits_prin = $repository->findBy(
                array('type' =>'fruits','saison' =>'printemps')

            );

        $legumes_ete = $repository->findBy(
                array('type' =>'légumes','saison' =>'ete')

            );
        $fruits_ete = $repository->findBy(
                array('type' =>'fruits','saison' =>'ete')

            );

        $legumes_aut = $repository->findBy(
                array('type' =>'légumes','saison' =>'automne')

            );
        $fruits_aut = $repository->findBy(
                array('type' =>'fruits','saison' =>'automne')

            );
        
        return $this->render('calendrier/calendrier.html.twig', [
            'controller_name' => 'CalendrierController',
            'fruits_hiv' =>$fruits_hiv,
            'legumes_hiv'=>$legumes_hiv,

            'fruits_prin' =>$fruits_prin,
            'legumes_prin'=>$legumes_prin,

            'fruits_ete' =>$fruits_ete,
            'legumes_ete'=>$legumes_ete,

            'fruits_aut' =>$fruits_aut,
            'legumes_aut'=>$legumes_aut

            
        ]);

        
    }
    


}