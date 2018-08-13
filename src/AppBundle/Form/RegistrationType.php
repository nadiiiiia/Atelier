<?php

// src/AppBundle/Form/RegistrationType.php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;


class RegistrationType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder
//                ->add('first_name', TextType::class, array(
//                    'attr' => array('class' => 'form-control'),
//                    'label' => 'Prénom'
//                ))
//                ->add('last_name', TextType::class, array(
//                    'attr' => array('class' => 'form-control'),
//                    'label' => 'Nom'
//                ))
//                ->add('date_naissance', TextType::class, array(
//                    'attr' => array('class' => 'form-control birth-day-fr floating-label'),
//                    'label' => 'Date de naissance'
//                ))
                ->add('first_name', HiddenType::class)
                ->add('last_name', HiddenType::class)
                ->add('date_naissance', HiddenType::class)
                ->add('roles', CollectionType::class, array(
                    'entry_type' => ChoiceType::class,
                    'entry_options' => array(
                        'attr' => array('class' => 'mdb-select select-dropdown'),
                        'choices' => array(
                            'Organisateur' => 'ROLE_ORGANIZER',
                            'Simple Utilisateur' => 'ROLE_USER',
                        ),
                    ),
        ))
                
               /* ->add('agree', CheckboxType::class, array(
    'label'    => 'Show this entry publicly?',
    'required' => TRUE,
))*/
                ;
    }

    public function getParent() {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    public function getName() {
        return 'app_user_registration';
    }

}
