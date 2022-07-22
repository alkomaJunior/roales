<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullName', TextType::class, [
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'attr' => [
                    'class' => 'form-control form-control-lg',
                    'placeholder' => 'Enter you full name',
                ],
            ])
            ->add('email', EmailType::class, [
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'attr' => [
                    'class' => 'form-control form-control-lg',
                    'placeholder' => 'Enter you email',
                ],
            ])
            ->add('phone', TextType::class, [
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'attr' => [
                    'class' => 'form-control form-control-lg',
                    'placeholder' => 'Enter your phone',
                ],
            ])
            ->add('subject', TextType::class, [
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'attr' => [
                    'class' => 'form-control form-control-lg',
                    'placeholder' => 'Enter you subject',
                ],
            ])
            ->add('description', TextareaType::class, [
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'attr' => [
                    'class' => 'form-control form-control-lg',
                    'placeholder' => 'Leave a message here',
                    'rows' => '4',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
