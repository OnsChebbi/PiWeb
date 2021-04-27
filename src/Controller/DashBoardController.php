<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\AnnoncesRepository;
use App\Repository\CategoriesRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;

class DashBoardController extends AbstractController
{
    /**
     * @IsGranted("ROLE_ADMIN", message="No access! Get out!")
     * @Route("/dash/board", name="dash_board")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'DashBoardController',
        ]);
    }


}
