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
use Symfony\Bridge\Twig\Mime\TemplatedEmail;




class ContactController extends AbstractController
{
    
    # fonction index responsable de créér un formulaire de contact ainsi que l'envoi par mail à la boite 
    # gmail de l'administrateur de tel formulaire sousmis par un tel utilisateur
 
    #[Route('/contact', name: 'contact')]
    public function index(Request $request, MailerInterface $mailer): Response {


        $formulaire = $this->createForm(ContactType::class);
        $formulaire->handleRequest($request);
        $notif = '';
        if ($formulaire->isSubmitted() && $formulaire->isValid()) {
            $contact = $formulaire->getData();
           
            $mailer->send(
                (new TemplatedEmail)
                    ->from($contact['email'])
                    ->to('fruitslegumes2022@gmail.com')
                    ->subject($contact['sujet'])
                    
                    // path of the Twig template to render
                    ->htmlTemplate('contact/email.html.twig')

                    // pass variables (name => value) to the template
                    ->context([
                        'user_mail' => $contact['email'],
                        'tel' => $contact['telephone'],
                        'message' => $contact['message'],
                    ])
            );
            $notif = 'Votre message a bien été envoyé.';
            
        }
        

        return $this->render('contact/index.html.twig', [
            'formulaire' => $formulaire->createView(),
            'notif' => $notif,
        ]);
        
    }
}

