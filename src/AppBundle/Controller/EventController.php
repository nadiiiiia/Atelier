<?php

namespace AppBundle\Controller;

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
use AppBundle\Entity\Event;
use AppBundle\Entity\Order;

/**
 * Event controller.
 *
 * 
 */
class EventController extends Controller {

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

        $events_json = 0; //$this->get('jms_serializer')->serialize($findEvents, 'json');
        $filter_name = 'Tous les Ateliers';
        return $this->render('AppBundle:default:event/accueil.html.twig', array('events' => $pagination, 'filter_name' => $filter_name, 'events_json' => $events_json));
    }

    /**
     * @Route("tri/{type}/{value}", name="triEvents")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function triAction($type, $value, Request $request) {
        //dump($type,$value);die;
        $em = $this->getDoctrine()->getManager();
        $findEvents = $em->getRepository('AppBundle:Event')->findBy([$type => $value]);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $findEvents, $request->query->getInt('page', 1)/* page number */, 6 /* limit per page */
        );


        $events_json = 0; //$this->get('jms_serializer')->serialize($findEvents, 'json');


        switch ($type) {
            case "region":
                $filter_name = $findEvents[0]->getRegion();
                return $this->render('AppBundle:default:event/accueil.html.twig', array('events' => $pagination, 'filter_name' => $filter_name, 'events_json' => $events_json));
                break;
            case "departement":
                $filter_name = $findEvents[0]->getDepartement();
                return $this->render('AppBundle:default:event/accueil.html.twig', array('events' => $pagination, 'filter_name' => $filter_name, 'events_json' => $events_json));
                break;
            case "category":
                $filter_name = $findEvents[0]->getCategory();
                return $this->render('AppBundle:default:event/accueil.html.twig', array('events' => $pagination, 'filter_name' => $filter_name, 'events_json' => $events_json));
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
    public function sortMaxParticipants(Request $request) {
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
    public function eventsNearMe($lat, $lng, Request $request) {

        $em = $this->getDoctrine()->getManager();
        $findEvents = $em->getRepository('AppBundle:Event')->sortByNearest($lat, $lng);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $findEvents, $request->query->getInt('page', 1)/* page number */, 6 /* limit per page */
        );

        $events_json = 0; //$this->get('jms_serializer')->serialize($findEvents, 'json');
        $filter_name = 'Tri par plus proches';
        return $this->render('AppBundle:default:event/accueil.html.twig', array('events' => $pagination, 'filter_name' => $filter_name, 'events_json' => $events_json));
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
        $form = $this->createFormBuilder()
                ->add('tel', NumberType::class, array(
                    'attr' => array('class' => 'form-control'),
                    'label' => 'Numéro de téléphone *'
                ))
                ->add('cin', FileType::class, array('attr' => array(
                        'accept' => 'image/*' // pour n'accepter que les images
                    ),
                    'label' => 'Identifiant *'
                ))
                ->add('photo', FileType::class, array('attr' => array(
                        'accept' => 'image/*' // pour n'accepter que les images
                    ),
                    'label' => 'Photo de profile *'
                ))
                ->add('certifs', FileType::class, array('attr' => array(
                        'accept' => 'image/*' // pour n'accepter que les images
                    ),
                    'multiple' => TRUE,
                    'label' => 'Certificats'
                ))
                ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // data is an array with "name", "email", and "message" keys
            $data = $form->getData();
            $user->setTel($data['tel']);

            //la partie d'ajout de CIN
            $cin = $data['cin'];
            $cinName = 'ID_' . md5(uniqid()) . '.' . $cin->guessExtension();
            //dump($cin); die;
            $cin->move($this->getParameter('profile_directory'), $cinName);
            $user->setCin($cinName);
            // fin ajout cin
            //la partie d'ajout de photo de profile
            $photo = $data['photo'];
            $photoName = 'avatar_'. md5(uniqid()) . '.' . $photo->guessExtension();
            $photo->move($this->getParameter('profile_directory'), $photoName);
            $user->setPhoto($photoName);
            // fin ajout photo
            /*             * ********Traitement des certifs**************** */
            if ($data['certifs']) {
                $certifs = $data['certifs'];
                $cert = array();
                foreach ($certifs as $certif) {
                    $certifName = 'certif_' . md5(uniqid()) . '.' . $certif->guessExtension();
                    $certif->move($this->getParameter('profile_directory'), $certifName);
                    $cert[] = $certifName;
                }
                $user->setCertifs($cert);
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('to_validate');
        }

        return $this->render('AppBundle:default:event/org_info.html.twig', array(
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
        dump($_FILES[$photo]['tmp_name']); die;
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
