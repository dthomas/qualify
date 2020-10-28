<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('street', TextType::class, [
                'attr' => [
                    'placeholder' => 'Street',
                ],
                'required' => false,
            ])
            ->add('landmark', TextType::class, [
                'attr' => [
                    'placeholder' => 'Landmark',
                ],
                'required' => false,
            ])
            ->add('locality', TextType::class, [
                'attr' => [
                    'placeholder' => 'Locality',
                ],
                'required' => false,
            ])
            ->add('district', TextType::class, [
                'attr' => [
                    'placeholder' => 'District',
                ],
                'required' => false,
            ])
            ->add('state', ChoiceType::class, [
                'attr' => [],
                'choices' => [
                    'Karnataka' => 'Karnataka',
                    'Kerala' => 'Kerala',
                    'Maharashtra' => 'Maharashtra',
                ],
                'placeholder' => 'Please select',
                'required' => false,
            ])
            ->add('postalCode', TextType::class, [
                'attr' => [
                    'placeholder' => 'PIN Code',
                ],
                'required' => false,
            ])
            ->add('plusCode', TextType::class, [
                'attr' => [
                    'placeholder' => 'PlusCode',
                ],
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Address::class
        ]);
    }
}
