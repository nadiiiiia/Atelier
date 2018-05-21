<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Event;
use Doctrine\ORM\EntityManager;

class EventUserController extends Controller {

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

        return $this->render('event/accueil.html.twig', array('events' => $pagination, 'events_json' => $events_json));
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
                return $this->render('event/recherche.html.twig', array('events' => $pagination, 'filter_name' => $filter_name, 'events_json' => $events_json));
                break;
            case "departement":
                $filter_name = $findEvents[0]->getDepartement();
                return $this->render('event/recherche.html.twig', array('events' => $pagination, 'filter_name' => $filter_name, 'events_json' => $events_json));
                break;
            case "category":
                $filter_name = $findEvents[0]->getCategory();
                return $this->render('event/recherche.html.twig', array('events' => $pagination, 'filter_name' => $filter_name, 'events_json' => $events_json));
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
                $findEvents, $request->query->getInt('page', 1)/* page number */, 3 /* limit per page */
        );

        $events_json = 0; //$this->get('jms_serializer')->serialize($findEvents, 'json');
        $filter_name = 'Tri par Moins cher';
        return $this->render('event/recherche.html.twig', array('events' => $pagination, 'filter_name' => $filter_name, 'events_json' => $events_json));
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
        return $this->render('event/recherche.html.twig', array('events' => $pagination, 'filter_name' => $filter_name, 'events_json' => $events_json));
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
        return $this->render('event/recherche.html.twig', array('events' => $pagination, 'filter_name' => $filter_name, 'events_json' => $events_json));
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
        return $this->render('event/recherche.html.twig', array('events' => $pagination, 'filter_name' => $filter_name, 'events_json' => $events_json));
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
        return $this->render('event/recherche.html.twig', array('events' => $pagination, 'filter_name' => $filter_name));
    }

}
