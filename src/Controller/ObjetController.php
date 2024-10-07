<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ObjetController extends AbstractController
{
    #[Route('/objet', name: 'app_objet')]
    public function index(): Response
    {
        return $this->render('objet/index.html.twig', [
            'controller_name' => 'ObjetController',
        ]);
    }
}
