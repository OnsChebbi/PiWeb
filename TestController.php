<?php

namespace App\Controller;

use App\Entity\Livreur;
use App\Entity\Map;
use App\Form\LivreurType;
use App\Form\MapType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    /**
     * @Route("/test", name="test")
     */
    public function index(Request $request): Response
    { $map= new Map();
        $form= $this->createForm(MapType::class,$map);

        $form->handleRequest($request);


        return $this->render('map.html.twig', [
            'MapForm'=>$form->createView(), 'map'=>$map
        ]);
    }
    /**
     * @Route("/t", name="t")
     */
    public function f1(): Response
    {
        return $this->render('AjouterLivraisonFront.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }
}
