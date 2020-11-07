<?php

namespace App\Form;

use App\Entity\LeadInteraction;
use App\Entity\LeadStage;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LeadInteractionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('callbackAt', DateTimeType::class, [
                'attr' => [
                    'class' => 'is-flex is-justify-content-space-between is-align-content-center',
                ],
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'label' => 'Next Followup At',
            ])
            ->add('remarks', TextareaType::class, [
                'attr' => [
                    'placeholder' => 'Remarks',
                ],
            ])
            // ->add('parentLead', LeadFormType::class)
            ->add('leadStage', EntityType::class, [
                'class' => LeadStage::class,
                'choice_label' => 'name',
                'placeholder' => 'Please select',
                'required' => 'true',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => LeadInteraction::class,
        ]);
    }
}
