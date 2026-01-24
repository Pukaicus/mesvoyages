<?php

namespace App\Form;

use App\Entity\Voyage;
use App\Entity\Environnement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class VoyageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('ville')
            ->add('pays')
            ->add('description', TextareaType::class)
            ->add('image', FileType::class, [
                'label' => 'Photo du voyage',
                'mapped' => false,
                'required' => false,
            ])
            ->add('environnement', EntityType::class, [
                'class' => Environnement::class,
                'choice_label' => 'nom',
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
