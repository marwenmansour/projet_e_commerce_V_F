<?php

namespace App\Form;

use App\Entity\FruitsLegumes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
            ->add('saison', ChoiceType::class,[
                'multiple' => false,
                'required' => true,
                'choices' => [
                    'Automne' => 'Automne',
                    'hiver' => 'hiver',
                    'printemps' => 'printemps',
                    'été' => 'été',
                ]
            ])
            ->add('type', ChoiceType::class,[
                'multiple' => false,
                'required' => true,
                'choices' => [
                    'légumes' => 'légumes',
                    'fruits' => 'fruits',
                ]
            ])
            ->add('imageFile', VichImageType::class, [
                'required' => false,
                'download_link' => false,
                'image_uri' => true
            ])
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
