<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class ProductFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code', TextType::class, [
                'attr' => [
                    'placeholder' => 'Product Code',
                ],
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 2, 'max' => 32]),
                    new Type(['type' => 'alnum']),
                ]
            ])
            ->add('name', TextType::class, [
                'attr' => [
                    'placeholder' => 'Product Code',
                ],
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 2, 'max' => 120]),
                ]
            ])
            ->add('isAvailable', ChoiceType::class, [
                'choices' => [
                    'Yes' => true,
                    'No' => false,
                ],
                'label' => 'Item Available',
            ])
            ->add('isDiscontinued', ChoiceType::class, [
                'choices' => [
                    'Yes' => true,
                    'No' => false,
                ],
                'label' => 'Discontinued Item',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
