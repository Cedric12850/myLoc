<?php

namespace App\Controller;

use App\Entity\Borrow;
use App\Form\BorrowType;
use App\Repository\ObjetRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BorrowController extends AbstractController
{
    #[Route('/borrow', name: 'app_borrow')]
    public function index(): Response
    {
        return $this->render('borrow/index.html.twig', [
            'controller_name' => 'BorrowController',
        ]);
    }

    #[Route('/borrow', name:'app_borrow')]
    public function add(
        Request $request,
        EntityManagerInterface $entityManager
    ):Response
    {
        $newBorrow = new Borrow;
        $form = $this->createForm(BorrowType::class, $newBorrow);
        $form->handleRequest($request);
        $newBorrow = $form->getdata();
        $entityManager->persist($newBorrow);
        $entityManager->flush();
        return $this->render('borrow/index.html.twig', [
            'formulaire' =>$form
        ]);
    }

    #[Route('/borrow/{id}', name: 'app_borrow')]
    public function showObjetById(
        ObjetRepository $objetRepository, 
        int $id
        ):response
        {
            $objet = $objetRepository->find($id);
            dump($objet);
            return $this->render('borrow/index.html.twig', [
                'objet' => $objet
            ]);
        }


}
