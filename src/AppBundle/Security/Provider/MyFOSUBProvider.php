<?php

namespace AppBundle\Security\Provider;

use Doctrine\Bundle\DoctrineBundle\Registry;
use FOS\UserBundle\Model\UserManagerInterface;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider;
use AppBundle\Entity\User;

class MyFOSUBProvider extends FOSUBUserProvider
{
    private $doctrine;

    /**
     * @param UserManagerInterface $userManager
     * @param array $properties
     * @param Registry $doctrine
     */
    public function __construct(UserManagerInterface $userManager, array $properties, $doctrine)
    {
        parent::__construct($userManager, $properties);

        $this->doctrine = $doctrine;
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $username = $response->getUsername();
        $property = $this->getProperty($response);

        $user = $this->userManager->findUserBy(array($this->getProperty($response) => $username));

        $email = $response->getEmail();
        // check if we already have this user
        $existing = $this->userManager->findUserBy(array('email' => $email));
        if ($existing instanceof User) {
            // in case of Facebook login, update the facebook_id
            if ($property == "facebookId") {
                $existing->setFacebookId($username);
            }
            // in case of Google login, update the google_id
            if ($property == "googleId") {
                $existing->setGoogleId($username);
            }
            // in case of Twitter login, update the Twitter_id
            if ($property == "twitterId") {
                $existing->setTwitterId($username);
            }
            $this->userManager->updateUser($existing);

            return $existing;
        }

        // if we don't know the user, create it
        if (null === $user || null === $username ) {
            /** @var User $user */
            $user = $this->userManager->createUser();
            $nick = $username;
            $user->setFirstname($response->getFirstName());
            $user->setLastname($response->getLastName());
            $user->setLastLogin(new \DateTime());
            $user->setEnabled(true);

            $user->setUsername($nick);
            $user->setUsernameCanonical($nick);
            $user->setPassword(sha1(uniqid()));
            $user->addRole('ROLE_USER');

            if ($property == "facebookId") {
                $user->setFacebookId($username);
            }
            if ($property == "googleId") {
                $user->setGoogleId($username);
            }
            if ($property == "twitterId") {
                $user->setTwitterId($username);
            }
        }

        $user->setEmail($response->getEmail());
        $user->setFirstname($response->getFirstName());
        $user->setLastname($response->getLastName());
        //$user->setPhoto($response->getProfilePicture());
        

        $this->userManager->updateUser($user);

        return $user;
    }
}