<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function isConnect(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        return $this->render('home/index.html.twig', [
            'users' => $users,
        ]);
        
    }
}
