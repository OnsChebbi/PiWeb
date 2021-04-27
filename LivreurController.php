<?php

namespace App\Controller;

use App\Entity\Livreur;
use App\Form\LivreurType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LivreurController extends AbstractController
{
    /**
     * @Route("/afflivreur", name="showlivreur")
     */

    public function AfficherLivreur()
    {
        $livreur= $this->getDoctrine()->getRepository(Livreur::class)->findAll();
        return $this->render("livreur/AfficherLivreurBack.html.twig",array('listlivreur'=>$livreur));

    }

    /**
     * @Route("/ajoutLivreur", name="addlivreur")
     */
    public function AjouterLivreur(Request $requet)
    {
        $livreur= new livreur();
        $form= $this->createForm(LivreurType::class,$livreur);
        $em=$this->getDoctrine()->getManager();

        $form->handleRequest($requet);

        if($form->isSubmitted()&& $form->isValid())
        {
            $em->persist($livreur);
            $em->flush();
            return $this->redirectToRoute("showlivreur");
        }
        return    $this->render("livreur/AjouterLivreur.html.twig",[

            'LivreurForm'=>$form->createView()
        ]);
    }

    /**
     * @Route("/suppLivreur/{id}", name="deletelivreur" )
     */

    public function SupprimerLivreur($id)
    {
        $em=$this->getDoctrine()->getManager();
        $doc=$em->getRepository(Livreur::class)->find($id);
        $em->remove($doc);
        $em->flush();
        return $this->redirectToRoute('showlivreur');

    }
    /**
     * @Route("/ModifierLivreur/{id}", name="updatelivreur" )
     */

    public function ModifierLivreur(Request $request,$id)
    {
        $em=$this->getDoctrine()->getManager();
        $livreur = $em->getRepository(Livreur::class)->find($id);
        $form = $this->createForm(LivreurType::class, $livreur);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $em->flush();
            return $this->redirectToRoute('showlivreur');
        }
        return $this->render('livreur/AjouterLivreur.html.twig', [
            'LivreurForm' => $form->createView(),
        ]);

    }


    /**
     * @Route("/trilivreurN", name="showlivreurTN")
     */

    public function TriLivreurN()
    {
        $livreur= $this->getDoctrine()->getRepository(Livreur::class)->listOrderByName();
        return $this->render("livreur/AfficherLivreurBack.html.twig",array('listlivreur'=>$livreur));

    }



    /**
     * @Route("/TrilivreurC", name="showlivreurTC")
     */

    public function TriLivreurc()
    {
        $livreur= $this->getDoctrine()->getRepository(Livreur::class)->listOrderByCin();
        return $this->render("livreur/AfficherLivreurBack.html.twig",array('listlivreur'=>$livreur));

    }
}
