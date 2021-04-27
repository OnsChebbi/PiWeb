<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Form\FrontReclamationType;
use App\Form\CategoryType;
use App\Form\ReclamationType;



use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\FrontReclamationRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class FrontReclamationController extends AbstractController
{

    /**
     * @Route("/frontrec", name="new_frontrec")
     * Method({"GET", "POST"})
     */
    public function newFront(Request $request)
    {
        $reclamation = new Reclamation();
        $form = $this->createForm(FrontReclamationType::class, $reclamation);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();


            $entityManager = $this->getDoctrine()->getManager();

            $reclamation->setEtat('En Attente');
            $entityManager->persist($reclamation);
            $entityManager->flush();

            $this->addFlash('info','ajouté avec succès');



        }
        return $this->render('home/FrontReclamation.html.twig', ['form' => $form->createView()]);
    }





}
