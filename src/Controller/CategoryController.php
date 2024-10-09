<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CategoryController extends AbstractController
{
    // #[Route('/category', name: 'app_category')]
    // public function index(): Response
    // {
    //     return $this->render('category/index.html.twig', [
    //         'controller_name' => 'CategoryController',
    //     ]);
    // }

    #[Route('/category/{id}', name: 'app_category')]
    public function categoryById(
        CategoryRepository $categoryRepository, 
        int $id
        ):response
        {
            $categorie = $categoryRepository->find($id);
            $objet = $categorie->getObjets();
            dump($categorie);
            dump($objet);
            return $this->render('category/index.html.twig', [
                'categorie' => $categorie,
                
            ]);
        }
}
