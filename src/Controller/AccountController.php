<?php

namespace App\Controller;

use App\Entity\Objet;
use App\Form\ObjetType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

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

    #[route('/account/edit/{id}', name: 'app_account_edit')]
    public function edit(
        Request $request, 
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger,
        #[Autowire('%kernel.project_dir%/public/uploads/')] string $uploadDirectory,
        int $id
    ):Response
    {
        $objet =$entityManager->getRepository(Objet::class)->find($id);
        dump($objet);
        if(!$objet){
            throw $this->createNotFoundException(
                "L'objet n'est pas répertorié."
            );
        }
        $form = $this->createForm(ObjetType::class, $objet);
        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid())
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
                $objet->setThumbnail($newFileName);
            }

            $objet= $form->getData();
            $entityManager->flush();

            return $this->redirectToRoute('app_account');
        }

    return $this->render('account/edit.html.twig', [
        'objet' => $objet,
        'editform' => $form
    ]);
    }

    #[Route('account/delete/{id}', name: 'app_delete')]
    public function delete(
        EntityManagerInterface $entityManager,
        int $id
    ):Response
    {
        $objet =$entityManager->getRepository(Objet::class)->find($id);
        $entityManager->remove($objet);
        $entityManager->flush();
        //return $this->render('account/delete.html.twig');
        return $this->redirectToRoute('app_account');
    }
    
}

