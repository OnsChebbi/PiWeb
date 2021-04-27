<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(Request $Reqest , \Swift_Mailer $mailer): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($Reqest);

        if($form->isSubmitted() && $form->isValid())
        {
            $contact= $form->getData();  //ici on va enovyer le mail
            $message = (new \Swift_Message('Nouveau Conatact'))
            //on attribue l experditeur
            ->setFrom($contact['email'])
                ->setTo('toutou1998@gmail.com')
                ->setBody(
                    $this->renderView(
                        'Email/contact.html.twig', compact('contact')
                    ),
                    'text/html'
                );
//envoie
            $mailer->send($message);
            $this->addFlash('message ','Le message est envoyé!');
             return $this->redirectToRoute('showlivreur');
        }

        return $this->render('contact/EnvoyerEmail.html.twig', [
            'contactForm' => $form->createView(),
        ]);
    }
}
