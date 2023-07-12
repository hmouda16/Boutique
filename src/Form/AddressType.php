<?php

namespace App\Form;

use App\Entity\Adress;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Country;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TelType;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeHolder' => 'Votre adresse'
                ]

            ])
            ->add('firstName', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeHolder' => 'Votre prenom'
                ]

            ])
            ->add('lastName', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeHolder' => 'Votre nom'
                ]

            ])
            ->add('company', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeHolder' => 'Votre société'
                ]

            ])
            ->add('address', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeHolder' => 'Votre adresse'
                ]

            ])
            ->add('zipCode', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeHolder' => 'Votre code postale'
                ]

            ])
            ->add('city', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeHolder' => 'Votre code ville'
                ]

            ])
            ->add('country', CountryType::class, [
                'label' => false,
                'attr' => [
                    'placeHolder' => 'Nommez votre pays'
                ]

            ])
            ->add('phone', TelType::class, [
                'label' => false,
                'attr' => [
                    'placeHolder' => 'Votre téléphone'
                ]

            ])

            ->add('submit', SubmitType::class, ['label' => 'Enregister l\'adresse', 'attr' => ['class' => 'col-12 btn btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Adress::class,
        ]);
    }
}
