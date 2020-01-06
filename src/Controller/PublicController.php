<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\User;
use App\Repository\UserRepository;


class PublicController extends AbstractController
{
    /**
     * @Route("/", name="public")
     */
    public function index()
    {
        return $this->render('public/index.html.twig', [
            'controller_name' => 'PublicController',
        ]);
    }

    /**
     * @Route("/test/{id}", name="testRoute", requirements={"id"="\d+"})
     */
    public function testRoute($id)
    {
        // DEBUG
        dump("LA VALEUR ID EST $id");
        // ON ARRIVE A RECUPERER LA VALEUR FOURNIE DANS L'URL
        // https://symfony.com/doc/current/routing.html#route-parameters


        return $this->render('public/test.html.twig', [
            // CLE => VARIABLE TWIG  ET LES VALEURS PHP SERONT LES VALEURS TWIGS
            'controller_name' => 'PublicController',
            'id'              => $id,
        ]);
    }


    /**
     * @Route("/testlist/{page}", name="testlist")
     */
    public function testlist(UserRepository $userRepository, $page=1)
    {
        // ON PEUT DEMANDER LA PAGE EN PARAMETRE D'URL 
        // ET DONNER UNE VALEUR PAR DEFAUT
        dump("LA PAGE DEMANDEE EST $page");


        https://www.doctrine-project.org/api/orm/latest/Doctrine/ORM/EntityRepository.html#method_count
        $nbUser = $userRepository->count([]);   // ATTENTION, ON DOIT FOURNIR UN TABLEAU EN PARAMETRE

        // https://www.doctrine-project.org/api/orm/latest/Doctrine/ORM/EntityRepository.html#method_findBy
        // ON A BESOIN DE UserRepository POUR FAIRE LA REQUETE READ 
        // POUR RECUPETERER TOUS LES User

        
        $listeUser =  $userRepository->findBy([], ["id" => "DESC"]);
        dump($listeUser);

        return $this->render('public/testlist.html.twig', [
            'listeUser'     => $listeUser,
            'nbUser'        => $nbUser,
        ]);        
    }

    /**
     * @Route("/test/{username}", name="testUsername")
     */
    public function testUsername($username, User $user, UserRepository $userRepository)
    {
        // DEBUG
        dump("LA VALEUR username EST $username");
        // INJECTION DEPENDANCE
        // PARAM CONVERTER
        // SYMFONY VA CHERCHER LA LIGNE QUI CORRESPOND A username DANS LA TABLE SQL user
        dump($user);

        // SI ON VEUT LE FAIRE A LA MAIN
        // JE VAIS FAIRE UNE REQUETE READ POUR RETROUVER LA BONNE LIGNE
        $userRead = $userRepository->findOneBy([ "username" => $username ]);
        dump($userRead);
        if ($userRead != null)
        {

        }
        else
        {

        }
        
        // ON ARRIVE A RECUPERER LA VALEUR FOURNIE DANS L'URL
        // https://symfony.com/doc/current/routing.html#route-parameters

        // AVEC CE $username JE VEUX RETROUVER LA LIGNE DANS LA TABLE SQL user
        // => READ (DANS LE CRUD...)



        return $this->render('public/testUsername.html.twig', [
            // CLE => VARIABLE TWIG  ET LES VALEURS PHP SERONT LES VALEURS TWIGS
            'controller_name' => 'PublicController',
            'username'              => $username,
        ]);
    }


}
