<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Livraison;
use App\Entity\Livreur;
use App\Form\LivraisonAjoutType;
use App\Form\LivraisonType;
use App\Form\LivreurType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LivraisonController extends AbstractController
{
    /**
     * @Route("/afflivraison", name="showlivraison")
     */

    public function AfficherLivraison()
    {
        $livraison= $this->getDoctrine()->getRepository(Livraison::class)->findAll();
        return $this->render("livraison/AfficherLivraisonBack.html.twig",array('listlivraison'=>$livraison));

    }
    /**
     * @Route("/suppLivraison/{id}", name="deletelivraison" )
     */

    public function SupprimerLivraison($id)
    {
        $em=$this->getDoctrine()->getManager();
        $doc=$em->getRepository(Livraison::class)->find($id);
        $em->remove($doc);
        $em->flush();
        return $this->redirectToRoute('showlivraison');

    }

    /**
     * @Route("/ModifierLivraison/{id}", name="updatelivraison" )
     */

    public function ModifierLivraison(Request $request,$id)
    {
        $em=$this->getDoctrine()->getManager();
        $livraison = $em->getRepository(Livraison::class)->find($id);
        $form = $this->createForm(LivraisonType::class, $livraison);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $em->flush();
            return $this->redirectToRoute('showlivraison');
        }
        return $this->render('livraison/ModifierLivraison.html.twig', [
            'LivraisonForm' => $form->createView(),
        ]);

    }

    /**
     * @Route("/AjouterLivraison/{id}", name="addlivraison" )
     */

    public function AjouterLiv (Request $request,$id)
    {
        $commande= new Commande();
        $em=$this->getDoctrine()->getManager();
        $commande = $em->getRepository(Commande::class)->find($id);

        $livraison= new livraison();
        $livraison->SetCommande($commande);
        $form= $this->createForm(LivraisonAjoutType::class,$livraison);
        $em=$this->getDoctrine()->getManager();

        $form->handleRequest($request);

        if($form->isSubmitted()&& $form->isValid())
        {
            $em->persist($livraison);
            $em->flush();
            return $this->redirectToRoute("showlivraison");
        }
        return    $this->render("livraison/ModifierLivraison.html.twig",[

            'LivraisonForm'=>$form->createView()
        ]);
    }

}
