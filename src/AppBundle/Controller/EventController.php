<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class EventController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function eventsAction()
    {
        $em=$this->getDoctrine()->getManager();
         $findEvents=$em->getRepository('AppBundle:Event')->findAll();
            
        return $this->render('event/accueil.html.twig', array('events'=>$findEvents));
    }
   
    
    /**
     * @Route("/categories/{categorie}", name="categoryEvents")
     */
     public function categoryAction($categorie)
                     
     {                   
         $em=$this->getDoctrine()->getManager();
         $findEvents=$em->getRepository('AppBundle:Event')->byCategorie($categorie);
         
             
           $categorie=$em->getRepository('AppBundle:Categories')->find($categorie);
           if(!$categorie) throw $this->createNotFoundException ('La page n\'existe pas');
           $categoryName = $categorie->getNom(); // pour extraire le nom de la categorie
           
            return $this->render('events/user/categorie.html.twig', 
                    array('categoryName'=> $categoryName,'events'=>$findEvents));
    }
    
    
}
