<?php

namespace App\Controller;

use App\Form\ProfileType;
use App\Form\UserType;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{
    /**
     * @Route("/profil", name="profil")
     */
    public function index(): Response
    {
        return $this->render('profil/index.html.twig', [
            'controller_name' => 'ProfilController',
        ]);
    }
    /**
     * @Route("/EditProfile/{id}", name="Edit")
     */
    public function modifier(Request $request, string $id): Response
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('App\Entity\User')->find($id);

        if (!$user) {
            throw $this->createNotFoundException(
                'There are no users with the following id: ' . $id
            );
        }

        $form = $this->createForm(ProfileType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $brochureFile = $form->get('photo')->getData();

            $Filename = md5(uniqid()) . '.' . $brochureFile->guessExtension();

            $entityManager = $this->getDoctrine()->getManager();
            $brochureFile->move($this->getParameter('directory'), $Filename);
            $user->setPhoto($Filename);
            $user = $form->getData();
            $em->flush();
            return $this->redirect('/profil');
        }

        return $this->render(
            'profil/edit.html.twig',
            array('form' => $form->createView())
        );
    }
    /**
     * @Route("/download", name="download")
     */
    public function usersDataDownload()
    {
        // On définit les options du PDF
        $pdfOptions = new Options();
        // Police par défaut
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->setIsRemoteEnabled(true);

        // On instancie Dompdf
        $dompdf = new Dompdf($pdfOptions);
        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => FALSE,
                'verify_peer_name' => FALSE,
                'allow_self_signed' => TRUE
            ]
        ]);
        $dompdf->setHttpContext($context);

        // On génère le html
        $html = $this->renderView('profil/download.html.twig');

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // On génère un nom de fichier
        $fichier = 'user-data-'. $this->getUser()->getId() .'.pdf';

        // On envoie le PDF au navigateur
        $dompdf->stream($fichier, [
            'Attachment' => true
        ]);

        return new Response();
    }
}
