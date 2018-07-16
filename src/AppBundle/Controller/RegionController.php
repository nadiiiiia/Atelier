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

        return $this->render('AppBundle:default:region/menu.html.twig', array(
            'regions' => $regions,
            'regions_json' => $regions_json
        ));
    }
}
