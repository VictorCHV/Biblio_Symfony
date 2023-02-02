<?php

namespace App\Controller;

use App\Form\ProfilType;
use App\Form\RegistrationType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityController extends AbstractController
{
    #[Route('/security', name: 'app_security')]
    public function index(): Response
    {
        return $this->render('security/index.html.twig', [
            'controller_name' => 'SecurityController',
        ]);
    }

    /**
     * @Route("/inscription", name="app_security_registration")
     */
    public function registration(UserRepository $repository, Request $request, UserPasswordHasherInterface $hasher): Response
    {

        //création du formulaire
        $form = $this->createForm(RegistrationType::class);

        //on rempli le form avec les données de l'utilisateur
        $form->handleRequest($request);

        //verification si le form est envoyé et est valide
        if($form->isSubmitted() && $form->isValid()){
            //récuperer les données de l'utilisateur
            $user= $form->getData();

            //crypter le mot de passe
            $cryptedPass= $hasher->hashPassword($user , $user->getPassword()); // c'est ici que le mdp ecrit en clair se transforme en hash
            $user->setPassword($cryptedPass);

            //Enregistrer l'utilisateur dans la base de données
            $repository->save($user, true);


            //redirection vers la page d'accueil
            return $this->redirectToRoute('app_front_home_home');
        }

        return $this->render('security/registration.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }


    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }


    /**
     * @IsGranted("ROLE_USER")
     */ 
    #[Route(path: '/mon-profile', name: 'app_security_myProfile')]
    public function myProfile(UserRepository $repository, Request $request): Response
    {
        //récupération des données de l'utilisateur connecté
        $user= $this->getUser();

        //création du formulaire
        $form= $this->createForm(ProfilType::class , $user );

        //remplissage formulaire
        $form->handleRequest($request);

        //test is form valid & submitted
        if($form->isSubmitted() && $form->isValid())

        //enregistrement des modifications via le repository
        $repository->save($user, true);

        //affichage de la page HTML
        return $this->render('security/myProfile.html.twig', [
            'form' => $form->createView(),
        ]);

    }


}
