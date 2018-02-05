<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Event;

class EventController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function eventsAction()
    {
         $em=$this->getDoctrine()->getManager();
         $findEvents=$em->getRepository('AppBundle:Event')->findAll();
            
     //  return $this->render('event/accueil.html.twig', array('events'=>new Response(json_encode($findEvents))));
        return $this->render('event/accueil.html.twig', array('events'=>$findEvents));
    }   
    
    /**
     * @Route("/categories/{categorie}", name="categoryEvents")
     */
     public function categoryAction($categorie)
                     
     {                   
         $em=$this->getDoctrine()->getManager();
         $findEvents=$em->getRepository('AppBundle:Event')->byCategorie($categorie);
         
             
           $categorie=$em->getRepository('AppBundle:Category')->find($categorie);
           if(!$categorie) throw $this->createNotFoundException ('La page n\'existe pas');
           $categoryName = $categorie->getNom(); // pour extraire le nom de la categorie
           
            return $this->render('event/categorie.html.twig', 
                    array('categoryName'=> $categoryName,'events'=>$findEvents));
            
    }
        /**
     * @Route("/departements/{departement}", name="departementEvents")
     */
     public function departementAction($departement)
                     
     {                   
         $em=$this->getDoctrine()->getManager();
         $findEvents=$em->getRepository('AppBundle:Event')->byDepartement($departement);
         
             
           $departement=$em->getRepository('AppBundle:Departement')->find($departement);
           if(!$departement) throw $this->createNotFoundException ('La page n\'existe pas');
           $departementName = $departement->getNom(); // pour extraire le nom de la categorie
           
            return $this->render('event/departement.html.twig', 
                    array('departementName'=> $departementName,'events'=>$findEvents));
            
    }
    
            /**
     * @Route("/regions/{region}", name="regionEvents")
     */
     public function regionAction($region)
                     
     {                   
         $em=$this->getDoctrine()->getManager();
         $findEvents=$em->getRepository('AppBundle:Event')->byRegion($region);
         
             
           $region=$em->getRepository('AppBundle:Region')->find($region);
           if(!$region) throw $this->createNotFoundException ('La page n\'existe pas');
           $regionName = $region->getNom(); // pour extraire le nom de la categorie
           
            return $this->render('event/region.html.twig', 
                    array('regionName'=> $regionName,'events'=>$findEvents));
            
    }
    
    /**
     * @Route("/event/{id}", name="presentation")
     */
        public function presentationAction($id)
    {
             
         $em=$this->getDoctrine()->getManager();
         $event=$em->getRepository('AppBundle:Event')->find($id);
         //$arr = array('event'=>$event['data']);
         //$test= new JsonResponse($arr);
       //var_dump($test);
         
        
       return $this->render('event/presentation.html.twig', array('event'=>$event));
       
       
    }
    
    
    
}
