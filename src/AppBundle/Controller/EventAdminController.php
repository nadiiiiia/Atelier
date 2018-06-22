<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use AppBundle\Entity\Event;

/**
 * Event Admin controller.
 *
 * @Route("/admin/event")
 */
class EventAdminController extends Controller {

    /**
     * Lists all event entities.
     *
     * @Route("/", name="admin_events_index")
     * @Method("GET")
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $events = $em->getRepository('AppBundle:Event')->findAdminAllCurrent();

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $events, $request->query->getInt('page', 1)/* page number */, 6 /* limit per page */
        );

        return $this->render('AppBundle:default:event/admin/index.html.twig', array(
                    'events' => $pagination,
        ));
    }

    
    /**
     * Creates a new event entity.
     *
     * @Route("/new", name="event_admin_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request) {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $event = new Event();
        $form = $this->createForm('AppBundle\Form\EventType', $event);
        $form->handleRequest($request);
        $event->setUtilisateur($user);

        if ($form->isSubmitted() && $form->isValid()) {
            /*             * ********Traitement des images**************** */
            $files = $event->getImages();
            $img = array();
            foreach ($files as $file) {
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move($this->getParameter('image_directory'), $fileName);
                $img[] = $fileName;
            }
            $event->setImages($img);

            /*             * ********Traitement des dates**************** */

            // Récupérer les dates de type string 
            $form_date_deb = $form->get('dateDebut')->getData();
            $form_date_fin = $form->get('dateFin')->getData();
            // convertir string to objet DateTime
            $event->setDateDebut(new \DateTime($form_date_deb));
            $event->setDateFin(new \DateTime($form_date_fin));

            /*             * ******** Insertion dans la DB **************** */

            $em = $this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }


        return $this->render('AppBundle:default:event/new.html.twig', array(
                    'event' => $event,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Validate an event .
     *
     * @Route("/valider/{id}", name="valider_event")
     * @Method("GET")
     */
    public function validateAction(Request $request, $id) {


        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository('AppBundle:Event')->find($id);

        $event->setValidation(1);
        $em->persist($event);
        $em->flush();

        $url = $request->headers->get('referer');
//     
        if (!$url) {
            $url = $this->router->generate('homepage');
        }
           return $this->redirect($url);
    }

    /**
     * @Route("/non_valide", name="nonValide")
     * @param Request $request
     */
    public function nonValideEvents(Request $request) {
        //dump($type,$value);die;
        $em = $this->getDoctrine()->getManager();
        $events = $em->getRepository('AppBundle:Event')->findByNonValide();


        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $events, $request->query->getInt('page', 1)/* page number */, 6 /* limit per page */
        );

        $list_name = 'Ateliers non validés';

        return $this->render('AppBundle:default:event/admin/index.html.twig', array(
                    'events' => $pagination,
        ));



        //   return $this->render('AppBundle:default:event/admin/index.html.twig', array('events' => $pagination, 'titre'=> $list_name));
    }

    /**
     * Finds and displays a event entity.
     *
     * @Route("/{id}", name="event_show")
     * @Method("GET")
     */
    public function showAction(Event $event) {
        $deleteForm = $this->createDeleteForm($event);

        return $this->render('AppBundle:default:event/show.html.twig', array(
                    'event' => $event,
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing event entity.
     *
     * @Route("/{id}/edit", name="admin_event_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Event $event) {
        $deleteForm = $this->createDeleteForm($event);
        $editForm = $this->createForm('AppBundle\Form\EventType', $event);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $files = $event->getImages();
            $img = array();
            foreach ($files as $file) {
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move($this->getParameter('image_directory'), $fileName);
                $img[] = $fileName;
            }
            $event->setImages($img);

            $em = $this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('AppBundle:default:event/edit.html.twig', array(
                    'event' => $event,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a event entity.
     *
     * @Route("/{id}", name="event_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Event $event) {
        $form = $this->createDeleteForm($event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($event);
            $em->flush();
        }

        return $this->redirectToRoute('event_index');
    }

    /**
     * Creates a form to delete a event entity.
     *
     * @param Event $event The event entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Event $event) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('event_delete', array('id' => $event->getId())))
                        ->setMethod('DELETE')
                        ->getForm()
        ;
    }

}
