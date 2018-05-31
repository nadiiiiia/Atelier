<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\DateTime;
use AppBundle\Entity\Event;
use AppBundle\Entity\Order;
use Doctrine\ORM\EntityManager;
use JMS\Payment\CoreBundle\Form\ChoosePaymentMethodType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\Payment\CoreBundle\PluginController\Result;
use JMS\Payment\CoreBundle\Plugin\Exception\Action\VisitUrl;
use JMS\Payment\CoreBundle\Plugin\Exception\ActionRequiredException;


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
        return $this->render('event/accueil.html.twig', array('events' => $pagination, 'filter_name' => $filter_name, 'events_json' => $events_json));
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
                return $this->render('event/accueil.html.twig', array('events' => $pagination, 'filter_name' => $filter_name, 'events_json' => $events_json));
                break;
            case "departement":
                $filter_name = $findEvents[0]->getDepartement();
                return $this->render('event/accueil.html.twig', array('events' => $pagination, 'filter_name' => $filter_name, 'events_json' => $events_json));
                break;
            case "category":
                $filter_name = $findEvents[0]->getCategory();
                return $this->render('event/accueil.html.twig', array('events' => $pagination, 'filter_name' => $filter_name, 'events_json' => $events_json));
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
        return $this->render('event/accueil.html.twig', array('events' => $pagination, 'filter_name' => $filter_name, 'events_json' => $events_json));
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
        return $this->render('event/accueil.html.twig', array('events' => $pagination, 'filter_name' => $filter_name, 'events_json' => $events_json));
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
        return $this->render('event/accueil.html.twig', array('events' => $pagination, 'filter_name' => $filter_name, 'events_json' => $events_json));
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
        return $this->render('event/accueil.html.twig', array('events' => $pagination, 'filter_name' => $filter_name, 'events_json' => $events_json));
    }

    /**
     * @Route("/event_show/{id}", name="presentation")
     */
    public function presentationAction($id) {

        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository('AppBundle:Event')->find($id);

        $event_json = 0; //$this->get('jms_serializer')->serialize($event, 'json');

        return $this->render('event/presentation.html.twig', array('event' => $event, 'event_json' => $event_json));
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
        return $this->render('event/accueil.html.twig', array('events' => $pagination, 'filter_name' => $filter_name));
    }

    /**
     * Creates a new event entity.
     *
     * @Route("event/new", name="event_new")
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

            return $this->redirectToRoute('homepage');
        }


        return $this->render('event/new.html.twig', array(
                    'event' => $event,
                    'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/order/{amount}_{event}", name="order_new")
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
        $this->addFlash('success', 'Genus created!');
        //  return $this->redirectToRoute('presentation', array('id' => $event->getId()));
        return $this->redirectToRoute('order_show', array('id' => $order->getId()));



        //return $this->redirect($request->getUri());
        // return $this->render('event/presentation.html.twig', array('event' => $event));
    }

    /**
     * @Route("/order_show/{id}", name="order_show")
     * 
     */
    public function showAction(Request $request, Order $order) {
        $form = $this->createForm(ChoosePaymentMethodType::class, null, [
            'amount' => $order->getAmount(),
            'currency' => 'EUR',
            'default_method'  => 'paypal_express_checkout',
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

        return $this->render('order/show.html.twig', array(
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
            return $this->redirect($this->generateUrl('app_orders_paymentcomplete', [
                                'id' => $order->getId(),
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
    }

}
