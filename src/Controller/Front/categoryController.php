<?php

namespace App\Controller\Front;


use App\Repository\BookRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class categoryController extends AbstractController
{
    #[Route('/front/category', name: 'app_front_category')]
    public function index(): Response
    {
        return $this->render('front/category/index.html.twig', [
            'controller_name' => 'categoryController',
        ]);
    }

    /**
     * @Route("admin/front/categorie/{id}" , name="app_front_category_display")
     */
    public function display (int $id, BookRepository $repository, CategoryRepository $catrepo): Response
    {
        //récupérer les livres de la catégorie ciblée (triés par prix décroissant) 
        $books= $repository->findTargetCategory($id);
        $category = $catrepo->find($id);

        //afficher la page de résultats
        return $this->render('front/category/result.html.twig', [
            'books' => $books,
            'category' => $category,
        ]);
    }
    

}


    
