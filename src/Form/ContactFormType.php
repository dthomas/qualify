<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('phone', TelType::class, [
                'attr' => [
                    'placeholder' => 'Phone Number',
                ]
            ])
            ->add('isPhoneVerified', ChoiceType::class, [
                'choices' => [
                    'Yes' => true,
                    'No' => false,
                ],
                'label' => 'Phone Number Verified',
                'placeholder' => 'Please Select',
                'required' => false,
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'placeholder' => 'Email Address',
                ],
                'required' => false,
            ])
            ->add('isEmailVerified', ChoiceType::class, [
                'choices' => [
                    'Yes' => true,
                    'No' => false,
                ],
                'label' => 'Email Address Verified',
                'placeholder' => 'Please Select',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
