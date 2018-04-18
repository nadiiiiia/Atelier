<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Event;

class EventUserController extends Controller {

    /**
     * @Route("/", name="homepage")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function eventsAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $findEvents = $em->getRepository('AppBundle:Event')->findAll();


        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $findEvents, $request->query->getInt('page', 1)/* page number */, 3 /* limit per page */
        );

        $events_json = $this->get('jms_serializer')->serialize($findEvents, 'json');

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
                $findEvents, $request->query->getInt('page', 1)/* page number */, 3 /* limit per page */
        );


        $events_json = $this->get('jms_serializer')->serialize($findEvents, 'json');

        switch ($type) {
            case "region":
                return $this->render('event/region.html.twig', array('events' => $pagination, 'events_json' => $events_json));
                break;
            case "departement":
                return $this->render('event/departement.html.twig', array('events' => $pagination, 'events_json' => $events_json));
                break;
            case "category":
                return $this->render('event/categorie.html.twig', array('events' => $pagination, 'events_json' => $events_json));
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

        $events_json = $this->get('jms_serializer')->serialize($findEvents, 'json');
        return $this->render('event/categorie.html.twig', array('events' => $pagination, 'events_json' => $events_json));
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
                $findEvents, $request->query->getInt('page', 1)/* page number */, 3 /* limit per page */
        );

        $events_json = $this->get('jms_serializer')->serialize($findEvents, 'json');
        return $this->render('event/categorie.html.twig', array('events' => $pagination, 'events_json' => $events_json));
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
                $findEvents, $request->query->getInt('page', 1)/* page number */, 3 /* limit per page */
        );

        $events_json = $this->get('jms_serializer')->serialize($findEvents, 'json');
        return $this->render('event/categorie.html.twig', array('events' => $pagination, 'events_json' => $events_json));
    }

    /**
     * @Route("/near_me", name="near_me")
     * @param Request $request
     */
    public function eventsNearMe(Request $request) {
//        $lng = $request->request->get('lng');
//        $lat= $request->request->get('lat');
        $lng = 2.408875899999998;
        $lat = 48.76254099999999;
        //dump($type,$value);die;
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
                  'SELECT id, titre'
               // . ', ( 3959 * acos( cos( radians('.$lat.') ) * cos( radians( lat ) ) * cos( radians( lng ) - '
              //  . 'radians('.$lng.') ) + sin( radians('.$lat.') ) * sin( radians( lat ) ) ) ) AS distance '
                . 'FROM atl_event '
              //  . 'HAVING distance < 25 ORDER BY distance LIMIT 0 , 20'
      
                );

        $findEvents = $query->getResult();
        var_dump($findEvents); die;
       
       // $findEvents = $em->getRepository('AppBundle:Event')->sortByNearest($lng, $lat);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $findEvents, $request->query->getInt('page', 1)/* page number */, 3 /* limit per page */
        );

        $events_json = $this->get('jms_serializer')->serialize($findEvents, 'json');
        return $this->render('event/categorie.html.twig', array('events' => $pagination, 'events_json' => $events_json));
    }

    /**
     * @Route("/event_show/{id}", name="presentation")
     */
    public function presentationAction($id) {

        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository('AppBundle:Event')->find($id);

        $event_json = $this->get('jms_serializer')->serialize($event, 'json');

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
                $findEvents, $request->query->getInt('page', 1)/* page number */, 3 /* limit per page */
        );

        $findEvents_json = $this->get('jms_serializer')->serialize($findEvents, 'json');

        return $this->render('event/accueil.html.twig', array('events' => $pagination, 'findEvents_json' => $findEvents_json));
    }

}
