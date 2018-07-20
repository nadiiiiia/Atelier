<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use AppBundle\Entity\Certif;

class CertifType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('titre')
                ->add('file', FileType::class, array('attr' => array(
                    'data_class' => null,
                        'accept' => 'image/*' // pour n'accepter que les images
                    ),
        ))
        ;
    }

/**
     * {@inheritdoc}
     */

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Certif'
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'certif';
    }

}
