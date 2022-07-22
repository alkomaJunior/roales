<?php

/**
 * ROALES
 *
 * This file is part of ROALES.
 *
 * ROALES is free road map trip web app: you can redistribute it and/or modify
 * it under the terms of the MIT License.
 *
 * @copyright   Copyright ROALES
 *
 * @license     MIT License
 */

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @access public
 *
 * @version 0.1
 */
class RegistrationFormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullName', TextType::class, [
                'label_attr' => ["class" => "form-label"],
                'attr' => [
                    "class" => "form-control",
                    "placeholder" => "Enter your full name",
                ],
            ])
            ->add('email', EmailType::class, [
                'label_attr' => ["class" => "form-label"],
                'attr' => [
                    "class" => "form-control",
                    "placeholder" => "Enter your email address",
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'label_attr' => ["class" => "form-label"],
                    'attr' => [
                        "class" => "form-control",
                        "placeholder" => "******",
                    ],
                ],
                'second_options' => [
                    'label_attr' => ["class" => "form-label"],
                    'attr' => [
                        "class" => "form-control",
                        "placeholder" => "******",
                    ],
                ],
                'invalid_message' => 'These fields are not the same',
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'validation_groups' => ['register'],
        ]);
    }
}
