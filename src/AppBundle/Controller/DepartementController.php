<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DepartementController extends Controller
{

          public function menuAction()
    {
        $em = $this->getDoctrine()->getManager();

        $departements = $em->getRepository('AppBundle:Departement')->findAll();

        return $this->render('AppBundle:default:departement/menu.html.twig', array(
            'departements' => $departements,
        ));
    }
}
