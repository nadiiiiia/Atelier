<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends Controller
{
   
     public function menuAction()
    {
        $em = $this->getDoctrine()->getManager();

        $categories = $em->getRepository('AppBundle:Category')->findAll();
        $categories_json = 0; //$this->get('jms_serializer')->serialize($categories, 'json');

        return $this->render('category/menu.html.twig', array(
            'categories' => $categories,
             'categories_json' => $categories_json
        ));
    }
    
    
}
