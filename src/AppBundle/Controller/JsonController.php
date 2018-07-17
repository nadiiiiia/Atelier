<?php

namespace AppBundle\Controller;

//
//ini_set('memory_limit','-1');
//ini_set('max_execution_time','450');

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Event controller.
 *
 * 
 */
class JsonController extends Controller {

    public function fetchArrayAction($events) {
        $all_events = array();
        foreach ($events as $event) {
            $event_array = array();
            $event_array['id'] = $event->getId();

            $event_array['category'] = $event->getCategory()->getNom();
            $event_array['titre'] = $event->getTitre();
            $event_array['desc'] = $event->getDescription();
            $event_array['dateDeb'] = $event->getDateDebut()->format('d/m/Y');
            $event_array['heureDeb'] = $event->getDateDebut()->format('H:i');
            $event_array['dateFin'] = $event->getDateFin()->format('d/m/Y');
            $event_array['heureFin'] = $event->getDateFin()->format('H:i');
            $event_array['prix'] = $event->getPrix();
            $event_array['nbrMax'] = $event->getNbrMax();
            $event_array['nbrParticipants'] = $event->getNbrParticipants();
            $event_array['dispo'] = $event_array['nbrMax'] - $event_array['nbrParticipants'];
            $event_array['adresse'] = $event->getAdresse();
            $event_array['codeP'] = $event->getCodeP();
            $event_array['lng'] = $event->getLng();
            $event_array['lat'] = $event->getLat();
            $event_array['ville'] = $event->getVille()->getNom();
            $event_array['region'] = $event->getRegion()->getNom();
            $event_array['classe'] = $event->getDepartement()->getNom();
            $event_array['validation'] = $event->getValidation();
            $event_array['note'] = $event->getNote();
            $event_array['organizer'] = $event->getUtilisateur()->getFirstName() . ' ' . $event->getUtilisateur()->getLastName();

            $images = array();
            foreach ($event->getImages() as $image) {
                $images [] = '/images/' . $image;
            }
            $event_array['images'] = $images;

            $all_events ["event_" . $event->getId()] = $event_array;
        }
        return $all_events;
    }

    /**
     * @Route("/json", name="events_json")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function eventsJsonAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $findEvents = $em->getRepository('AppBundle:Event')->findAllCurrent();

        $events = $this->fetchArrayAction($findEvents);
        return new JsonResponse($events);
    }

    /**
     * @Route("/json_navbar", name="json_navbar")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function navbarJsonAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $classes = $em->getRepository('AppBundle:Departement')->findAll();
        $categories = $em->getRepository('AppBundle:Category')->findAll();
        $regions = $em->getRepository('AppBundle:Region')->findAll();

        $classe_array = array();
        $category_array = array();
        $regions_array = array();


        foreach ($classes as $classe) {
            $classe_array[$classe->getId()] = $classe->getNom();
        }
        foreach ($categories as $category) {
            $category_array[$category->getId()] = $category->getNom();
        }
        foreach ($regions as $region) {
            $regions_array[$region->getId()] = $region->getNom();
        }
        $navbar_1 = array('titre' => 'classes', 'elements' => $classe_array);
        $navbar_2 = array('titre' => 'catégories', 'elements' => $category_array);
        $navbar_3 = array('titre' => 'regions', 'elements' => $regions_array);

        $navbar_array = array(
            'nav_1' => $navbar_1,
            'nav_2' => $navbar_2,
            'nav_3' => $navbar_3,
        );
        return new JsonResponse($navbar_array);
    }

    /**
     * @Route("filter_json/{type}/{value}", name="filterJsonEvents")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function filterJsonAction($type, $value, Request $request) { // exemple: type = category / value= 1 (informatique)
        //dump($type,$value);die;
        if ($type == 'classes') {
            $type = 'departement';
        }
        if ($type == 'catégories') {
            $type = 'category';
        }
        if ($type == 'regions') {
            $type = 'region';
        }
        $em = $this->getDoctrine()->getManager();
        $findEvents = $em->getRepository('AppBundle:Event')->findBy([$type => $value]);

        $events = $this->fetchArrayAction($findEvents);
        return new JsonResponse($events);
    }

    /**
     * @Route("/json_price_min", name="json_sortMin")
     * @param Request $request
     */
    public function sortMinJsonAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $findEvents = $em->getRepository('AppBundle:Event')->sortByMinPrice();

        $events = $this->fetchArrayAction($findEvents);
        return new JsonResponse($events);
    }

    /**
     * @Route("/json_price_max", name="json_sortMax")
     * @param Request $request
     */
    public function sortMaxJsonAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $findEvents = $em->getRepository('AppBundle:Event')->sortByMaxPrice();

        $events = $this->fetchArrayAction($findEvents);
        return new JsonResponse($events);
    }

    /**
     * @Route("/json_max_participants", name="sortMaxParticipants_json")
     * @param Request $request
     */
    public function sortMaxParticipantsjsonAction(Request $request) {
        //dump($type,$value);die;
        $em = $this->getDoctrine()->getManager();
        $findEvents = $em->getRepository('AppBundle:Event')->sortByParticipants();

        $events = $this->fetchArrayAction($findEvents);
        return new JsonResponse($events);
    }

    /**
     * @Route("/json_near_me_{lat}_{lng}", options={"expose"=true}, name="json_near_me")
     * @param Request $request
     */
    public function eventsNearMeAction($lat, $lng, Request $request) {

        $em = $this->getDoctrine()->getManager();
        $findEvents = $em->getRepository('AppBundle:Event')->sortByNearest($lat, $lng);

        $all_events = array();
        foreach ($findEvents as $event_array) {
            $event = $event_array[0];
            $distance = $event_array['distance'];

            $event_array = array();

            $event_array['id'] = $event->getId();
            $event_array['category'] = $event->getCategory()->getNom();
            $event_array['titre'] = $event->getTitre();
            $event_array['desc'] = $event->getDescription();
            $event_array['dateDeb'] = $event->getDateDebut()->format('d/m/Y');
            $event_array['heureDeb'] = $event->getDateDebut()->format('H:i');
            $event_array['dateFin'] = $event->getDateFin()->format('d/m/Y');
            $event_array['heureFin'] = $event->getDateFin()->format('H:i');
            $event_array['prix'] = $event->getPrix();
            $event_array['nbrMax'] = $event->getNbrMax();
            $event_array['nbrParticipants'] = $event->getNbrParticipants();
            $event_array['dispo'] = $event_array['nbrMax'] - $event_array['nbrParticipants'];
            $event_array['adresse'] = $event->getAdresse();
            $event_array['codeP'] = $event->getCodeP();
            $event_array['lng'] = $event->getLng();
            $event_array['lat'] = $event->getLat();
            $event_array['ville'] = $event->getVille()->getNom();
            $event_array['region'] = $event->getRegion()->getNom();
            $event_array['classe'] = $event->getDepartement()->getNom();
            $event_array['validation'] = $event->getValidation();
            $event_array['note'] = $event->getNote();
            $event_array['organizer'] = $event->getUtilisateur()->getFirstName() . ' ' . $event->getUtilisateur()->getLastName();
            $event_array['distance'] = $distance;
            $images = array();
            foreach ($event->getImages() as $image) {
                $images [] = '/images/' . $image;
            }
            $event_array['images'] = $images;

            $all_events ["event_" . $event->getId()] = $event_array;
        }
        return new JsonResponse($all_events);
    }

    /**
     * @Route("/json_recherche/{motcle}", name="json_event_recherche")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function rechercheAction($motcle, Request $request) {
        $em = $this->getDoctrine()->getManager();
        //dump($motcle);die;
        $findEvents = $em->getRepository('AppBundle:Event')->findEventByTitle($motcle);

        $events = $this->fetchArrayAction($findEvents);
        return new JsonResponse($events);
    }

    /**
     * @Route("/json_login", name="json_login")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loginReactAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $login = $request->get('username');
        $pass = $request->get('password');
        dump($login);
        dump($pass);
        die();
        $events = $this->fetchArrayAction($findEvents);
        return new JsonResponse($events);
    }

    /**
     * @Route("/csrf", name="json_login")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function genetateCsrf(Request $request) {
        /** @var \Symfony\Component\Security\Csrf\CsrfTokenManagerInterface $csrf */
        $csrf = $this->get('security.csrf.token_manager');
        $intention = '_token';
        $token = $csrf->refreshToken($intention);
  //      dump($token->getValue());die;

        return new JsonResponse($token->getValue());
    }

}
