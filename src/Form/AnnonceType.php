<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Annonce;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

// ON VA GERER LA RELATION AVEC User
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
// POUR AVOIR UN INPUT type="file"
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

// POUR PROTEGER LES FICHIERS QU'ON PEUT UPLOADER
use Symfony\Component\Validator\Constraints\File;


class AnnonceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre')
            ->add('description')
            ->add('slug', TextType::class, [
                            'attr' => ['class' => 'maClasseAvecPhp'],
            ])
            ->add('prix')
            // https://symfony.com/doc/current/reference/forms/types/file.html
            ->add('photo', FileType::class, [
                                'required'    => false,
                                // => DESACTIVE LE Data Mapper POUR NE PAS AVOIR L'ERREUR
                                'data_class' => null,
                                'constraints' => [
                                        new File([
                                            'maxSize' => '1024k',
                                            // https://symfony.com/doc/current/reference/constraints/File.html#mimetypes
                                            'mimeTypes' => [
                                                'image/*',
                                            ],
                                            'mimeTypesMessage' => "Merci d'envoyer une image valide",
                                        ]),
                                    ],
            ])
            ->add('datePublication')
            ->add('categorie')
            ->add('user', EntityType::class, [
                            'class' => User::class, // ON VA FAIRE UNE RELATION AVEC User
                            'choice_label' => 'username',   // QUELLE PROPRIETE SERA AFFICHEE DANS LE FORMULAIRE
                            // 'expanded' => true,     // CREE DES BOUTONS RADIOS ET PAS UNE BALISE select
                            //'multiple' => true,  // => VA CREER DES CHECKBOX

        ])
        // ON NE RAJOUTE PAS DE BOUTON
        // CE SERA DANS TWIG QUE LE CODE HTML DU BOUTON SERA RAJOUTE
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Annonce::class,
        ]);
    }
}
