<?php

namespace App\Controller;


use App\Entity\Objet;
use App\Form\ObjetType;
use App\Repository\BorrowRepository;
use App\Repository\ObjetRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Masterminds\HTML5\Entities;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ObjetController extends AbstractController
{
    #[Route('/objet', name: 'app_objet')]
    public function index(ObjetRepository $objetRepository): Response
    {
        $objets = $objetRepository->findAll();
        
        return $this->render('objet/index.html.twig', [
            'objets' => $objets,
        ]);
    }

    #[Route('/objet/add', name: 'app_objet_add')]
    public function add(
        Request $request, 
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger,
        #[Autowire('%kernel.project_dir%/public/uploads/')] string $uploadDirectory,
        ):Response
    {
        $newObject = new Objet();
        $user = $this->getUser('id');
        $form = $this->createForm(ObjetType::class, $newObject);
        $form->handleRequest($request);
        if($form->isSubmitted()&& $form->isValid()) 
        {
            $thumbnail = $form->get('thumbnail')->getData();
                if($thumbnail) {
                    //Récuperation du nom d'origine de l'image
                    $originalFileName = pathinfo($thumbnail->getClientOriginalName(),PATHINFO_FILENAME);
                    // slugger pour nettoyer des espaces et caractères spéciaux
                    $safeFileName = $slugger->slug($originalFileName);
                    //attribution d'un id unique et guessExtension ajoutte le jpg ou png ...
                    $newFileName = $safeFileName.'-'.uniqid().'.'.$thumbnail->guessExtension();
                    // Move the file to the directory where brochures are stored
                    try {
                        $thumbnail->move($uploadDirectory, $newFileName);
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                    }
                    // updates the 'uploadFilename' property to store the PDF file name
                    // instead of its contents
                    $newObject->setThumbnail($newFileName);
                }
            // $isDisponible = $form->$user.objets->getData();
            // dump($isDisponible);
            // $isDisponible->setDisponible('True');
            
            $newObject = $form->getData();
            $newObject->setOwner($user);
            $entityManager->persist($newObject);
            $entityManager->flush();
            
            return $this->redirectToRoute('app_objet');
        }
        return $this->render('objet/add.html.twig', [
            'formulaire'=>$form
        ]);
    }

    #[Route('/objet/{id}', name: 'app_objet_id')]
    public function showObjetById(
        ObjetRepository $objetRepository, 
        int $id,
        EntityManagerInterface $entityManager,
        BorrowRepository $borrowRepository   
        ):response
        {
            $objet = $objetRepository->find($id);
            //récupértion de la endDate des emprunts s'il y en a
            $borrows = $objet->getBorrows();
                if(count($borrows) > 0) {
                    foreach($borrows as $borrow) {
                        $endDate = $borrow->getEndDate();
                        $now = new DateTime();

                        if($endDate <= $now && !$objet->isDisponible()){
                            $objet->setDisponible(true);
                            $entityManager->persist($objet);
                            $entityManager->flush();
                        }
                    }
                }
            dump($objet);
            return $this->render('objet/id.html.twig', [
                'objet' => $objet
            ]);
        }
}
