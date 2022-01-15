<?php

namespace App\Form;

use App\Entity\Panier;

use App\Entity\Utilisateur;
use App\Entity\FruitsLegumes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PanierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class)
            ->add('adresse', TextType::class)
            ->add('telephone', NumberType::class)
            ->add('prix_totale', NumberType::class)
            ->add('fruitETlegumes',EntityType::class, [
                'class' => FruitsLegumes::class,
                'choice_label' => 'nom',
                'multiple'=> True
            ])
            ->add('user', EntityType::class, [
                'class' => Utilisateur::class,
                'choice_label' => 'pseudo'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'payer'
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Panier::class,
        ]);
    }
}
