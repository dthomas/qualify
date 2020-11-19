<?php

namespace App\Form;

use App\Entity\OpportunityItem;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OpportunityItemFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'autocomplete' => false,
                    'placeholder' => 'Title',
                ],
                'label' => false,
            ])
            ->add('remarks', TextareaType::class, [
                'attr' => [
                    'placeholder' => 'Remarks',
                ],
                'label' => false,
                'required' => false,
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Not Started' => 'not-started',
                    'In Progress' => 'in-progress',
                    'Completed' => 'completed',
                    'Dropped' => 'dropped',
                    'Stuck' => 'stuck',
                ],
                'label' => 'Status',
                'placeholder' => 'Please select',
            ])
            ->add('dueAt', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('product', EntityType::class, [
                'class' => Product::class,
                'choice_label' => 'name',
                'placeholder' => 'Please select',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OpportunityItem::class,
        ]);
    }
}
