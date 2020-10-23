<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class OwnerFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'placeholder' => 'Your Name',
                ],
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 3, 'max' => 120]),
                ],
                'label' => 'Your Name',
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'placeholder' => 'Work Email',
                ],
                'constraints' => [
                    new NotBlank(),
                    new Email(),
                    new Length(['min' => 6, 'max' => 80]),
                ],
                'label' => 'Work Email',
            ])
            ->add('phone', TelType::class, [
                'attr' => [
                    'placeholder' => 'Mobile Number',
                ],
                'constraints' => [
                    new NotBlank(),
                    new Type(['type' => 'digit']),
                    new Length(['min' => 10, 'max' => 10]),
                ],
                'label' => 'Mobile Number',
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Passwords must match',
                'required' => true,
                'first_options' => [
                    'attr' => [
                        'placeholder' => 'Password',
                    ],
                    'constraints' => [
                        new NotBlank(),
                        new Length(['min' => 8, 'max' => 4096]),
                    ],
                    'label' => 'Password',
                ],
                'second_options' => [
                    'attr' => [
                        'placeholder' => 'Repeat Password',
                    ],
                    'label' => 'Repeat Password',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class
        ]);
    }
}
