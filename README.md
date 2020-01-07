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

    OK =>    MAIL
    https://symfony.com/doc/current/mailer.html

    OK =>   FORMULAIRE ET VALIDATION        (CONTRAINTES)
    https://symfony.com/doc/current/validation.html

    OK => FORMULAIRE ET UPLOAD
    https://symfony.com/doc/current/controller/upload_file.html

    FORMULAIRE ET HTML ET CSS
    https://symfony.com/doc/current/forms.html

    OK => FORMULAIRE ET AJAX (ET JSON)
    https://symfony.com/doc/current/components/http_foundation.html#creating-a-json-response

    READ AVEC JOINTURE
    https://www.doctrine-project.org/projects/doctrine-orm/en/2.7/reference/dql-doctrine-query-language.html

    https://www.wanadev.fr/56-comment-realiser-de-belles-requetes-sql-avec-doctrine/

    FORMULAIRE ET CREATE/UPDATE/DELETE AVEC JOINTURE
    https://symfony.com/doc/current/forms.html#rendering-forms


    OK => INJECTION DEPENDANCES AVEC SYMFONY

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

    CREER UNE AUTRE ENTITE POUR CREER UNE RELATION MANY TO MANY

    LIKE ENTRE User ET Annonce

    php bin/console make:entity Annonce


    php bin/console make:migration

    
    php bin/console doctrine:migrations:migrate

    => SYMFONY CREE LA TABLE INTERMEDIAIRE annonce_user



 ## ESPACE MEMBRE


    UNE ANNONCE CREEE EST AUTOMATIQUEMENT ATTRIBUEE AU User CONNECTE

 ## PAGE PUBLIQUE annonces

    PAGE QUI AFFICHE LA LISTE DES ANNONCES AVEC LE username DU User
    => JOINTURE SUR UN READ



## MAILS AVEC SYMFONY


COMPOSANT HISTORIQUE

https://symfony.com/doc/current/email.html


NOUVEAU COMPOSANT (EN COURS DE DEV...)

https://symfony.com/doc/current/mailer.html


    composer require symfony/mailer
    => Nothing to install or update
    => DEJA INSTALLE DANS NOTRE PROJET


    CONFIGURER LE SERVICE DANS .env

    # EN MODE DEV, POUR SIMULER LES EMAILS
    # REGARDER DANS LE PROFILER
    MAILER_DSN=null://null

    # SUR LE VRAI HEBERGEMENT
    MAILER_DSN=smtp://localhost



    ###> symfony/mailer ###
    # POUR LE VRAI SITE (SUR L'HEBERGEMENT...)
    # MAILER_DSN=smtp://localhost
    # https://symfony.com/doc/current/mailer.html#disabling-delivery
    # DESACTIVE L'ENVOI DE MAIL (POUR LE DEV)
    MAILER_DSN=null://null
    ###< symfony/mailer ###


    ENVOI DE MAIL DANS LE PHP (CONTROLLER)

            // IL FAUT ENVOYER UN EMAIL A L'ADMIN
            $email = (new Email())
                ->from('contact@monsite.fr')
                ->to('admin@monsite.fr')
                //->cc('cc@example.com')
                //->bcc('bcc@example.com')
                //->replyTo('fabien@example.com')
                //->priority(Email::PRIORITY_HIGH)
                ->subject('NOUVELLE INSCRIPTION')
                ->text('NOUVELLE INSCRIPTION')
                ->html('<p>NOUVELLE INSCRIPTION</p>');
                // ON PEUT UTILISER DES TEMPLATES TWIG POUR CREER LE HTML DES EMAILS
                // https://symfony.com/doc/current/mailer.html#twig-html-css


## CREER DE NOUVEAUX FORMULAIRES


    https://symfony.com/doc/current/forms.html#creating-form-classes

    ON VA UTILISER LA CONSOLE POUR GENERER LA BASE DE CODE POUR UN NOUVEAU FORMULAIRE

    php bin/console make:form


## INJECTION DEPENDANCES


```php

class MonCode
{
    // METHODE SANS INJECTION DE DEPENDANCE
    function ecrireMonCode ()
    {
        // DEPENDANCES ENTRE CLASSES
        // ON A BESOIN DE PLEIN D'OBJETS AVANT DE POUVOIR CONSTRUIRE L'OBJET QUI NOUS INTERESSE
        $objet1 = new MaClasse1;

        $objet2 = new MaClasse2($objet1);

        $objet3 = new MaClasse3($objet1, $objet2);

        // ON VEUT JUSTE POUVOIR APPELER LA METHODE
        // MAIS ON NE VEUT AVOIR A GERER LA CREATION (new)
        $objet3->faireTravail();

    }

    // ON RECOIT L'OBJET EN PARAMETRE
    // ON N'A PAS BESOIN DE FAIRE new
    function ecrireMonCodeAvecInjection (MaClasse3 $objet3, MaClasse4 $objet4)
    {
        // ON VEUT JUSTE POUVOIR APPELER LA METHODE
        // MAIS ON NE VEUT AVOIR A GERER LA CREATION (new)
        $objet3->faireTravail();

    }
}

```

    INTROSPECTION OU REFLECTION

    https://www.php.net/manual/fr/book.reflection.php

    // PHP PERMET D'ANALYSER LE CODE PHP 
    // ET D'EXTRAIRE LES INFORMATIONS SUR LES TYPES DES PARAMETRES DES METHODES

    https://www.php.net/manual/fr/reflectionparameter.gettype.php


## BEST PRACTICES: SYMFONY RECOMMANDE DE CENTRALISER LE CODE PHP POUR UN FORMULAIRE

    https://symfony.com/doc/current/best_practices.html#use-a-single-action-to-render-and-process-the-form

    PROBLEME: PAS A JOUR PAR RAPPORT AUX ARCHITECTURES LOGICIELLES ACTUELLES 
    (AJAX, ANGULAR, REACT, VUEJS, etc...)


## UPLOAD DE FICHIER


    ATTENTION A LA SECURITE: 
    BIEN AJOUTER DES CONTRAINTES SUR LES EXTENSIONS DE FICHIERS...
    (...constraints...)

    https://symfony.com/doc/current/controller/upload_file.html


## CREATION DE FORMULAIRE DE CREATION De User

    php bin/console make:registration-form

    


