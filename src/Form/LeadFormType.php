<?php

namespace App\Form;

use App\Entity\Lead;
use App\Entity\LeadSource;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
            ])
            ->add('leadSource', EntityType::class, [
                'class' => LeadSource::class,
                'choice_label' => 'name',
                'label' => 'Lead Source',
                'placeholder' => 'Please Select',
            ])
            ->add('product', EntityType::class, [
                'class' => Product::class,
                'choice_label' => 'name',
                'label' => 'Product',
                'placeholder' => 'Please Select',
                'required' => false,
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Lead::class,
        ]);
    }
}
