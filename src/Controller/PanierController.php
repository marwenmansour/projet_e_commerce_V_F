<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Entity\Panier;
use App\Form\PanierType;
use App\Entity\Utilisateur;
use Stripe\Checkout\Session;
use App\Entity\FruitsLegumes;
use App\Form\UtilisateurType;
use App\Form\FruitsLegumesType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\FruitsLegumesRepository;
use App\Repository\PanierRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PanierController extends AbstractController
{
    // fonction index responsable de collecter les produits ajoutés au panier ainsi que leurs produits totale

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
    //    prix totale//
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

    //  fonction remove responsable de supprimer un tel produits ajouté au panier du panier

    #[Route('/panier/remove/{id}', name: 'supprimer_panier'),IsGranted("ROLE_USER")]
    public function remove($id,SessionInterface $session)
    {
        $panier = $session->get('panier',[]);
        if(!empty($panier[$id])){
            // effacé la commande
            unset($panier[$id]);
        }   
        $session->set('panier',$panier);
        
        return $this->redirectToRoute('panier');
    }

    // fonction checkout responsable de : 
            //  1) collecter les données des produits ajoutés au panier    
            //  2) exploiter le api du STRIPE en y insérant les données collectées (1) pour réaliser le payment

    #[Route('/checkout', name: 'checkout')]
    public function checkout(SessionInterface $session_panier,FruitsLegumesRepository $fruit_legume_repository): Response
    {
        $panier = $session_panier->get('panier',[]);
       
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

    //  la fonction successUrl reponsable de :
        //  1)  rendre un page statique aprés la réalisation du payement de la part du client
        //  2)  envoie d'un email au client aprés son payement pour confirmer son payement tout en lui 
        //      indiquant le montant qu'il a payé dans l e-mail

    #[Route('/success-url', name: 'success_url')]
    public function successUrl(MailerInterface $mailer, SessionInterface $session,FruitsLegumesRepository $fruit_legume_repository): Response
    {
        $totalItems = 0;
        $panier = $session->get('panier',[]);
        $panier_with_data = [];
        
        foreach($panier as $id => $quantite){
            $panier_with_data[]=[
                'produit' => $fruit_legume_repository->find($id),
                'quantite'=> $quantite,
                
            ];
        }
       
        foreach($panier_with_data as $item) 
        {
        $totalItem = $item['produit']->getPrix() * $item['quantite'];
        $totalItems += $totalItem ;
        }
        $user = $this->getUser();
        $email = $user->getEmail();
        $mailer->send(
            (new TemplatedEmail)
                ->from('fruitslegumes2022@gmail.com')
                ->to($email)
                ->subject('commande')
                
                // path of the Twig template to render
                ->htmlTemplate('panier/email.html.twig')

                // pass variables (name => value) to the template
                ->context([
                    'user_nom' => $user->getPseudo(),
                    'prix' => $totalItems,
                ])
        );
        return $this->render('panier/success.html.twig', []);
    }


    #[Route('/cancel-url', name: 'cancel_url')]
    public function cancelUrl(): Response
    {
        return $this->render('panier/cancel.html.twig', []);
    }

    // fonction new responsable de valider les coordonnées de l'utilisateur

    #[Route('/commande', name: 'commande_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SessionInterface $session,EntityManagerInterface $entityManager,FruitsLegumesRepository $fruit_legume_repository): Response
    {
        $commande = new Panier();
        $form = $this->createForm(PanierType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() ) {
           
            $totalItems = 0;
            $panier = $session->get('panier',[]);
            $panier_with_data = [];
            
            foreach($panier as $id => $quantite){
                $panier_with_data[]=[
                    'produit' => $fruit_legume_repository->find($id),
                    'quantite'=> $quantite,
                    
                ];
            }
           
            foreach($panier_with_data as $item) 
            {
            $totalItem = $item['produit']->getPrix() * $item['quantite'];
            $totalItems += $totalItem ;
            }
            $user = $this->getUser();
            $commande->setPrixTotale($totalItems);
            $commande->setUser($user);
            $entityManager->persist($commande);
            $entityManager->flush();
            
           
           

            return $this->redirectToRoute('checkout', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('panier/valider.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

    // fonction commande responsable d' affiher tous les commandes au admin  

    #[Route('/commandes', name: 'commandes', methods: ['GET']), IsGranted("ROLE_ADMIN")]
    public function commandes(PanierRepository $panierRepository): Response
    {
        return $this->render('panier/commandes.html.twig', [
            'commandes' => $panierRepository->findAll(),
        ]);
    }

    // fonction commande_delete responsable de supprimer une tel commande

    #[Route('commandes/{id}', name: 'commande_delete', methods: ['GET', 'POST']), IsGranted("ROLE_ADMIN")]
    public function delete(Request $request,Panier $panier, EntityManagerInterface $entityManager): Response
    {
        
            $entityManager->remove($panier);
            $entityManager->flush();
      

        return $this->redirectToRoute('commandes', [], Response::HTTP_SEE_OTHER);
    }

}
   


    


