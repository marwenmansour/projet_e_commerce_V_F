<?php

namespace App\Controller;

use App\Repository\FruitsLegumesRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\FruitsLegumes;
use App\Entity\Panier;
use App\Form\FruitsLegumesType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class PanierController extends AbstractController
{
    #[Route('/panier', name: 'panier'),IsGranted("ROLE_USER")]
    public function index(SessionInterface $session, FruitsLegumesRepository $fruit_legume_repository): Response
    {
        $panier = $session->get('panier',[]);
        $panier_with_data = [];
       foreach($panier as $id => $quantite){
           $panier_with_data[]=[
               'produit' => $fruit_legume_repository->find($id),
               'quantite'=> $quantite,
           ];
       }
       $totalItems = 0;
       foreach($panier_with_data as $item) {
           $totalItem = $item['produit']->getPrix() * $item['quantite'];
           $totalItems += $totalItem ;
       }
       
        return $this->render('panier/index.html.twig', [
            'controller_name' => 'PanierController',
            'items' => $panier_with_data,
            'prix_total'=> $totalItems,
        ]);
    }

    #[Route('/panier/remove/{id}', name: 'supprimer_panier'),IsGranted("ROLE_USER")]
    public function remove($id,SessionInterface $session)
    {
        $panier = $session->get('panier',[]);
        if(!empty($panier[$id])){
            unset($panier[$id]);
        }   
        $session->set('panier',$panier);
        
        return $this->redirectToRoute('panier');
    }

}
