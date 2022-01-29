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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CalendrierController extends AbstractController
{   

    //   Controlleur du de la fonction affiche responsable de l'affichage des fruits & légumes

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

    //  fonction creer responsable de creer des nouveaux produits

    #[Route('/calendrier/create', name: 'create'), IsGranted("ROLE_ADMIN")]
    public function creer(Request $request,EntityManagerInterface $entityManager ): Response
    {
        $produit = new FruitsLegumes();

        $form = $this->createForm(FruitsLegumesType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($produit);
            $entityManager->flush();

            return $this->redirectToRoute('calendrier', []);
        }
        
        return $this->render('calendrier/add.html.twig', [
            'formulaire' => $form->createView()
            ]);
    }

    //  fonction delete responsable de supprimer un tel {/id} produit

    #[Route('/calendrier/{id}/delete', name: 'delete'), IsGranted("ROLE_ADMIN")]
    public function delete(FruitsLegumes $fruits_legumes, EntityManagerInterface $em) {
        $em->remove($fruits_legumes);
        $em->flush();

        return $this->redirectToRoute('calendrier');
    }

    //  fonction show responsable de l'ajout d'un tel produit { /id } ainsi que définir la quantité
    //   de ce dernier pour l'ajouter au panier 

    #[Route('calendrier/{id}', name: 'calendrier_show', methods: ['GET']),IsGranted("ROLE_USER")]
    public function show(FruitsLegumes $fruit_legume,$id,SessionInterface $session): Response
    {
        $passquantity = 0 ; 
        $panier = $session->get('panier',[]);
        if(isset($_GET['submit'])){

            if(!empty($_GET['pass-quantity'])) {
                $quantite=($_GET['pass-quantity']);
            }
            $panier[$id]=intval($quantite);
            $session->set('panier',$panier);
            return $this->redirectToRoute('panier', [], Response::HTTP_SEE_OTHER);
        }
        

        return $this->render('calendrier/show.html.twig', [
            'fruit_legume' => $fruit_legume,
            'passquantity' => intval($passquantity),
        ]);
    }
    
    //  fonction edit responsable de mettre à jour les données d'un tel produit {/id}

    #[Route('/calendrier/{id}/update', name: 'update'), IsGranted("ROLE_ADMIN")]
    public function edit(int $id, Request $request, FruitsLegumes $fruits_legumes, FruitsLegumesRepository $fruitsLegumesRepository, EntityManagerInterface $entityManager) {
        
        $form = $this->createForm(FruitsLegumesType::class, $fruits_legumes);
        $form->handleRequest($request);
 
        if ($form->isSubmitted() && $form->isValid()) {
          
            $newImg = $form['imageFile']->getData();
            
            if (!empty($newImg)) {
                
                $oldImg = $fruitsLegumesRepository->find($id)->getImage();

                $oldImgPath = $this->getParameter('app.path.product_images') . '/' . $oldImg;
                
                
                if (file_exists($oldImgPath)) {
                    unlink($oldImgPath);
                }
            } 

            $entityManager->flush();

            return $this->redirectToRoute('calendrier', []);
        }

        return $this->renderForm('calendrier/edit.html.twig', [
            
            'formulaire' => $form,
        ]);
    }


}