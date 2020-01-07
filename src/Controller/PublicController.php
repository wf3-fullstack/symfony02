<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Form\UserType;
use Symfony\Component\HttpFoundation\Request;

// POUR ENVOYER UN EMAIL
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

// FORMULAIRE D'ACTIVATION
use App\Form\ActivationUserType;

use App\Repository\AnnonceRepository;


class PublicController extends AbstractController
{
    /**
     * @Route("/", name="public")
     */
    public function index(AnnonceRepository $annonceRepository)
    {
        // READ SUR LES Annonce
        // ET ENSUITE ON TRANSLET A TWIG POUR L'AFFICHAGE
        $annonces = $annonceRepository->findBy([], [ "datePublication" => "DESC"]);
        // DEBUG
        dump($annonces);

        return $this->render('public/index.html.twig', [
            // CLES => VARIABLES TWIG
            "classBody" => "index",
            "annonces"  => $annonces,
        ]);
    }

    /**
     * @Route("/inscription", name="inscription")
     */
    public function inscription(Request $request, MailerInterface $mailer)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        $message = "";
        if ($form->isSubmitted() && $form->isValid()) {

            // BRICOLAGE POUR RATTRAPER LE PROBLEME SUR roles
            $user->setRoles(["ROLE_USER"]);

            // HASHAGE DU MOT DE PASSE
            $passwordNonHashe = $user->getPassword();
            $passwordHashe    = password_hash($passwordNonHashe, PASSWORD_BCRYPT);
            $user->setPassword($passwordHashe);

            // JE CREE UNE CLE D'ACTIVATION ALEATOIRE
            $cleActivation = md5(password_hash(uniqid(), PASSWORD_DEFAULT));
            $user->setCleActivation($cleActivation);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // OK ON A CREE UN NOUVEL User
            $message = "COOL BIENVENUE TON COMPTE EST CREE";


            // IL FAUT ENVOYER UN EMAIL A L'ADMIN
            $email = (new Email())
                ->from('contact@monsite.fr')
                ->to('admin@monsite.fr')
                //->cc('cc@example.com')
                //->bcc('bcc@example.com')
                //->replyTo('fabien@example.com')
                //->priority(Email::PRIORITY_HIGH)
                ->subject("NOUVELLE INSCRIPTION")
                ->text("NOUVELLE INSCRIPTION CLE ACTIVATION: $cleActivation")
                ->html("<p>NOUVELLE INSCRIPTION CLE ACTIVATION: $cleActivation</p>");
                // ON PEUT UTILISER DES TEMPLATES TWIG POUR CREER LE HTML DES EMAILS
                // https://symfony.com/doc/current/mailer.html#twig-html-css


            /** @var Symfony\Component\Mailer\SentMessage $sentEmail */
            $sentEmail = $mailer->send($email);
            // $messageId = $sentEmail->getMessageId();

            // return $this->redirectToRoute('user_index');
        }

        // AFFICHAGE DE LA PAGE
        return $this->render('public/inscription.html.twig', [
            // CLES => VARIABLES TWIG
            'message'           => $message,
            'form'              => $form->createView(),
            "classBody"         => "inscription",

        ]);
    }



    /**
     * @Route("/activation", name="activation")
     */
    public function activation(Request $request, MailerInterface $mailer, UserRepository $userRepository)
    {

        // ON VA CREER UN FORMULAIRE A PARTIR DE LA CLASSE ActivationUserType
        $form = $this->createForm(ActivationUserType::class);
        // ON TRANSMET LES INFOS DE LA REQUETE AU FORMULAIRE $form
        $form->handleRequest($request);

        // TRAITEMENT DU FORMULAIRE
        if ($form->isSubmitted())
        {
            // DEBUG
            dump("FORMULAIRE SOUMIS A TRAITER");
            if ($form->isValid()) 
            {
                // DEBUG
                dump("FORMULAIRE VALIDE A TRAITER");
                // RECUPERER LES INFOS DU FORMULAIRE
                // $tabInfo = $request->get("activation_user);
                // https://symfony.com/doc/current/form/without_class.html");
                $tabInfo = $form->getData();
                extract($tabInfo);
                // extract va créer les variables
                // $email
                // $cleActivation

                // ON PASSE PAR $form
                // $email = $form->get("email")->getData();
                // $cleActivation = $form->get("cleActivation")->getData();

                // REQUETE READ SUR ENTITE User
                $userTrouve = $userRepository->findOneBy([ "email" => $email, "cleActivation" => $cleActivation]);
                if ($userTrouve != null)
                {
                    // OK ON A TROUVE
                    dump($userTrouve);

                    // IL FAUT ACTIVER LE User
                    // ET IL FAUT EFFACER LA cleActivation
                    $userTrouve->setRoles(["ROLE_TOTO", "ROLE_MEMBRE"]);
                    $userTrouve->setCleActivation(uniqid());

                    // SYNCHRONISER AVEC LA TABLE SQL
                    $entityManager = $this->getDoctrine()->getManager();
                    // ON N'A PAS BESOIN DE FAIRE persist
                    // => ON A RECUPERE $userTrouve AVEC $userRepository
                    // ET DONC SYMFONY SE SOUVIENT DU LIEN ENTRE L'ENTITE ET LA LIGNE SQL
                    $entityManager->flush();
                }
                else
                {
                   // ERREUR
                   dump("USER NON TROUVE");     
                }

                // DEBUG
                dump("INFOS RECUPEREES $email/$cleActivation");
            }
        }

        // AFFICHAGE DE LA PAGE
        return $this->render('public/activation.html.twig', [
            // CLES => VARIABLES TWIG
            "form"      => $form->createView(),
            "message"   => $message ?? "",
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
