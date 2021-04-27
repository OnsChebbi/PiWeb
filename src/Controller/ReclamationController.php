<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Entity\Category;
use App\Form\CategoryType;
use App\Form\ReclamationType;



use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ReclamationRepository;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Dompdf\Dompdf;
use Dompdf\Options;





class ReclamationController extends AbstractController
{
    /**
     * @Route("/reclamation", name="list_reclamation")
     */
    public function home()
    {

        $reclamation= $this->getDoctrine()->getRepository(Reclamation::class)->findAll();
        return  $this->render('admin/index.html.twig',['reclamation' => $reclamation]);
    }

    /**
     * @Route("/Supp/{id}", name="d")
     */
    public function delete($id, ReclamationRepository $rep)
    {

        $rec = $rep->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($rec);
        $em->flush();
        return $this->redirectToRoute('list_reclamation');

    }


    /**
     * @Route("/reclamation/ajout", name="new_rec")
     * Method({"GET", "POST"})
     */
    public function new(Request $request)
    {
        $reclamation = new Reclamation();
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $reclamation = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reclamation);
            $entityManager->flush();


            $this->addFlash('info','ajouté avec succès');

            return $this->redirectToRoute('list_reclamation');
        }
        return $this->render('admin/new.html.twig', ['form' => $form->createView()]);
    }


    /**
     * @Route("/edit/{id}", name="edit_rec")
     * Method({"GET", "POST"})
     */
    public function edit(Request $request, $id)
    {
        $reclamation = new Reclamation();
        $reclamation = $this->getDoctrine()->getRepository(Reclamation::class)->find($id);

        $form = $this->createForm(ReclamationType::class, $reclamation);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('list_reclamation');
        }

        return $this->render('admin/update.html.twig', ['form' => $form->createView()]);
    }


    /**
     * @Route("/category/newCat", name="new_category")
     * Method({"GET", "POST"})
     */
    public function newCategory(Request $request) {
        $category = new Category();

        $form = $this->createForm(CategoryType::class,$category);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();
        }
        return $this->render('admin/newCategory.html.twig',['form' => $form->createView()]);
    }

    /**
     * @param ReclamationRepository $repository
     * @param Request $request
     * @return Response
     * @Route("/recherche",name="rechercheevent")
     */

    function RechercheEvent(ReclamationRepository $repository , Request $request)
    {
        $nom=$request->get('recherche');
        $reclamation=$repository->RechercheNom($nom);
        return $this->render('admin/index.html.twig' , ['reclamation'=>$reclamation]);
    }

    /**
     * @param ReclamationRepository $repository
     * @return Response
     * @Route("/tri",name="trierec")
     */
    function OrderByMailsQL (ReclamationRepository   $repository) {
        $r=$repository->OrderByMailQB();
        return $this->render('admin/index.html.twig', ['reclamation'=>$r]);
    }

}
