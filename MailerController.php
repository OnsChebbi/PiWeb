<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mime\Email;


class MailerController extends AbstractController
{
    /**
     * @Route("/mailer", name="mailer")
     */
    public function index(): Response
    {
        return $this->render('mailer/index.html.twig', [
            'controller_name' => 'MailerController',
        ]);
    }


    /**
     * @Route("/EmailLivreur", name="Emaillivreur" )
     */


    public function Emaillivreur(MailerInterface $mailer)
    {
        $email= (new Email())
            ->from ('toutou1998@gmail.com')
            ->to ('chaabaaicha@gmail.com')
            ->subject ('LIVRAISON')
            ->html('<p> salut </p>');

        $mailer->send($email);
        return new Response('Email sent');


    }
}
