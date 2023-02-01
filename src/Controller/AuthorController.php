<?php

namespace App\Controller;


use App\Entity\Author;
use App\Form\AuthorType;
use App\Form\SearchAuthorType;
use App\DTO\SearchAuthorCriteria;
use App\Repository\AuthorRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AuthorController extends AbstractController
{
    #[Route("/author", name: "app_author")]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }

     /**
     * @Route("/admin/auteurs/nouveau", name="app_author_create")
     */
    public function create(Request $request , AuthorRepository $repository): Response
    {
        // création de l'objet PHP -> inutile d'ajouter l'objet Author en paramètre 
        //car on ne fait pas de préremplissage
        // $author= new Author();

        //création du formulaire
        $form= $this->createForm(AuthorType::class);
        //remplissage du formulaire et de l'objet php avec la requete utilisateur
        $form->handleRequest($request); 

        //tester si le formulaire est envoyé et les données sont valides
        if($form->isSubmitted() && $form->isValid()){

            //recuperation des données du formulaire dans l'objet author
            $validAuthor = $form->getData();

            //enregistrer les données de l'auteur dans la DB grâce au Repository
            $repository->save($validAuthor, true);

            // redirection utilisateur vers la liste des auteurs
            return $this->redirectToRoute('app_author_list');
        }

        // récuperation de la view du formulaire
        $formView= $form->createView();

        //affichage dans le template
        return $this->render('author/createForm.html.twig' , [
            'form' => $formView,
        ]);


        /* ou bien ecrire les 2 instructions dans une seule:
         return $this->render('pizza/createForm.html.twig' , [
            'form' => $form->createView(),
        ]); 
        */
    }


     /**
     * @Route("/admin/auteurs", name="app_author_list")
     */
    public function list(AuthorRepository $repository, Request $request): Response
    {

        // 1.Création des critères de recherche
        $criteria = new SearchAuthorCriteria();

        // 2.Création du formulaire
        $form = $this->createForm(SearchAuthorType::class, $criteria);

        // 3.Remplir le formulaire avec les critères de recherche de l'utilisateur
        $form->handleRequest($request);

        //recuperer les auteurs depuis la DB
        $authors= $repository->findByCriteria($criteria); //retourne les résultats de la recherche

        return $this->render('author/list.html.twig', [
            'authors' => $authors,
            'form' => $form->createView(),
        ] );

    }


     /**
     * @Route("admin/auteurs/maj/{id}", name="app_author_updateForm")
     */

    public function updateForm (int $id, AuthorRepository $repository, Request $request):Response
    {
        //recuperer les données de l'auteur du id
        $author= $repository->find($id);
//--------------Même partie que la création-------------------
        //création du formulaire
        $form= $this->createForm(AuthorType::class , $author );
        
        //remplissage du formulaire et de l'objet php avec la requete utilisateur
        $form->handleRequest($request); 

        //tester si le formulaire est envoyé avec données valides
        if($form->isSubmitted() && $form->isValid()){

            //recuperation de l'objet validé et remplie par le formulaire
            $validAuthor = $form->getData();

            //enregistrer les données dans la DB
            $repository->save($validAuthor, true);

            //redirection vers la liste des auteurs
            return $this->redirectToRoute('app_author_list');
        }

        //récuperation de la view du formulaire
        $formView= $form->createView();

        //affichage dans le template
        return $this->render('author/updateForm.html.twig' , [
            'form' => $formView,
            'author' => $author,
        ]);
    }


     /**
     * @Route("/admin/auteurs/{id}/supprimer", name="app_author_remove")
     */
    public function remove(int $id, Request $request , AuthorRepository $repository): Response
    {
        //recuperer l'auteur depuis son id
        $author = $repository->find($id);

        //suprimer l'auteur de la DB
        $repository->remove($author, true);

        //redirection vers la liste des auteurs
        return $this->redirectToRoute('app_author_list');
    }
}
