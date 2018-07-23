<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\User;

class UserType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
            $builder->add('first_name', TextType::class, array(
                    'attr' => array('class' => 'form-control'),
                    'label' => 'Prénom'
                ))
                ->add('last_name', TextType::class, array(
                    'attr' => array('class' => 'form-control'),
                    'label' => 'Nom'
                ))
                ->add('date_naissance', TextType::class, array(
                    'attr' => array('class' => 'form-control birth-day-fr floating-label'),
                    'label' => 'Date de naissance'
                ))
                ->add('tel', NumberType ::class, array(
                    'attr' => array('class' => 'form-control',
                        'minlength' => '8'
                    ),
                    'invalid_message' => 'Le numéro de téléphone doit être au moins de %num% chiffres',
                    'invalid_message_parameters' => array('%num%' => 8),
                    'label' => 'Numéro de téléphone '
                ))
                ->add('cin', ImageType::class, array('attr' => array(
                        'accept' => 'image/*' // pour n'accepter que les images
                    ),
                    'label' => 'Identifiant '
                ))
                ->add('photo', FileType::class, array(
                'data_class' => null,
                'attr' => array(
                    
                        'accept' => 'image/*' // pour n'accepter que les images
                    ),
                    'label' => 'Photo de profile '
                ))
                ->add('certifs', CollectionType::class, array(
                    'entry_type' => CertifType::class,
                    'label' => false,
                   // 'entry_options' => array('label' => false),
                    'allow_add' => true,
//                    'prototype' => true,
//                     'by_reference' => false,
                ))
                ->add('adresse', TextType::class, array(
                    'attr' => array('class' => 'form-control'),
                    'label' => 'Adresse'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'user';
    }

}
