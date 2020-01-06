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

