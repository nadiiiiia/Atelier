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

    /**
     * @Route("/json", name="events_json")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function eventsJsonAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $findEvents = $em->getRepository('AppBundle:Event')->findAllCurrent();

        $all_events = array();
        foreach ($findEvents as $event) {
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
        $all_events;


        return new JsonResponse($all_events);
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
            $classe_array[] = $classe->getNom();
        }
        foreach ($categories as $category) {
            $category_array[] = $category->getNom();
        }
        foreach ($regions as $region) {
            $regions_array[] = $region->getNom();
        }
        
         $navbar_array = array(
            'classes' => $classe_array,
            'categories' => $category_array,
             'regions' => $regions_array,
        );
          return new JsonResponse($navbar_array);
    }

}
