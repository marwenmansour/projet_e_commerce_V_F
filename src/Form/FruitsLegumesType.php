<?php

namespace App\Form;

use App\Entity\FruitsLegumes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FruitsLegumesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class)
            ->add('origine', TextType::class)
            ->add('prix', NumberType::class)
            ->add('saison', TextType::class)
            ->add('type', TextType::class)
            ->add('imageFile', VichImageType::class)
            ->add('submit', SubmitType::class,[ 
                'label' => 'Envoyer'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FruitsLegumes::class,
        ]);
    }
}
