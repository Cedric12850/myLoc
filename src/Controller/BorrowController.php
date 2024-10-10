<?php

namespace App\Controller;

use App\Entity\Borrow;
use App\Form\BorrowType;
use App\Repository\ObjetRepository;
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


    #[Route('/borrow/{id}', name: 'app_borrow')]
    public function borrowObjetById(
        Request $request,
        EntityManagerInterface $entityManager,
        ObjetRepository $objetRepository, 
        int $id
        ):response
        {
        $objet = $objetRepository->find($id);
        $borrower = $this->getUser();
        $ownerPoints = $objet->getOwner();
        //partie pour ajouter l'emprunt en bdd
        $newBorrow = new Borrow;
        $form = $this->createForm(BorrowType::class, $newBorrow);
        $form->handleRequest($request);
       
           
            
        if ($form->isSubmitted()&& $form->isValid()){
            $newBorrow = $form->getdata();
            //ajoutte les id du borrower et de l'objet pour enregistrer en bdd
            $newBorrow ->setBorrower($borrower);
            $newBorrow ->setObjet($objet);
            $entityManager->persist($newBorrow);
            
            //recupération des points de la catégorie
            $objetPoints = $objet->getcategory();
            $objetPoints = $objetPoints->getPoints();
            //récupération des points de l'utilisateurs
            $userPoints = $ownerPoints->getCumulPoints();
            //ajout des points de la catégorie au points de l'utilisateurs
            $cumulPoints = $objetPoints + $userPoints;
            //ajout des points dans la colonne user de la bdd
            $ownerPoints->setCumulPoints($cumulPoints);

            $entityManager->flush();

            return $this->redirectToRoute('app_account');
    }
        return $this->render('borrow/index.html.twig', [
            'formulaire' =>$form,
            'objet' => $objet
        ]);
        }

       
}
