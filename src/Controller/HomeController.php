<?php

namespace App\Controller;

use App\Repository\ObjetRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ObjetRepository $objetRepository): Response
    {
        $objets = $objetRepository->findAll();
        return $this->render('home/index.html.twig', [
            'objets' => $objets,
        ]);
    }

}
