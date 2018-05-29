<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\User;
use AppBundle\Entity\Event;
use AppBundle\Entity\Order;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/orders")
 */
class OrdersController extends Controller {

    /**
     * @Route("/order/{amount}_{event}}" name="order_new")
     */
    public function orderAction($amount, $event) {
        $em = $this->getDoctrine()->getManager();

        $event = $em->getRepository('AppBundle:Event')->find($event);
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $order = new Order($amount, $user, $event);
        $em->persist($order);
        $em->flush();

        return $this->render('event/presentation.html.twig', array('event' => $event));
    }

}
