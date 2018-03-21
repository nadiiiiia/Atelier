<?php
// src/AppBundle/Form/RegistrationType.php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('organizer', ChoiceType::class, array(
                    'attr' => array('class' => 'mdb-select select-dropdown'),
					'placeholder' => 'Choisissez votre type',
					 'choices' => array(
                                        'Organisateur' => 1,
                                        'Simple Utilisateur' => 0,
                                     ),
					));
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    public function getName()
    {
        return 'app_user_registration';
    }
}