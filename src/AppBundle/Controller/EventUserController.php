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

       return $this->render('event/accueil.html.twig', array('events' => $pagination));
    }

    /**
     * @Route("/categories/{categorie}", name="categoryEvents")
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
     */
    public function categoryAction($categorie, Request $request) {
        $em = $this->getDoctrine()->getManager();
        $findEvents = $em->getRepository('AppBundle:Event')->byCategorie($categorie);
		 
		 $paginator  = $this->get('knp_paginator');
    $pagination = $paginator->paginate(
        $findEvents, 
        $request->query->getInt('page', 1)/*page number*/,
        6 /*limit per page*/
    );


        $categorie = $em->getRepository('AppBundle:Category')->find($categorie);
        if (!$categorie)
            throw $this->createNotFoundException('La page n\'existe pas');
        $categoryName = $categorie->getNom(); // pour extraire le nom de la categorie

        return $this->render('event/categorie.html.twig', array('categoryName' => $categoryName, 'events' => $pagination));
    }

    /**
     * @Route("/departements/{departement}", name="departementEvents")
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
     */
    public function departementAction($departement, Request $request) {
        $em = $this->getDoctrine()->getManager();
        $findEvents = $em->getRepository('AppBundle:Event')->byDepartement($departement);

		 $paginator  = $this->get('knp_paginator');
    $pagination = $paginator->paginate(
        $findEvents, 
        $request->query->getInt('page', 1)/*page number*/,
        6 /*limit per page*/
    );
	
        $departement = $em->getRepository('AppBundle:Departement')->find($departement);
        if (!$departement)
            throw $this->createNotFoundException('La page n\'existe pas');
        $departementName = $departement->getNom(); // pour extraire le nom de la categorie

        return $this->render('event/departement.html.twig', array('departementName' => $departementName, 'events' => $pagination));
    }

    /**
     * @Route("/regions/{region}", name="regionEvents")
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
     */
    public function regionAction($region, Request $request) {
        $em = $this->getDoctrine()->getManager();
        $findEvents = $em->getRepository('AppBundle:Event')->byRegion($region);

				 $paginator  = $this->get('knp_paginator');
    $pagination = $paginator->paginate(
        $findEvents, 
        $request->query->getInt('page', 1)/*page number*/,
        6 /*limit per page*/
    );

        $region = $em->getRepository('AppBundle:Region')->find($region);
        if (!$region)
            throw $this->createNotFoundException('La page n\'existe pas');
        $regionName = $region->getNom(); // pour extraire le nom de la categorie

        return $this->render('event/region.html.twig', array('regionName' => $regionName, 'events' => $pagination));
    }

    /**
     * @Route("/event/{id}", name="presentation")
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
