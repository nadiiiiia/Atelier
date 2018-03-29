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
		
		
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
        $findEvents, 
        $request->query->getInt('page', 1)/*page number*/,
        3 /*limit per page*/
    );
	
        $events_json = $this->get('jms_serializer')->serialize($findEvents, 'json');

       return $this->render('event/accueil.html.twig', array('events' => $pagination, 'events_json'=>$events_json));
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
     
     $paginator  = $this->get('knp_paginator');
    $pagination = $paginator->paginate(
        $findEvents, 
        $request->query->getInt('page', 1)/*page number*/,
        3 /*limit per page*/
    );
    
    $events_json = $this->get('jms_serializer')->serialize($findEvents, 'json');
	
        switch ($type) {
            case "region":
                return $this->render('event/region.html.twig', array('events' => $pagination, 'events_json'=>$events_json));
                break;
            case "departement":
                return $this->render('event/departement.html.twig', array('events' => $pagination, 'events_json'=>$events_json));
                break;
            case "category":
                return $this->render('event/categorie.html.twig', array('events' => $pagination, 'events_json'=>$events_json));
                break;
        }
    }





    /**
     * @Route("/event_show/{id}", name="presentation")
     */
    public function presentationAction($id) {

        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository('AppBundle:Event')->find($id);
        
        $event_json = $this->get('jms_serializer')->serialize($event, 'json');

        return $this->render('event/presentation.html.twig', array('event' => $event, 'event_json'=>$event_json));
    }

}
