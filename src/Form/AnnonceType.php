<?php

namespace App\Form;

use App\Entity\Annonce;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

// ON VA GERER LA RELATION AVEC User
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class AnnonceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre')
            ->add('description')
            ->add('slug')
            ->add('prix')
            ->add('photo')
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
