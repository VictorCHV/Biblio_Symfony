<?php

namespace App\Controller\Front;

use App\Form\SearchBookType;
use App\DTO\SearchBookCriteria;
use App\Repository\BookRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class homeController extends AbstractController
{
    #[Route('/front/home', name: 'app_front_home')]
    public function index(): Response
    {
        return $this->render('front/home/index.html.twig', [
            'controller_name' => 'homeController',
        ]);
    }

    /**
     * @Route("admin/front/home" , name="app_front_home_home")
     */
    public function home(BookRepository $repository): Response
    {
        //récupérer les 10 derniers livres (triés par prix décroissant)
        $books= $repository->findLastTen();

        //afficher la page de résultats
        return $this->render('front/home/result.html.twig', [
            'books' => $books,
        ]);
    }

    /**
     * @Route("/search", name="app_front_home_search")
     */
    public function search(BookRepository $repository, Request $request): Response
    {
        //1. Création des critères de recherche
        $criteria = new SearchBookCriteria();

        //2. Création du formulaire
        $form = $this->createForm(SearchBookType::class, $criteria);

        //3. Remplir le formulaire avec les critères de recherche de l'utilisateur
        $form->handleRequest($request);

        //4. récupérer les livres selon les critères donnés
        $books = $repository->findByCriteria($criteria);

        //5. affichage du twig
        return $this->render('front/home/search.html.twig', [
            'form' => $form->createView(),
            'books' => $books
        ]);

    }  

}
