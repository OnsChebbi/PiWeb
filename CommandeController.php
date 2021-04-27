<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Livraison;
use App\Entity\Livreur;
use App\Form\CommandeType;
use App\Form\LivreurType;
use App\Repository\CommandeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;

class CommandeController extends AbstractController
{
    /**
     * @Route("/affcommande", name="showcommande")
     */

    public function AfficherCommande()
    {
        $commande= $this->getDoctrine()->getRepository(Commande::class)->findAll();
        return $this->render("commande/AfficherCommandeBack.html.twig",array('listcommande'=>$commande));

    }
    /**
     * @Route("/suppCommande/{id}", name="deletecommande" )
     */

    public function SupprimerCommande($id)
    {
        $em=$this->getDoctrine()->getManager();
        $doc=$em->getRepository(Commande::class)->find($id);
        $em->remove($doc);
        $em->flush();
        return $this->redirectToRoute('showcommande');

    }


    /**
     * @Route("/ajoutCommande", name="addcommande")
     */
    public function AjouterCommande(Request $requet, SessionInterface $session)
    { $session->start();

        $commande= new commande();

        $commande->setPrixtotal((float)$session->get('total'));
        $commande->setEtat("non livre");
        $em=$this->getDoctrine()->getManager();
        $em->persist($commande);
        $em->flush();
        $session->clear();
        return $this->render("front.html.twig");

    }

    /**
     * @Route("/c", name="c" )
     */

    public function index()
    {

        return $this->render("commande/AjouterCommande.html.twig");

    }

    /**
     * @Route("/recherchercommande", name="searchC")
     */

    public function searchCommande(CommandeRepository $repository , Request $request)
    {
        $data=$request->get('search');
        $commande=$repository->rechercher($data);
        return $this->render("commande/AfficherCommandeBack.html.twig",array('listcommande'=>$commande));

    }


    /**
     * @Route("/listimprimer",name="listimprimer")
     */
    public function list2()
    {
        //récupérer tous les articles de la table article de la BD
        // et les mettre dans le tableau $articles


        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        $inter = $this->getDoctrine()->getRepository(Commande::class)->findAll();
        // Retrieve the HTML generated in our twig file
        $html = $this->render('Commande/imprimer.html.twig', ['inter' => $inter]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => false
        ]);


    }
}
