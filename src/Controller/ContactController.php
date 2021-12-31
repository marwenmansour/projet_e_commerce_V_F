<?php

namespace App\Controller;


use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;



class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function index(Request $request, MailerInterface $mailer): Response {


        $formulaire = $this->createForm(ContactType::class);
        $formulaire->handleRequest($request);
        
        if ($formulaire->isSubmitted() && $formulaire->isValid()) {
            $contact = $formulaire->getData();
           
            $mailer->send(
                (new Email)
                    ->from($contact['email'])
                    ->to($contact['email'])
                    ->subject($contact['sujet'])
                    ->text($contact['message'])
            );
            $this->addFlash('success', 'Le message a bien été envoyé.');
            
        }
        

        return $this->render('contact/index.html.twig', [
            'formulaire' => $formulaire->createView(),
        ]);
    }
}

