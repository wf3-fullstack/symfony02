<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Form\AnnonceType;
use App\Repository\UserRepository;
use App\Repository\AnnonceRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/annonce")
 */
class AnnonceController extends AbstractController
{
    /**
     * @Route("/", name="annonce_index", methods={"GET"})
     */
    public function index(AnnonceRepository $annonceRepository): Response
    {
        return $this->render('annonce/index.html.twig', [
            'annonces' => $annonceRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="annonce_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $annonce = new Annonce();
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // IL FAUT GERER LE FICHIER UPLOADE AVEC photo
            // https://symfony.com/doc/current/controller/upload_file.html
            $photo = $form['photo']->getData();
            if ($photo)
            {
                // ON A UN FICHIER UPLOADE
                // https://www.php.net/manual/fr/transliterator.transliterate.php
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                //$safeFilename = \Transliterator::transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $safeFilename =  $originalFilename;
                $fileName = $safeFilename . '-' . uniqid() . '.' . $photo->guessExtension();

                // ON VA STOCKER CE NOM EN BASE DE DONNEES
                $annonce->setPhoto($fileName);

                // ON VA STOCKER LE FICHIER
                $projectDir = $this->getParameter("kernel.project_dir");
                $cheminDossier = "$projectDir/public/assets/upload";
                dump($projectDir);

                $photo->move($cheminDossier, $fileName);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($annonce);
            $entityManager->flush();

            return $this->redirectToRoute('annonce_index');
        }

        return $this->render('annonce/new.html.twig', [
            'annonce' => $annonce,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="annonce_show", methods={"GET"})
     */
    public function show(Annonce $annonce, Request $request): Response
    {
        // IL FAUDRAIT QUE JE RAJOUTE LE CODE POUR TRAITER LE FORMULAIRE
        // A LA MAIN
        // dump($annonce);
        // dump($_REQUEST);
        // dump($request);

        // AVEC SYMFONY, POUR RECUPERER LES INFOS DE FORMULAIRE
        // ON PASSE PAR L'OBJET $request ET LA METHODE get
        $identifiantFormulaire = $request->get("identifiantFormulaire");
        dump($identifiantFormulaire);
        if ($identifiantFormulaire == "like")
        {
            // ON AJOUTE UN LIKE SUR L'ANNONCE AVEC LE User CONNECTE
            // ON RECUPERE L'id DU FORMULAIRE
            $id =  $request->get("id");
            // ON A BESOIN DE L'ENTITE User
            $userConnecte = $this->getUser();
            dump($userConnecte);
            // ON AJOUTE LE LIKE EN PARTANT DE L'UTILISATEUR
            // $annonce->addUser($userConnecte);
            // ON MANIPULE DES ENTITES (objet) ET DES RELATIONS
            $userConnecte->addLikeannonce($annonce);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($userConnecte);
            $entityManager->flush();
        }

        return $this->render('annonce/show.html.twig', [
            'annonce' => $annonce,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="annonce_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Annonce $annonce): Response
    {
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('annonce_index');
        }

        return $this->render('annonce/edit.html.twig', [
            'annonce' => $annonce,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="annonce_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Annonce $annonce): Response
    {
        if ($this->isCsrfTokenValid('delete'.$annonce->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($annonce);
            $entityManager->flush();
        }

        return $this->redirectToRoute('annonce_index');
    }
}
