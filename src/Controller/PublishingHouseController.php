<?php

namespace App\Controller;

use App\Entity\PublishingHouse;
use App\Form\PublishingHouseType;
use App\Repository\PublishingHouseRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_ADMIN")
 * 
 */

class PublishingHouseController extends AbstractController
{
    #[Route('/publishing/house', name: 'app_publishing_house')]
    public function index(): Response
    {
        return $this->render('publishing/house/index.html.twig', [
            'controller_name' => 'PublishingHouseController',
        ]);
    }

    /**
     * @Route("/admin/publishing_house/nouvelle", name="app_publishing_house_create")
     */
    public function create(Request $request , PublishingHouseRepository $repository): Response
    {
        // création de l'objet PublishingHouse
        $publishinghouse= new PublishingHouse();

        // création du formulaire
        $form= $this->createForm(PublishingHouseType::class , $publishinghouse);
        //remplissage du formulaire et de l'objet php avec la requete
        $form->handleRequest($request); 

        // tester si le formulaire est envoyé avec des données valides
        if($form->isSubmitted() && $form->isValid()){
            // recuperation des données du formulaire dans l'objet PublishingHouse
            $validPublishingHouse = $form->getData();
            // enregistrer les données de la maison d'édition dans la DB
            $repository->save($validPublishingHouse, true);

            // redirection vers la liste des maisons d'édition
            return $this->redirectToRoute('app_publishinghouse_list');
        }

        // récuperation de la view du formulaire
        $formView= $form->createView();

        //affichage dans le template
        return $this->render('publishing_house/create.html.twig' , [
            'form' => $formView,
        ]);
    }

     /**
     * @Route("/admin/publishing_house", name="app_publishinghouse_list")
     */
    public function list(PublishingHouseRepository $repository): Response
    {

        //recuperer les maisons d'éditions depuis la DB
        $publishinghouses= $repository->findAll(); //retourne la liste des maisons d'éditions

        return $this->render('publishing_house/list.html.twig', [
            'publishinghouses' => $publishinghouses,
        ] );

    }

     /**
     * @Route("/publishing_house/updateForm/{id}", name="app_publishinghouse_updateForm")
     */

     public function updateForm (int $id, PublishingHouseRepository $repository, Request $request):Response
     {
         //recuperer les données de la maison d'édition de l'id
         $publishinghouse= $repository->find($id);
 //--------------Même partie que la création-------------------
         //création du formulaire
         $form= $this->createForm(PublishingHouseType::class , $publishinghouse );
         
         //remplissage du formulaire et de l'objet php avec la requete
         $form->handleRequest($request); 
 
         //tester si le formulaire est envoyé avec données valides
         if($form->isSubmitted() && $form->isValid()){
 
             //recuperation de l'objet validé et remplie par le formulaire
             $validPublishinghouse = $form->getData();
 
             //enregistrer les données dans la DB
             $repository->save($validPublishinghouse, true);
 
             //redirection vers la liste des maisons d'édition
             return $this->redirectToRoute('app_publishinghouse_list');
         }
 
         //récuperation de la view du formulaire
         $formView= $form->createView();
 
         //affichage dans le template
         return $this->render('publishing_house/update.html.twig' , [
             'form' => $formView,
             'publishinghouse' => $publishinghouse,
         ]);
     }

    /**
     * @Route("/admin/publishing_house/{id}/supprimer", name="app_publishinghouse_remove")
     */
    public function remove(int $id, Request $request , PublishingHouseRepository $repository): Response
    {
        //recuperer l'auteur depuis son id
        $publishinghouse = $repository->find($id);

        //suprimer l'auteur de la DB
        $repository->remove($publishinghouse, true);

        //redirection vers la liste des auteurs
        return $this->redirectToRoute('app_publishinghouse_list');
    }

}
