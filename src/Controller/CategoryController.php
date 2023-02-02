<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_ADMIN")
 * 
 */

class CategoryController extends AbstractController
{
    #[Route("/category", name: "app_category")]
    public function index(): Response
    {
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }

    /**
     * @Route("/admin/categorie/nouvelle", name="app_category_create")
     */
    public function create(Request $request , CategoryRepository $repository): Response
    {
        //création de l'objet PHP
        $category= new Category();

        //création du formulaire
        $form= $this->createForm(CategoryType::class, $category);
        
        //remplissage du formulaire et de l'objet php avec la requete
        $form->handleRequest($request);

        //tester si le formulaire est envoyé et les données sont valides
        if ($form->isSubmitted() && $form->isValid()) {
             //recuperation des données du formulaire dans l'objet category
             $validCategory = $form->getData();
             //enregistrer les données de la catégorie dans la DB
             $repository->save($validCategory, true);

             // redirection vers la liste des catégories
             return $this->redirectToRoute('app_category_list');
        }

    // récuperation de la view du formulaire
    $formView= $form->createView();

    //affichage dans le template
    return $this->render('category/create.html.twig', [
        'form' => $formView,
    ]);
    }


     /**
     * @Route("/admin/categorie", name="app_category_list")
     */
    public function list(CategoryRepository $repository): Response
    {

        //recuperer les catégories depuis la DB
        $categories= $repository->findAll(); //retourne la liste des catégories

        return $this->render('category/list.html.twig', [
            'categories' => $categories,
        ] );

    }



    /**
     * @Route("/admin/categorie/maj/{id}", name="app_category_update")
     */
    public function update(int $id, Request $request , CategoryRepository $repository): Response
    {
        //recuperer la categorie à partir de l'id
        $category = $repository->find($id);
    //--------------Même partie que la création-------------------
        //création du formulaire
        $form= $this->createForm(CategoryType::class , $category );

        //remplissage du formulaire et de l'objet php avec la requete
        $form->handleRequest($request); 

        //tester si le formulaire est envoyé avec données valides
        if($form->isSubmitted() && $form->isValid()){

            //recuperation de l'objet validé et remplie par le formulaire
            $validCategory = $form->getData();
            
            //enregistrer les données dans la DB
            $repository->save($validCategory, true);

            //redirection vers la liste des catégories
            return $this->redirectToRoute('app_category_list');
        }

        //récuperation de la view du formulaire
        $formView= $form->createView();

        //affichage dans le template
        return $this->render('category/update.html.twig' , [
            'form' => $formView,
            'category' => $category,
        ]);
    }


     /**
     * @Route("/admin/categorie/{id}/supprimer", name="app_category_remove")
     */
    public function remove(int $id, Request $request , CategoryRepository $repository): Response
    {
        //recuperer la categorie depuis son id
        $category = $repository->find($id);

        //suprimer la categorie de la DB
        $repository->remove($category, true);

        //redirection vers la liste des categories
        return $this->redirectToRoute('app_category_list');
    }
}
