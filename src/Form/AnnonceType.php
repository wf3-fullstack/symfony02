<?php

namespace App\Form;

use App\Entity\Annonce;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

// ON VA GERER LA RELATION AVEC User
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
// POUR AVOIR UN INPUT type="file"
use Symfony\Component\Form\Extension\Core\Type\FileType;


class AnnonceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre')
            ->add('description')
            ->add('slug')
            ->add('prix')
            // https://symfony.com/doc/current/reference/forms/types/file.html
            ->add('photo', FileType::class)
            ->add('datePublication')
            ->add('categorie')
            ->add('user', EntityType::class, [
                            'class' => User::class, // ON VA FAIRE UNE RELATION AVEC User
                            'choice_label' => 'username',   // QUELLE PROPRIETE SERA AFFICHEE DANS LE FORMULAIRE
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Annonce::class,
        ]);
    }
}
