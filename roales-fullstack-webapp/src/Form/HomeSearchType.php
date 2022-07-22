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

use App\Entity\Location;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @access public
 *
 * @version 0.1
 */
class HomeSearchType extends AbstractType
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
            ->add('city', SearchType::class, [
                'attr' => [
                    "class" => "form-control",
                    "dir" => "ltr",
                    "spellcheck" => false,
                    "autocorrect" => "off",
                    "autocomplete" => "off",
                    "autocapitalize" => "off",
                    "maxlength" => "2048",
                    "tabindex" => "1",
                ],
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
            'data_class' => Location::class,
            'validation_groups' => ['home_search'],
        ]);
    }
}
