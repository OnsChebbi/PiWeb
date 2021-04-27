<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{  /**
 * @Route("/panier1", name="panier1")
 */
    public function index( ): Response
    {

        return($this->render('front.html.twig'));
    }

    /**
     * @Route("/panier", name="panier")
     */
    public function afficher(SessionInterface $session, ProduitRepository $produitRepository ): Response
    {
        $panier= $session->get('panier',[]);
        //dd($session->get('panier'));
        $panierWithData= [];

        foreach($panier as $id => $quantity) {
            $panierWithData [] =[
                'product' => $produitRepository->find($id),
                'quantity' => $quantity

            ];

        }
        $total =0;
        foreach ($panierWithData as $item)
        { $totalItem=$item['product']->getPrix() *$item['quantity'];
        $total += $totalItem;}
        $session->set('total', $total);

        return($this->render('panier/AfficherPanier.html.twig',['items' => $panierWithData,
            'total' => $total]));
    }

    /**
     * @Route("/panier/add/{id}", name="cart_add")
     */
    public function AjouterPanier($id, SessionInterface $session): Response
    {

       $panier= $session->get('panier',[]);
       if (!empty($panier[$id]))
       {$panier[$id]++;}
       else
       {$panier[$id]=1;}

       $session->set('panier', $panier);
        return $this->redirectToRoute('panier1');
       //dd($session->get('panier'));
    }

    /**
     * @Route("/panier/remove/{id}", name="cart_remove")
     */
    public function SupprimerPanier($id, SessionInterface $session): Response
    {

        $panier= $session->get('panier',[]);
        if (!empty($panier[$id]))
        {unset($panier[$id]);
        }


        $session->set('panier', $panier);
      return $this->redirectToRoute("panier");
    }
}
