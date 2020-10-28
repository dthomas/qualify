<?php

namespace App\Form;

use App\Entity\Lead;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LeadFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'placeholder' => 'Name',
                ],
            ])
            ->add('contact', ContactFormType::class, [
                'label' => 'Primary Contact',
            ])
            ->add('address', AddressFormType::class, [
                'label' => 'Address',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Lead::class,
        ]);
    }
}
