<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProduitController extends AbstractController
{
    /**
     * @Route("/liste", name="showProduit")
     */

    public function listproduct(ProduitRepository $produitRepository)
    {
        $p=$produitRepository->getCustomInformations();

        $produit= $this->getDoctrine()->getRepository(Produit::class)->findAll();
        return $this->render("produit/listproduitB.html.twig",array('listproduit'=>$produit,'stat'=>$p));

    }

    /**
     * @Route("/listeTrie", name="trieproduit")
     */

    public function listTrierProduit(ProduitRepository $produitRepository)

    {
        $p=$produitRepository->getCustomInformations();
        $produit= $this->getDoctrine()->getRepository(Produit::class)->listOrderByName();
        return $this->render("produit/listproduitB.html.twig",array('listproduit'=>$produit,'stat'=>$p));

    }
    /**
     * @Route("/listeTrieId", name="trieproduitId")
     */

    public function TrierId(ProduitRepository $produitRepository)
    {
        $p=$produitRepository->getCustomInformations();
        $produit= $this->getDoctrine()->getRepository(Produit::class)->listOrderById();
        return $this->render("produit/listproduitB.html.twig",array('listproduit'=>$produit,'stat'=>$p));

    }

    /**
     * @Route("/rechercherProduit", name="searchProd")
     */

    public function searchPROD(ProduitRepository $repository , Request $request)

    {
        $p=$repository->getCustomInformations();
        $data=$request->get('search');
        $produit=$repository->rechercher($data);
        return $this->render("produit/listproduitB.html.twig",array('listproduit'=>$produit,'stat'=>$p));

    }

    /**
     * @Route("/addprod", name="newprod")
     */
    public function Ajouterproduit(Request $requet)
    {
        $produit= new produit();
        $form= $this->createForm(ProduitType::class,$produit);
        $em=$this->getDoctrine()->getManager();

        $form->handleRequest($requet);

        if($form->isSubmitted()&& $form->isValid())
        {
            $file = $form['image']->getData();
            if($file)
            {$fileName = md5(uniqid()).'.'.$file->guessExtension();
                $produit->setImage($fileName);


                $file->move(
                    $this->getParameter('EventImage_directory'),
                    $fileName
                );

            }else{
                $produit->setImage('default.jpg');
            }
            $em->persist($produit);
            $em->flush();
            return $this->redirectToRoute("showProduit");
        }
        return    $this->render("produit/add.html.twig",[

            'produitform'=>$form->createView()
        ]);
    }
    /**
     * @Route("/deleteproduit/{id}", name="deletePROD" )
     */

    public function SupprimerProduit($id)
    {
        $em=$this->getDoctrine()->getManager();
        $prod=$em->getRepository(Produit::class)->find($id);
        $em->remove($prod);
        $em->flush();
        return $this->redirectToRoute('showProduit');

    }

    /**
     * @Route("/updateProduit/{id}", name="updatePROD" )
     */

    public function ModifierProduit(Request $request,$id)
    {
        $em = $this->getDoctrine()->getManager();
        $produit = $em->getRepository(Produit::class)->find($id);
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $file = $form['image']->getData();
            if($file) {
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move(
                    $this->getParameter('EventImage_directory'),
                    $fileName
                );
                $produit->setPic($fileName);
            }
            $em->flush();
            return $this->redirectToRoute('showProduit');
        }
        return $this->render('produit/add.html.twig', [
            'produitform' => $form->createView(),
        ]);

    }

}
