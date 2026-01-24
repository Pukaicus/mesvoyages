<?php

namespace App\Form;

use App\Entity\Voyage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
// NE PAS OUBLIER CETTE LIGNE CI-DESSOUS
use Symfony\Component\Form\Extension\Core\Type\FileType;

class VoyageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('description')
            // ON AJOUTE LE CHAMP IMAGE ICI
            ->add('image', FileType::class, [
                'label' => 'Photo du voyage (Fichier image)',
                'mapped' => false,     // Très important : dit à Symfony de ne pas chercher à mettre le fichier direct en BDD
                'required' => false,   // Permet de ne pas être obligé de mettre une photo à chaque fois
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Voyage::class,
        ]);
    }
}
