<?php

namespace App\Controller;

use App\Repository\BorrowRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AccountController extends AbstractController
{
    #[Route('/account/', name: 'app_account')]
    public function index(
        BorrowRepository $borrowRepository,
        
        ): Response
    {
        $borrow = $borrowRepository->findAll();
        
        return $this->render('account/index.html.twig', [
            'borrow' => $borrow,
        ]);
    }
}
