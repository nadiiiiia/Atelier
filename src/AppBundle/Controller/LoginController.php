<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use ReCaptcha\ReCaptcha; // Include the recaptcha lib

class LoginController extends Controller {

    /**
     * @Route("/checkLogin", name="checkLogin")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loginAction(Request $request) {
        $key = $this->container->getParameter('captcha_server_key');

        $recaptcha = new ReCaptcha($key);
        $resp = $recaptcha->verify($request->request->get('g-recaptcha-response'), $request->getClientIp());

        if (!$resp->isSuccess()) {
            // Do something if the submit wasn't valid ! Use the message to show something
            $message = "Le reCAPTCHA n'a pas été entré correctement. Essayez à nouveau.";
            $this->addFlash(
                    'error', $message
            );

            return $this->redirectToRoute('fos_user_security_login');
        } else {

            // This data is most likely to be retrieven from the Request object (from Form)
            // But to make it easy to understand ...
            // dump($request);die;
            $_username = $request->get('_username');
            $_password = $request->get('_password');

            // Retrieve the security encoder of symfony
            $factory = $this->get('security.encoder_factory');

            /// Start retrieve user
            // Let's retrieve the user by its username:
            // If you are using FOSUserBundle:
            $user_manager = $this->get('fos_user.user_manager');
            $user = $user_manager->findUserByUsername($_username);
            // Or by yourself
//        $user = $this->getDoctrine()->getManager()->getRepository("userBundle:User")
//                ->findOneBy(array('username' => $_username));
            /// End Retrieve user
            // Check if the user exists !
            if (!$user) {
                $request->getSession()
                        ->getFlashBag()
                        ->add('login_error', 'Nom d\'utilisateur n\'existe pas');

                $url = $this->generateUrl('fos_user_security_login');

                return $this->redirect($url);
            }

            /// Start verification
            $encoder = $factory->getEncoder($user);
            $salt = $user->getSalt();

            if (!$encoder->isPasswordValid($user->getPassword(), $_password, $salt)) {
                $request->getSession()
                        ->getFlashBag()
                        ->add('login_error', 'Identifiants invalides.');

                $url = $this->generateUrl('fos_user_security_login');

                return $this->redirect($url);
            }
            /// End Verification
            // The password matches ! then proceed to set the user in session
            //Handle getting or creating the user entity likely with a posted form
            // The third parameter "main" can change according to the name of your firewall in security.yml
            $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
            $this->get('security.token_storage')->setToken($token);

            // If the firewall name is not main, then the set value would be instead:
            // $this->get('session')->set('_security_XXXFIREWALLNAMEXXX', serialize($token));
            $this->get('session')->set('_security_main', serialize($token));

            // Fire the login event manually
            $event = new InteractiveLoginEvent($request, $token);
            $this->get("event_dispatcher")->dispatch("security.interactive_login", $event);

            /*
             * Now the user is authenticated !!!! 
             * Do what you need to do now, like render a view, redirect to route etc.
             */
//   
            // redirect the user to where they were before the login process begun.
            $referer_url = $request->get('_target_path');
            if (!$referer_url) {
                $referer_url = $this->generateUrl('homepage');
            }

            $response = new RedirectResponse($referer_url);


            return $response;
        }
    }

}
