<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class RegionController extends Controller
{

          public function menuAction()
    {
        $em = $this->getDoctrine()->getManager();

        $regions = $em->getRepository('AppBundle:Region')->findAll();
        $regions_json = 0; //$this->get('jms_serializer')->serialize($regions, 'json');

        return $this->render('region/menu.html.twig', array(
            'regions' => $regions,
            'regions_json' => $regions_json
        ));
    }
}
