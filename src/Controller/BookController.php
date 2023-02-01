<?php

namespace App\Controller;

use App\Form\BookType;
use App\Repository\BookRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_ADMIN")
 * 
 */

class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

     /**
     * @Route("/admin/livre/nouveau", name="app_book_create")
     */
    public function create(Request $request, BookRepository $repository): Response
    {
        // je n'ai pas besoin d'ajouter un objet Book en paramétre 
        //car je ne fait pas de préremplissage
        //$book= new Book();

        //création du formulaire
        $form= $this->createForm(BookType::class);
        
        //remplissage du formulaire et de l'objet php avec la requete
        $form->handleRequest($request);

        //tester si le formulaire est envoyé avec des données valides
        if ($form->isSubmitted() && $form->isValid()) {
            //recuperation des données du formulaire dans l'objet book
            $validBook = $form->getData();
            //enregistrer les données de l'auteur dans la DB
            $repository->save($validBook, true);

            // redirection vers la liste des livres
            return $this->redirectToRoute('app_book_list');
        }

        // récuperation de la view du formulaire
        $formView= $form->createView();

        //affichage dans le template
        return $this->render('book/create.html.twig', [
            'form' => $formView,
        ]);
    }

    /**
     * @Route("/admin/livre", name="app_book_list")
     */
    public function list(BookRepository $repository): Response
    {

        //recuperer les livres depuis la DB
        $books= $repository->findAll(); //retourne la liste des livres

        return $this->render('book/list.html.twig', [
            'books' => $books,
        ] );
    }

    /**
     * @Route("/admin/livre/maj/{id}", name="app_book_update")
     */

     public function update (int $id, BookRepository $repository, Request $request):Response
     {
         //recuperer les données du livre de l'id
         $book= $repository->find($id);
 //--------------Même partie que la création-------------------
         //création du formulaire
         $form= $this->createForm(BookType::class , $book );
         
         //remplissage du formulaire et de l'objet php avec la requete
         $form->handleRequest($request); 
 
         //tester si le formulaire est envoyé avec données valides
         if($form->isSubmitted() && $form->isValid()){
 
             //recuperation de l'objet validé et remplie par le formulaire
             $validBook = $form->getData();
 
             //enregistrer les données dans la DB
             $repository->save($validBook, true);
 
             //redirection vers la liste des livres
             return $this->redirectToRoute('app_book_list');
         }
 
         //récuperation de la view du formulaire
         $formView= $form->createView();
 
         //affichage dans le template
         return $this->render('book/update.html.twig' , [
             'form' => $formView,
             'book' => $book,
         ]);
     }

    /**
     * @Route("/admin/livre/{id}/supprimer", name="app_book_remove")
     */
    public function remove(int $id, Request $request , BookRepository $repository): Response
    {
        //recuperer le livre depuis son id
        $book = $repository->find($id);

        //suprimer le livre de la DB
        $repository->remove($book, true);

        //redirection vers la liste des livres
        return $this->redirectToRoute('app_book_list');
    }

}
