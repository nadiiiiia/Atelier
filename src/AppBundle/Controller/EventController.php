<?php

namespace AppBundle\Controller;

//
//ini_set('memory_limit','-1');
//ini_set('max_execution_time','450');

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\DateTime;
use Doctrine\ORM\EntityManager;
use JMS\Payment\CoreBundle\Form\ChoosePaymentMethodType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\Payment\CoreBundle\PluginController\Result;
use JMS\Payment\CoreBundle\Plugin\Exception\Action\VisitUrl;
use JMS\Payment\CoreBundle\Plugin\Exception\ActionRequiredException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use AppBundle\Entity\Event;
use AppBundle\Entity\Order;
use ReCaptcha\ReCaptcha; // Include the recaptcha lib
use AppBundle\Entity\Image;
use AppBundle\Form\ImageType;
use AppBundle\Form\CertifType;
use AppBundle\Entity\Certif;

/**
 * Event controller.
 *
 * 
 */
class EventController extends Controller {

    const VALIDATION_EN_COURS = 0;
    const VALIDATION_VALIDATE = 1;
    const VALIDATION_NONVALIDER = 2;

    /**
     * @Route("/", name="homepage")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function eventsAction(Request $request) {
       

        $em = $this->getDoctrine()->getManager();
        $findEvents = $em->getRepository('AppBundle:Event')->findAllCurrent();

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $findEvents, $request->query->getInt('page', 1)/* page number */, 6 /* limit per page */
        );
        
        // $events_json = 0; //$this->get('jms_serializer')->serialize($findEvents, 'json');
        $filter_name = 'Tous les Ateliers';

        return $this->render('AppBundle:default:event/accueil.html.twig', array('events' => $pagination, 'filter_name' => $filter_name));
    }

    /**
     * @Route("filter/{type}/{value}", name="filterEvents")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function filterAction($type, $value, Request $request) { // exemple: type = category / value= 1 (informatique)
        //dump($type,$value);die;
        $em = $this->getDoctrine()->getManager();
        $findEvents = $em->getRepository('AppBundle:Event')->findBy([$type => $value]);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $findEvents, $request->query->getInt('page', 1)/* page number */, 6 /* limit per page */
        );



        switch ($type) {
            case "region":
                $filter_name = $findEvents[0]->getRegion();
                return $this->render('AppBundle:default:event/accueil.html.twig', array('events' => $pagination, 'filter_name' => $filter_name));
                break;
            case "departement":
                $departement = $em->getRepository('AppBundle:Departement')->find($value);
               
                $filter_name = $departement->getNom(); // pour extraire le nom de la categorie
                return $this->render('AppBundle:default:event/accueil.html.twig', array('events' => $pagination, 'filter_name' => $filter_name));
                break;
            case "category":
                // $uri = $request->getUri();
                $uri=$request->headers->get('referer');
             //   dump($uri);die;
                   $category = $em->getRepository('AppBundle:Category')->find($value);
            
                $filter_name = $category->getNom();
                return $this->render('AppBundle:default:event/accueil.html.twig', array('events' => $pagination, 'filter_name' => $filter_name));
                break;
        }
    }

    /**
     * @Route("/price_min", name="sortMin")
     * @param Request $request
     */
    public function sortMinAction(Request $request) {
        //dump($type,$value);die;
        $em = $this->getDoctrine()->getManager();
        $findEvents = $em->getRepository('AppBundle:Event')->sortByMinPrice();

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $findEvents, $request->query->getInt('page', 1)/* page number */, 6 /* limit per page */
        );

        $events_json = 0; //$this->get('jms_serializer')->serialize($findEvents, 'json');
        $filter_name = 'Tri par Moins cher';
        return $this->render('AppBundle:default:event/accueil.html.twig', array('events' => $pagination, 'filter_name' => $filter_name, 'events_json' => $events_json));
    }

    /**
     * @Route("/price_max", name="sortMax")
     * @param Request $request
     */
    public function sortMaxAction(Request $request) {
        //dump($type,$value);die;
        $em = $this->getDoctrine()->getManager();
        $findEvents = $em->getRepository('AppBundle:Event')->sortByMaxPrice();

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $findEvents, $request->query->getInt('page', 1)/* page number */, 6 /* limit per page */
        );

        $events_json = 0; //$this->get('jms_serializer')->serialize($findEvents, 'json');
        $filter_name = 'Tri par plus cher';
        return $this->render('AppBundle:default:event/accueil.html.twig', array('events' => $pagination, 'filter_name' => $filter_name, 'events_json' => $events_json));
    }

    /**
     * @Route("/max_participants", name="sortMaxParticipants")
     * @param Request $request
     */
    public function sortMaxParticipantsAction(Request $request) {
        //dump($type,$value);die;
        $em = $this->getDoctrine()->getManager();
        $findEvents = $em->getRepository('AppBundle:Event')->sortByParticipants();

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $findEvents, $request->query->getInt('page', 1)/* page number */, 6 /* limit per page */
        );

        $events_json = 0; //$this->get('jms_serializer')->serialize($findEvents, 'json');
        $filter_name = 'Tri par plus avancés';
        return $this->render('AppBundle:default:event/accueil.html.twig', array('events' => $pagination, 'filter_name' => $filter_name, 'events_json' => $events_json));
    }

    /**
     * @Route("/near_me_{lat}_{lng}", options={"expose"=true}, name="near_me")
     * @param Request $request
     */
    public function eventsNearMeAction($lat, $lng, Request $request) {

        $em = $this->getDoctrine()->getManager();
        $findEvents = $em->getRepository('AppBundle:Event')->sortByNearest($lat, $lng);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $findEvents, $request->query->getInt('page', 1)/* page number */, 6 /* limit per page */
        );

        $filter_name = 'Tri par plus proches';
        return $this->render('AppBundle:default:event/near_me.html.twig', array('events' => $pagination, 'filter_name' => $filter_name));
    }

    /**
     * @Route("/event_show/{id}", name="presentation")
     */
    public function presentationAction($id) {

        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository('AppBundle:Event')->find($id);


        $event_json = 0; //$this->get('jms_serializer')->serialize($event, 'json');

        return $this->render('AppBundle:default:event/presentation.html.twig', array('event' => $event, 'event_json' => $event_json));
    }

    /**
     * @Route("/recherche", name="event_recherche")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function rechercheAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $motcle = $request->get('motcle');
        $findEvents = $em->getRepository('AppBundle:Event')->findEventByTitle($motcle);


        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $findEvents, $request->query->getInt('page', 1)/* page number */, 6 /* limit per page */
        );

        $findEvents_json = 0; //$this->get('jms_serializer')->serialize($findEvents, 'json');
        $filter_name = 'Résultat de recherche .. "' . $motcle . '"';
        return $this->render('AppBundle:default:event/accueil.html.twig', array('events' => $pagination, 'filter_name' => $filter_name));
    }

    /**
     * Creates a new event entity.
     *
     * @Route("event/new", name="event_new")
     * @param Request $request
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request) {
        $categories = $this->listCategoriesOfDepartmentAction();
      //   dump($categories); die;
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $event = new Event();
        $form = $this->createForm('AppBundle\Form\EventType', $event);
        $form->handleRequest($request);
        $event->setUtilisateur($user);

        if ($form->isSubmitted() && $form->isValid()) {
            /*             * ********Traitement des images**************** */
            $files = $event->getImages();
            $img = array();
            foreach ($files as $file) {
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move($this->getParameter('image_directory'), $fileName);
                $img[] = $fileName;
            }
            $event->setImages($img);

            /*             * ********Traitement des dates**************** */

            // Récupérer les dates de type string 
            $form_date_deb = $form->get('dateDebut')->getData();
            $form_date_fin = $form->get('dateFin')->getData();
            // convertir string to objet DateTime
            $event->setDateDebut(new \DateTime($form_date_deb));
            $event->setDateFin(new \DateTime($form_date_fin));

            /*             * ******** Insertion dans la DB **************** */
            $em = $this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush();

            if ($user->getTel() == null || $user->getPhoto() == null || $user->getCin() == null) {
                return $this->redirectToRoute('org_info');
            } else {
                return $this->redirectToRoute('to_validate');
            }
        }


        return $this->render('AppBundle:default:event/new.html.twig', array(
                    'event' => $event,
                    'categories'=>$categories,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Creates a new event entity.
     *
     * @Route("event/org_info", name="org_info")
     * @param Request $request
     * @Method({"GET", "POST"})
     */
    public function orgInfoAction(Request $request) {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $tel = $user->getTel();
        $form = $this->createFormBuilder()
                ->add('tel', NumberType::class, array(
                    'attr' => array('class' => 'form-control', 'value' => $tel),
                    'invalid_message' => 'Le numéro de téléphone doit être au moins de %num% chiffres',
                    'invalid_message_parameters' => array('%num%' => 8),
                    'label' => 'Numéro de téléphone *'
                ))
                ->add('cin', ImageType::class, array('attr' => array(
                        'accept' => 'image/*' // pour n'accepter que les images
                    ),
                    'label' => 'Identifiant *'
                ))
                ->add('photo', FileType::class, array('attr' => array(
                        'accept' => 'image/*' // pour n'accepter que les images
                    ),
                    'label' => 'Photo de profil *'
                ))
                ->add('certifs', CollectionType::class, array(
                    'entry_type' => CertifType::class,
                    'label' => false,
                    // 'entry_options' => array('label' => false),
                    'allow_add' => true,
//                    'prototype' => true,
//                     'by_reference' => false,
                ))
                ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // data is an array with "name", "email", and "message" keys
            $data = $form->getData();
            $user->setTel($data['tel']);

            //la partie d'ajout de CIN
            if ($data['cin']->file != null) {
                $cin_titre = $data['cin']->titre;
                $cin_file = $data['cin']->file;
                $cinName = 'ID_' . md5(uniqid()) . '.' . $cin_file->guessExtension();
                $cin_file->move($this->getParameter('profile_directory'), $cinName);
                $image = new Image();

                $image->setTitre($cin_titre);
                $image->setPath($cinName);
                $user->setCin($image);
            }
            // fin ajout cin
            //la partie d'ajout de photo de profile
            if ($data['photo']) {
                $photo = $data['photo'];
                $photoName = 'avatar_' . md5(uniqid()) . '.' . $photo->guessExtension();
                $photo->move($this->getParameter('profile_directory'), $photoName);
                $user->setPhoto($photoName);
            }
            // fin ajout photo
            /*             * ********Traitement des certifs**************** */

            if ($data['certifs']) {
                $certifs = $data['certifs'];

                foreach ($certifs as $certif) {
                    if ($certif->file != null) {
                        $certif_titre = $certif->getTitre();
                        $certif_file = $certif->file;
                        $certifName = 'CERTIF_' . md5(uniqid()) . '.' . $certif_file->guessExtension();
                        $certif_file->move($this->getParameter('profile_directory'), $certifName);
                        $certif->setPath($certifName);

                        $user->addCertif($certif);
                    }
                }
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('to_validate');
        }

        return $this->render('AppBundle:default:event/org/org_info.html.twig', array(
                    'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/event/change_photo", name="change_photo")
     * @param Request $request
     */
    public function changePhotoAction(Request $request) {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $form = $this->createFormBuilder()
                ->add('photo', FileType::class, array('attr' => array(
                        'accept' => 'image/*' // pour n'accepter que les images
                    ),
                    'label' => 'Photo de profile *'
                ))
                ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $oldPhoto = $user->getPhoto();
            $path = 'uploads/images/' . $oldPhoto;

            $filesystem = new Filesystem();
            $filesystem->remove($path);
            //$user->setPhoto(null);

            $photo = $request->get('photo');
            dump($_FILES[$photo]['tmp_name']);
            die;
            $photoName = md5(uniqid()) . '.' . $photo->guessExtension();
            $photo->move($this->getParameter('image_directory'), $photoName);
            $user->setPhoto($photoName);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('fos_user_profile_show');
        }

        return $this->render('AppBundle:default:modals/photoModal.html.twig', array(
                    'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/event/to_validate", name="to_validate")
     * @param Request $request
     */
    public function toValidateAction() {
        return $this->render('AppBundle:default:event/to_validate.html.twig');
    }

    /**
     * @Route("/order/{amount}/{event}", name="order_new")
     * @param Request $request
     */
    public function orderAction($amount, $event, Request $request) {
        $em = $this->getDoctrine()->getManager();

        $event = $em->getRepository('AppBundle:Event')->find($event);
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $today = date("YmdHs");
        $rand = strtoupper(substr(uniqid(sha1(time())), 0, 4));
        $order_number = $today . $rand;

        $order = new Order();
        $order->create_order($order_number, $amount, $user, $event);
        $event->updateParticipants();
        $em->persist($order);
        $em->persist($event);
        $em->flush();

        return $this->redirectToRoute('order_show', array('id' => $order->getId()));
    }

    /**
     * @Route("/order_show/{id}", name="order_show")
     * 
     */
    public function showAction(Request $request, Order $order) {
        $config = [
            'paypal_express_checkout' => [
                'return_url' => $this->generateUrl('create_payment', [// si je change create_payment le payement ne marche pas
                    'id' => $order->getId(),
                        ], UrlGeneratorInterface::ABSOLUTE_URL),
                'checkout_params' => [
                    'PAYMENTREQUEST_n_DESC' => $order->getEvent()->getTitre(),
                ],
            ],
        ];
        $form = $this->createForm(ChoosePaymentMethodType::class, null, [
            'amount' => $order->getAmount(),
            'currency' => 'EUR',
            'default_method' => 'paypal_express_checkout',
            'method_options' => [
                'paypal_express_checkout' => [
                    'label' => false,
                ],
            ],
            'predefined_data' => $config,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ppc = $this->get('payment.plugin_controller');
            $ppc->createPaymentInstruction($instruction = $form->getData());

            $order->setPaymentInstruction($instruction);

            $em = $this->getDoctrine()->getManager();
            $em->persist($order);
            $em->flush($order);

            return $this->redirect($this->generateUrl('create_payment', [
                                'id' => $order->getId(),
            ]));
        }

        return $this->render('AppBundle:default:order/show.html.twig', array(
                    'order' => $order,
                    'form' => $form->createView(),
        ));
    }

    private function createPayment($order) {
        $instruction = $order->getPaymentInstruction();
        $pendingTransaction = $instruction->getPendingTransaction();

        if ($pendingTransaction !== null) {
            return $pendingTransaction->getPayment();
        }

        $ppc = $this->get('payment.plugin_controller');
        $amount = $instruction->getAmount() - $instruction->getDepositedAmount();

        return $ppc->createPayment($instruction->getId(), $amount);
    }

    /**
     * @Route("/create_payment/{id}", name="create_payment")
     */
    public function paymentCreateAction(Order $order) {
        $payment = $this->createPayment($order);

        $ppc = $this->get('payment.plugin_controller');
        $result = $ppc->approveAndDeposit($payment->getId(), $payment->getTargetAmount());

        if ($result->getStatus() === Result::STATUS_SUCCESS) {
            //   $this->addFlash('success', 'Inscription avec succès !'); // if rdierct presentation
            return $this->redirect($this->generateUrl('order_complete', [
                                'id' => $order->getId(),
                                'order' => $order,
                                    //'event'=>$order->getEvent(),
            ]));
        }

        if ($result->getStatus() === Result::STATUS_PENDING) {
            $ex = $result->getPluginException();

            if ($ex instanceof ActionRequiredException) {
                $action = $ex->getAction();

                if ($action instanceof VisitUrl) {
                    return $this->redirect($action->getUrl());
                }
            }
        }

        throw $result->getPluginException();
        // In a real-world application you wouldn't throw the exception. You would,
        // for example, redirect to the showAction with a flash message informing
        // the user that the payment was not successful.
        // $this->addFlash('failure', 'Erreur d\'inscription !!');
    }

    /**
     * Finish payment.
     *
     * @Route("/order_complete/{id}", name="order_complete")
     * 
     */
    public function orderCompleteAction($id) {

        $em = $this->getDoctrine()->getManager();
        $order = $em->getRepository('AppBundle:Order')->find($id);
        $user = $this->get('security.token_storage')->getToken()->getUser()->getEmailCanonical();
        $username = $this->get('security.token_storage')->getToken()->getUser()->getUsername();


        // ici le mail de confirmation
        $message = \Swift_Message::newInstance()
                ->setSubject('Validation du paiement')
                ->setFrom(array('equipelatelier@gmail.com' => 'L\'Atelier'))
                ->setTo($user)
                ->setCharset('utf-8')
                ->setContentType('text/html')
                ->setBody($this->renderView('AppBundle:default:swiftLayout/validate_payment.html.twig', array('order' => $order, 'user' => $username)));

        $this->get('mailer')->send($message);
        // Fin mail de confirmation

        return $this->render('AppBundle:default:order/complete.html.twig', array(
                    'order' => $order,
        ));
    }

    /**
     * Lists all event entities.
     *
     * @Route("/list/{orgId}", name="org_events_index")
     * @Method("GET")
     */
    public function listAction(Request $request, $orgId) {
        $em = $this->getDoctrine()->getManager();

        $events = $em->getRepository('AppBundle:Event')->findAllByOrg($orgId);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $events, $request->query->getInt('page', 1)/* page number */, 6 /* limit per page */
        );

        return $this->render('AppBundle:default:event/org/list.html.twig', array(
                    'events' => $pagination,
        ));
    }

    /**
     * Displays a form to edit an existing Events entity.
     *
     * @Route("event/edit/{id}", name="org_event_edit")
     * @param Request $request
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Event $event, $id) {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        //$deleteForm = $this->createDeleteForm($event);
        $editForm = $this->createForm('AppBundle\Form\EventType', $event);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {



            /*             * ********Traitement des dates**************** */
            // Récupérer les dates de type string 
            $form_date_deb = $editForm->get('dateDebut')->getData();
            $form_date_fin = $editForm->get('dateFin')->getData();
            // convertir string to objet DateTime
            $event->setDateDebut(new \DateTime($form_date_deb));
            $event->setDateFin(new \DateTime($form_date_fin));

            /*             * ********Traitement des images**************** */


            $uploaded = $editForm->get('images')->getData();
            if (!empty($uploaded)) {

                $files = $uploaded;
                $img = array();
                foreach ($files as $file) {
                    $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                    $file->move($this->getParameter('image_directory'), $fileName);
                    $img[] = $fileName;
                }
                $test = array('nas', 'hbr');
                //   dump($test); die;
                $event->setImages($img);
            } else {
//                dump($event);
//                die;
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush();

            return $this->redirectToRoute('org_events_index', array('orgId' => $user->getId()));
            // retour à la liste des evenements après les modifications 
        }

        /*         * ********Traitement des dates**************** */

        //  Récupérer les dates de type Objet et  convertir objet DateTime to string
        $form_date_deb = $event->getDateDebut()->format('Y-m-d H:i:s');
        $form_date_fin = $event->getDateFin()->format('Y-m-d H:i:s');


        $editForm->get('dateDebut')->setData($form_date_deb);
        $editForm->get('dateFin')->setData($form_date_fin);

        return $this->render('AppBundle:default:event/org/edit.html.twig', array(
                    'event' => $event,
                    'edit_form' => $editForm->createView(),
                        // 'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     *
     * @Route("test/edit_info", name="profile_edit_info")
     * @param Request $request
     * @Method({"GET", "POST"})
     */
    public function editProfileAction(Request $request) {
//dump($request);die;
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $tel = $user->getTel();
        $adresse = $user->getAdresse();
        $form = $this->createForm('AppBundle\Form\UserType', $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // data is an array with "name", "email", and "message" keys
            $data = $form->getData();
            //  dump($data); die;
            // Récupérer les dates de type string 
            $form_birthday = $form->get('date_naissance')->getData();
            // convertir string to objet DateTime
            $user->setDateNaissance(new \DateTime($form_birthday));

            $update = 0;
            //la partie d'ajout de CIN
            if ($data->getCin()->file != null) {
                $cin_titre = $data->getCin()->getTitre();
                $cin_file = $data->getCin()->file;
                //   dump($cin_file); die;
                $cinName = 'ID_' . md5(uniqid()) . '.' . $cin_file->guessExtension();
                $cin_file->move($this->getParameter('profile_directory'), $cinName);
                $image = new Image();

                $image->setTitre($cin_titre);
                $image->setPath($cinName);
                $user->setCin($image);
                $update++;
            }
            // fin ajout cin
            //la partie d'ajout de photo de profile
            if ($data->getPhoto()) {
                $photo = $data->getPhoto();
                $photoName = 'avatar_' . md5(uniqid()) . '.' . $photo->guessExtension();
                $photo->move($this->getParameter('profile_directory'), $photoName);
                $user->setPhoto($photoName);
            }
            // fin ajout photo
            /*             * ********Traitement des certifs**************** */
//            if ($data['certifs']) {
//                $certifs = $data['certifs'];
//                //dump($certifs); die;
//                $cert = array();
//                foreach ($certifs as $certif) {
//                    if ($certif->file != null) {
//                        $certif_titre = $certif->titre;
//                        $certif_file = $certif->file;
//                        $certifName = 'CERTIF_' . md5(uniqid()) . '.' . $certif_file->guessExtension();
//                        $certif_file->move($this->getParameter('profile_directory'), $certifName);
//                        $certif = new Certif();
//
//                        $certif->setTitre($certif_titre);
//                        $certif->setPath($certifName);
//                        $user->$getCertifs()->add($certif);
//                    }
//                }
//                $update++;
//            }
            if ($data->getCertifs()) {
                $certifs = $data->getCertifs();

//                foreach ($certifs as $certif) {
//                    $user->addCertif($certif);
//                }

                foreach ($certifs as $certif) {
                    if ($certif->file != null) {
                        $certif_titre = $certif->getTitre();
                        $certif_file = $certif->file;
                        $certifName = 'CERTIF_' . md5(uniqid()) . '.' . $certif_file->guessExtension();
                        $certif_file->move($this->getParameter('profile_directory'), $certifName);
                        $certif->setPath($certifName);

                        $user->addCertif($certif);
                    }
                }
                $update++;
            }


            if ($update != 0) {
                // si les certifs ou cin sont odifiés alors rendre les events refusés ==> en cours
                $userId = $user->getId();
                $this->resetValidationEvents($userId);
            }
            $this->getDoctrine()->getManager()->flush();


            return $this->redirectToRoute('profile_edit_info');
        }
        if ($user->getDateNaissance()) {
            $form_birthday = $user->getDateNaissance()->format('Y-m-d ');
            $form->get('date_naissance')->setData($form_birthday);
        }

        return $this->render('AppBundle:default:user/editDocs.html.twig', array(
                    'form' => $form->createView(),
        ));
    }

    /**
     *
     * @Route("/event/validation", name="validation")
     * @param Request $request
     * @Method({"GET", "POST"})
     */
    public function validationAction(Request $request) {

        $eventID = $request->get('eventId');
        $orgId = $request->get('orgId');
        $validation = $request->get('validation');
        $remarques = $request->get('remarques');

        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository('AppBundle:Event')->find($eventID);
        $user = $em->getRepository('AppBundle:User')->find($orgId);
        $event->setValidation($validation);
        $event->setNote($remarques);
        $em->persist($event);
        $em->flush();
        $userMail = $user->getEmail();

        if ($validation == self::VALIDATION_VALIDATE) {
            $message = \Swift_Message::newInstance()
                    ->setSubject('Etat de votre demande')
                    ->setFrom(array('equipelatelier@gmail.com' => 'L\'Atelier'))
                    ->setTo($userMail)
                    ->setCharset('utf-8')
                    ->setContentType('text/html')
                    ->setBody($this->renderView('AppBundle:default:swiftLayout/notification_valider.html.twig', array('event' => $event, 'user' => $user->getFirstName())));
        } else if ($validation == self::VALIDATION_NONVALIDER) {
            $message = \Swift_Message::newInstance()
                    ->setSubject('Etat de votre demande')
                    ->setFrom(array('equipelatelier@gmail.com' => 'L\'Atelier'))
                    ->setTo($userMail)
                    ->setCharset('utf-8')
                    ->setContentType('text/html')
                    ->setBody($this->renderView('AppBundle:default:swiftLayout/notification_refuser.html.twig', array('event' => $event, 'user' => $user->getFirstName())));
        }
        $this->get('mailer')->send($message);

        return $this->redirectToRoute('presentation', array(
                    'id' => $eventID,
        ));
    }

    /**
     * 
     * @Route("/mini_valid/{id}", name="mini_valid")
     * @param Request $request
     * 
     */
    public function miniValidAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository('AppBundle:Event')->find($id);
        $event->setValidation(self::VALIDATION_VALIDATE);
        $em->persist($event);
        $em->flush();
        return $this->redirectToRoute('presentation', array(
                    'id' => $id,
        ));
    }

    public function resetValidationEvents($userId) {
        $em = $this->getDoctrine()->getManager();
        $findEvents = $em->getRepository('AppBundle:Event')->findRefusedByUser($userId);

        foreach ($findEvents as $event) {
            $event->setValidation(self::VALIDATION_EN_COURS);
        }
    }
    
    
    /**
     * Returns a JSON string with the categories of the Class with the providen id.
     * 
     * @Route("/get-categ-from-department", name="event_list_categories")
     * @param Request $request
     * @Method({"GET"})
     * @return JsonResponse
     */
    public function listCategoriesOfDepartmentAction()
    {
        // Get Entity manager and repository
        $em = $this->getDoctrine()->getManager();
        $categRepository = $em->getRepository("AppBundle:Category");
        
        // Search the categories that belongs to the department with the given id as GET parameter "departementid"
        $categories = $categRepository->createQueryBuilder("q")
            //->where("q.departement = :departementid")
            //->setParameter("departementid", $request->query->get("departementid"))
            ->getQuery()
            ->getResult();
        
        // Serialize into an array the data that we need, in this case only name and id
        // Note: you can use a serializer as well, for explanation purposes, we'll do it manually
        $responseArray = array();
        foreach($categories as $category){
            $responseArray[] = array(
                "id" => $category->getId(),
                "name" => $category->getNom(),
                 "parent" => $category->getDepartement()->getId()  
            );
        }
        
        // Return array with structure of the neighborhoods of the providen city id
        return $responseArray;

        // e.g
        // [{"id":"3","name":"Treasure Island"},{"id":"4","name":"Presidio of San Francisco"}]
    }

    /**
     * 
     * @Route("/referrer", name="referrer")
     * @param Request $request
     * 
     */
    public function referrerAction(Request $request) {
        $url = $request->headers->get('referer');
//     
        if (!$url) {
            $url = $this->router->generate('homepage');
        }
        return $this->redirect($url);
    }

}
