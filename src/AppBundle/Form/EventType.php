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
use Doctrine\ORM\EntityRepository;

// 1. Include Required Namespaces
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormEvents;
use Doctrine\ORM\EntityManagerInterface;
// Your Entity
use AppBundle\Entity\Departement;

class EventType extends AbstractType {
    
        private $em;
    
    /**
     * The Type requires the EntityManager as argument in the constructor. It is autowired
     * in Symfony 3.
     * 
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
             // 2. Remove the dependent select from the original buildForm as this will be
        // dinamically added later and the trigger as well
        $builder
//                ->add('departement', EntityType::class, array(
//                    'class' => 'AppBundle:Departement',
//                    'placeholder' => 'Choisir une classe',
//                    'choice_label' => 'nom',
//                    'attr' => ['class' => 'mdb-select select-dropdown']
//                ))
//                ->add('category', EntityType::class, array(
//                    // looks for choices from this entity
//                    'class' => 'AppBundle:Category',
//                    'empty_data' => 'Choisir une Catégorie',
//                    'required' => true,
//                    'placeholder' => 'Choisir une Catégorie',
//                    'choice_label' => 'nom',
//                    'attr' => ['class' => 'mdb-select select-dropdown',
//                        'id' => 'categoty']
//                        // used to render a select box, check boxes or radios
//                        // 'multiple' => true,
//                        // 'expanded' => true,
//                ))
                ->add('titre', TextType::class, array(
                    'attr' => array('class' => 'form-control'),
                    'label' => 'Titre'
                ))
                // ->add('description')
                ->add('description', TextareaType::class, array(
                    'attr' => array('class' => 'form-control md-textarea',),
                    'label' => 'Description'
                ))
                //     ->add('dateCreation')
                ->add('dateDebut', TextType::class, array(
                    'attr' => array('class' => 'form-control date-deb-fr floating-label'),
                    'label' => 'Date de début'
                ))
                ->add('dateFin', TextType::class, array(
                    'attr' => array('class' => 'form-control date-fin-fr floating-label'),
                    'label' => 'Date de fin'
                ))
                ->add('prix', NumberType::class, array(
                    'attr' => array('class' => 'form-control'),
                    'label' => 'Prix par utilisateur'
                ))
                ->add('nbrMax', NumberType::class, array(
                    'attr' => array('class' => 'form-control'),
                    'label' => 'Nombre max des participants'
                ))
                ->add('adresse', TextType::class, array(
                    'attr' => array('class' => 'form-control'),
                    'label' => 'Adresse'
                ))
                ->add('lng', HiddenType::class)
                ->add('lat', HiddenType::class)
                ->add('codeP', TextType::class, array(
                    'attr' => array('class' => 'form-control'),
                    'label' => 'Code Postal'
                ))

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
                ->add('images', FileType::class, array('attr' => array(
                        'accept' => 'image/*' // pour n'accepter que les images
                    ),
                    'multiple' => true,
                    'label' => 'Images de l\'événement'
        ));

                // 3. Add 2 event listeners for the form
        $builder->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'onPreSetData'));
        $builder->addEventListener(FormEvents::PRE_SUBMIT, array($this, 'onPreSubmit'));
    }

        protected function addElements(FormInterface $form, Departement $departement = null) {
        // 4. Add the province element
        $form->add('departement', EntityType::class, array(
            'required' => true,
           // 'data' => $departement,
            'placeholder' => 'Choisir une classe',
            'class' => 'AppBundle:Departement',
            'attr' => ['class' => 'mdb-select select-dropdown', 'onChange'=>'alert(this)']
        ));
        
        // Categories empty, unless there is a selected departement (Edit View)
        $categories = array();
        
        // If there is a department stored in the Event entity, load the categories of it
        if ($departement) {
            // Fetch Neighborhoods of the City if there's a selected city
            $repoCategory = $this->em->getRepository('AppBundle:Category');
            
            $categories = $repoCategory->createQueryBuilder("q")
                ->where("q.departement = :departementid")
                ->setParameter("departementid", $departement->getId())
                ->getQuery()
                ->getResult();
        }
        
        // Add the categories field with the properly data
        $form->add('category', EntityType::class, array(
            'required' => true,
            'placeholder' => 'Choisir une Classe au début',
            'class' => 'AppBundle:Category',
            'choices' => $categories,
             'attr' => ['class' => 'mdb-select select-dropdown ']
        ));
    }
    
        function onPreSubmit(FormEvent $event) {
        $form = $event->getForm();
        $data = $event->getData();
        
        // Search for selected Department and convert it into an Entity
        $departement = $this->em->getRepository('AppBundle:Departement')->find($data['departement']);
        
        $this->addElements($form, $departement);
    }
    
        function onPreSetData(FormEvent $event) {
        $atelier = $event->getData();
        $form = $event->getForm();

        // When you create a new event, the Department is always empty
        $departement = $atelier->getDepartement() ? $atelier->getDepartement() : null;
        
        $this->addElements($form, $departement);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Event'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return null;
    }

}
