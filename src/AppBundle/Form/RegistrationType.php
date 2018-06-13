<?php

// src/AppBundle/Form/RegistrationType.php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class RegistrationType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder->add('first_name', TextType::class, array(
                    'attr' => array('class' => 'form-control'),
                    'label' => 'Prénom'
                ))
                ->add('last_name', TextType::class, array(
                    'attr' => array('class' => 'form-control'),
                    'label' => 'Nom'
                ))
                ->add('photo', FileType::class, array('attr' => array(
                        'accept' => 'image/*', // pour n'accepter que les images
                    ),
                    'label' => 'Photo de profile '
                ))
                ->add('roles', CollectionType::class, array(
                    'entry_type' => ChoiceType::class,
                    'entry_options' => array(
                        'attr' => array('class' => 'mdb-select select-dropdown'),
                        'choices' => array(
                            'Organisateur' => 'ROLE_ORGANIZER',
                            'Simple Utilisateur' => 'ROLE_USER',
                        ),
                    ),
        ));
    }

    public function getParent() {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    public function getName() {
        return 'app_user_registration';
    }

}
