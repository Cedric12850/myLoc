<?php

namespace App\Controller;

use App\Repository\BorrowRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AccountController extends AbstractController
{
    #[Route('/account', name: 'app_account')]
    public function index(): Response
    {
        // on va chercher dans getUser tous les emprunts pour les afficher (résultats sous forme de tableau)
        $user = $this->getUser();
        $userObjet = $this->getUser()->getObjets();
        $borrow = $this->getUser()->getBorrows();
       
        return $this->render('account/index.html.twig', [
            'borrow' => $borrow,
            'objet' => $userObjet,
            'user' => $user
        ]);
    }
}
