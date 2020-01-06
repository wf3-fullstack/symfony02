## MON PROJET SYMFONY 


## INSTALLATION DE SYMFONY

https://symfony.com/doc/current/setup.html#creating-symfony-applications

## GIT EXEMPLE

AVEC LA CONSOLE

    git status

    git add -A

    git status

    git commit -a -m "message du commit"

    git push


## CONTROLLER POUR L'ESPACE PUBLIC

AJOUTER LE FICHIER .htaccess

    composer require symfony/apache-pack



    On va créer 2 controllers

    PublicController.php

    AdminController.php

    php bin/console make:controller PublicController

    php bin/console make:controller AdminController



    ET CHANGER URL POUR ACCUEIL


## DATABASE POUR SQL

    MODIFIER LE FICHIER .env

    # DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7
    DATABASE_URL=mysql://root:@127.0.0.1:3306/symfony02?serverVersion=5.7

    ON VA CREER LA DATABASE EN LIGNE DE COMMANDE

    php bin/console doctrine:database:create

    Created database `symfony02` for connection named default

## CREATION DE ENTITE User AVEC SYMFONY

    php bin/console make:user

    REPONDRE AVEC LES CHOIX PAR DEFAUT...

    
    created: src/Entity/User.php
    created: src/Repository/UserRepository.php
    updated: src/Entity/User.php
    updated: config/packages/security.yaml

## CREATION DU CODE POUR LE LOGIN

    php bin/console make:auth

    1

    LoginFormAuthenticator

    SecurityController



## CREER LES TABLES SQL    


    php bin/console make:migration

    => CREER LE FICHIER PHP Version...

    php bin/console doctrine:migrations:migrate


    SI ON A MARIA DB QUI NE GERE PAS LE TYPE JSON

    https://mariadb.com/kb/en/json-data-type/

        SQLSTATE[42000]: Syntax error or access violation: 1064 You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C7' at line 1


    /**
     * @ORM\Column(type="text")
     */
    private $roles = [];


    make:migration  => LONGTEXT

        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');


## LANCER LA make:crud POUR CREER LES PAGES POUR AJOUTER DES User


    php bin/console make:crud User


    created: src/Controller/UserController.php
    created: src/Form/UserType.php
    created: templates/user/_delete_form.html.twig
    created: templates/user/_form.html.twig
    created: templates/user/edit.html.twig
    created: templates/user/index.html.twig
    created: templates/user/new.html.twig
    created: templates/user/show.html.twig


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            //->add('roles')
            ->add('password')
        ;
    }

    BRICOLAGE DE LA CLASSE User.php
    ...

## AJOUT DE CODE TWIG POUR AFFICHER LA LISTE DES ROLES


## ROUTES AVEC PARAMETRES


https://symfony.com/doc/current/routing.html#route-parameters


ON PEUT AJOUTER DES CONTRAINTES SUR LES PARAMETRES


    /**
     * @Route("/test/{id}", name="testRoute", requirements={"id"="\d+"})
     */



## READ AVEC DOCTRINE


https://symfony.com/doc/current/doctrine.html#fetching-objects-from-the-database

    DOCUMENTATION REFERENCE POUR findBy

https://www.doctrine-project.org/api/orm/latest/Doctrine/ORM/EntityRepository.html#method_findBy


## DEBUGGAGE AVEC SYMFONY ET LA FONCTION dump

NE PAS HESITER A DEBUGGER AVEC LA FONCTION dump
POUR COMPRENDRE LES VALEURS DES VARIABLES


## A VOIR AVEC SYMFONY

MAIL
FORMULAIRE ET VALIDATION        OK (CONTRAINTES)
FORMULAIRE ET UPLOAD
FORMULAIRE ET HTML ET CSS
FORMULAIRE ET AJAX (ET JSON)
READ AVEC JOINTURE
FORMULAIRE ET CREATE/UPDATE/DELETE AVEC JOINTURE

EXEMPLE PRATIQUE: 
PANIER
PAIEMENT: CONSEIL PASSER PAR API PAYPAL
            => DOCUMENTATION A LIRE (TUTORIEL A SUIVRE...)

PRODUIRE DES PDF: CONSEIL PASSER PAR BIBLIOTHEQUES PHP
    ...NOM A TROUVER...


## VALIDATION DE FORMULAIRE


https://symfony.com/doc/current/validation.html


EXEMPLE EMAIL:

https://symfony.com/doc/current/reference/constraints/Email.html

UNICITE

https://symfony.com/doc/current/reference/constraints/UniqueEntity.html

ATTENTION: IL FAUT UNE CONTRAINTE D'UNICITE PAR COLONNE...

    /**
    * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
    * @UniqueEntity(
    *      fields="username", 
    *      message="bad username"
    * )
    * @UniqueEntity(
    *      fields="email", 
    *      message="DESOLE CHANGE D'EMAIL STP..."
    * )
    */
    class User implements UserInterface



## JOINTURE

IL FAUT AU MOINS 2 TABLES

Annonce => ENTITE
    PROPRIETES                              COLONNE SQL
    (id)
    titre           string(160)
    description     text
    slug            string(160)     => identifiant dans URL (SEO avec des mots clés...)
    prix            decimal(10,2)
    photo           string(160)
    datePublication datetime
    categorie       string(160)
    user            relation(MANY-TO-ONE)   id_user


    php bin/console make:entity Annonce

    => POUR CREER LA TABLE SQL annonce


    created: src/Entity/Annonce.php
    created: src/Repository/AnnonceRepository.php


    php bin/console make:migration



    php bin/console doctrine:migrations:migrate

    php bin/console make:crud Annonce


 created: src/Controller/AnnonceController.php
 created: src/Form/AnnonceType.php
 created: templates/annonce/_delete_form.html.twig
 created: templates/annonce/_form.html.twig
 created: templates/annonce/edit.html.twig
 created: templates/annonce/index.html.twig
 created: templates/annonce/new.html.twig
 created: templates/annonce/show.html.twig



 ## PERSONNALISER LES CHAMPS DE FORMULAIRE


 https://symfony.com/doc/current/forms.html#rendering-forms

 https://symfony.com/doc/current/reference/forms/types.html

 https://symfony.com/doc/current/reference/forms/types/entity.html


 ## MANY TO MANY

    CREER UNE AUTRE ENTITE POUR CREER UNE RELATION MANY OTO MANY
    
 ## ESPACE MEMBRE


    UNE ANNONCE CREEE EST AUTOMATIQUEMENT ATTRIBUEE AU User CONNECTE

 ## PAGE PUBLIQUE annonces

    PAGE QUI AFFICHE LA LISTE DES ANNONCES AVEC LE username DU User
    => JOINTURE SUR UN READ

