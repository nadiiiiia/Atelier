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
        6 /*limit per page*/
    );
	
    //$test = $this->get('jms_serializer')->serialize($pagination, 'json');
	//dump($test); die;

       return $this->render('event/accueil.html.twig', array('events' => $pagination));
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
        6 /*limit per page*/
    );
	
        switch ($type) {
            case "region":
                return $this->render('event/region.html.twig', array('events' => $pagination));
                break;
            case "departement":
                return $this->render('event/departement.html.twig', array('events' => $pagination));
                break;
            case "category":
                return $this->render('event/categorie.html.twig', array('events' => $pagination));
                break;
        }
    }





    /**
     * @Route("/event_show/{id}", name="presentation")
     */
    public function presentationAction($id) {

        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository('AppBundle:Event')->find($id);
        //$arr = array('event'=>$event['data']);
        //$test= new JsonResponse($arr);
        //var_dump($test);


        return $this->render('event/presentation.html.twig', array('event' => $event));
    }

}
