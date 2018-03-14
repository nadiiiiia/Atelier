<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Event;

class EventType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
  public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('category', EntityType::class, array(
                    // looks for choices from this entity
                    'class' => 'AppBundle:Category',
                    'placeholder' => 'Choisir une Catégorie',
                    'choice_label' => 'nom',
                    'attr' => ['class' => 'mdb-select select-dropdown']
                        // used to render a select box, check boxes or radios
                        // 'multiple' => true,
                        // 'expanded' => true,
                ))
                ->add('titre')
                // ->add('description')
                ->add('description', TextareaType::class, array(
                    'attr' => array('class' => 'form-control md-textarea')))
               //     ->add('dateCreation')
                ->add('dateDebut', TextType::class, array(
                    'attr' => array('placeholder' => 'jj/mm/aaaa', 'required' => 'false')))
                ->add('dateFin', TextType::class, array(
                    'attr' => array('placeholder' => 'jj/mm/aaaa', 'required' => 'false')))
                ->add('heureDeb', TextType::class, array(
                    'attr' => array('placeholder' => 'hh:mm', 'required' => 'false')))
                ->add('heureFin', TextType::class, array(
                    'attr' => array('placeholder' => 'hh:mm', 'required' => 'false')))
                ->add('prix')
                ->add('nbrMax')
                ->add('adresse')
                ->add('codeP')

                // ->add('organisateur', EntityType::class, array('class' => 'AppBundle:Organisateur'))
                ->add('ville', EntityType::class, array(
                    'class' => 'AppBundle:Ville',
                    'placeholder' => 'Choisir une ville',
                    'choice_label' => 'nom',
                    'attr' => ['class' => 'mdb-select select-dropdown']
                ))
                   ->add('region', EntityType::class, array(
                    'class' => 'AppBundle:Region',
                    'placeholder' => 'Choisir une Région',
                    'choice_label' => 'nom',
                    'attr' => ['class' => 'mdb-select select-dropdown']
                ))
                   ->add('departement', EntityType::class, array(
                    'class' => 'AppBundle:Departement',
                    'placeholder' => 'Choisir un departement',
                    'choice_label' => 'nom',
                    'attr' => ['class' => 'mdb-select select-dropdown']
                ))
              
                      ->add('image', EntityType::class, array(
                    'class' => 'AppBundle:Image',
                    'placeholder' => 'Choisir une image',
                    'choice_label' => 'titre',
                    'attr' => ['class' => 'mdb-select select-dropdown', 'required' => 'false']
                )) ;
    }
	
	/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Event'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_event';
    }


}
