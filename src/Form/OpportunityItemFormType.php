<?php

namespace App\Form;

use App\Entity\OpportunityItem;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
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
                    'Appointment Scheduled' => 'appointment-scheduled',
                    'Completed' => 'completed',
                    'Dropped' => 'dropped',
                    'Stuck' => 'stuck',
                ],
                'label' => false,
                'placeholder' => 'Select a status',
            ])
            ->add('callbackAt', DateTimeType::class, [
                'attr' => [
                    'class' => 'is-flex is-justify-content-space-between is-align-content-center',
                ],
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'label' => 'Follow up on',
                'required' => false,
            ])
            ->add('appointmentAt', DateTimeType::class, [
                'attr' => [
                    'class' => 'is-flex is-justify-content-space-between is-align-content-center',
                ],
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'label' => 'Schedule an appointment on',
                'required' => false,
            ])
            ->add('product', EntityType::class, [
                'class' => Product::class,
                'choice_label' => 'name',
                'label' => false,
                'placeholder' => 'Select a product',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OpportunityItem::class,
        ]);
    }
}
