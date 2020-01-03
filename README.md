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

    

