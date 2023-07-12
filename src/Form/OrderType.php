<?php

namespace App\Form;

use App\Entity\Adress;
use App\Entity\Carrier;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        //dd($options['user']);

        $builder
            ->add('adresses', EntityType::class, [

                'class' => Adress::class,
                //'choice_label' => 'name',
                'multiple' => false,
                'expanded' => true,
                'choices' => $options['user']->getAdresses(),
                'label' => 'Choisissez une adresse'

            ])


            ->add('transporteurs', EntityType::class, [

                'class' => Carrier::class,
                //'choice_label' => 'name',
                'multiple' => false,
                'expanded' => true,
                'label' => 'Choisissez un transporteur'

            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'user' => null
            // Configure your form options here
        ]);
    }
}
