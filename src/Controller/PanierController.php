<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Entity\Panier;
use Stripe\Checkout\Session;
use App\Entity\FruitsLegumes;
use App\Form\FruitsLegumesType;
use App\Repository\FruitsLegumesRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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

    
    // #[Route('/payment', name: 'payment')]
    // public function payement(): Response
    // {
    //     return $this->render('panier/payer.html.twig', [
    //         'controller_name' => 'PanierController',
    //     ]);
    // }

    #[Route('/checkout', name: 'checkout')]
    public function checkout(SessionInterface $session_panier,FruitsLegumesRepository $fruit_legume_repository): Response
    {
        $panier = $session_panier->get('panier',[]);
        $panier_with_data = [];
       foreach($panier as $id => $quantite){
           $panier_with_data[]=[
               'produit' => $fruit_legume_repository->find($id),
               'quantite'=> $quantite,
           ];
       }
       $totalItems = 0;
       $nomProduits =  "";
       foreach($panier_with_data as $item) {
           $totalItem = $item['produit']->getPrix() * $item['quantite'];
           $nomProduit = $item['produit']->getNom();
           $totalItems += $totalItem ;
           if (count($panier_with_data) == 1 ){
               $nomProduits = $nomProduit;
           }else{
                $nomProduits .= $nomProduit . " & ";
           }
       }
       if (count($panier_with_data) > 1 )
        {
            $nomProduits = rtrim($nomProduits,"& ");
        }
       
        \Stripe\Stripe::setApiKey('sk_test_51KArhPDMswz9lF9pSntfioAo9HcAuLymqDhMh9x7VK08TwJdl8pTQoWhJefQKgqzYPbPKfUr9e0LRPz8rkw1Os4v00GO7mvzzR');
              
        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items'           => [
                [
                    'price_data' => [
                        'currency'     => 'eur',
                        'product_data' => [
                            'name' => $nomProduits,
                        ],
                        'unit_amount'  =>  $totalItems * 100,
                    ],
                    'quantity'   => 1,
                ]
            ],
            'mode'                 => 'payment',
            'success_url'          => $this->generateUrl('success_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url'           => $this->generateUrl('cancel_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);
        
        
        return $this->redirect($session->url, 303);
    }

    #[Route('/success-url', name: 'success_url')]
    public function successUrl(): Response
    {
        return $this->render('panier/success.html.twig', []);
    }


    #[Route('/cancel-url', name: 'cancel_url')]
    public function cancelUrl(): Response
    {
        return $this->render('panier/cancel.html.twig', []);
    }
}


    


