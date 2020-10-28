<?php

namespace App\Form;

use App\Entity\LeadSource;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LeadSourceFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code', TextType::class, [
                'attr' => [
                    'placeholder' => 'Code',
                ]
            ])
            ->add('name', TextType::class, [
                'attr' => [
                    'placeholder' => 'Name',
                ]
            ])
            ->add('isActive', ChoiceType::class, [
                'choices' => [
                    'Yes' => true,
                    'No' => false,
                ],
                'placeholder' => 'Please Select',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => LeadSource::class,
        ]);
    }
}
