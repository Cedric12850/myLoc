<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ObjetRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        ObjetRepository $objetRepository,
        CategoryRepository $categoryRepository,
        ): Response
    {
        $user = $this->getUser();
        $objets = $objetRepository->findAll();
        $categories = $categoryRepository->findAll();
        return $this->render('home/index.html.twig', [
            'objets' => $objets,
            'user' => $user,
            'categories' =>$categories
        ]);
    }

}
