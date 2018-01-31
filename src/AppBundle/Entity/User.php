<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="atl_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $facebookID;
    
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $googleID;
    
    /**
     * @ORM\Column(type="string",nullable=true)
     */
    protected $twitterID;
    
    /**
     *
     * @ORM\OneToMany(targetEntity="Event",mappedBy="utilisateur", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $events;

    public function __construct()
    {
        parent::__construct();
        $this->events = new ArrayCollection();
    }

    /**
     * Add event
     *
     * @param \AppBundle\Entity\Event $event
     *
     * @return User
     */
    public function addEvent(\AppBundle\Entity\Event $event)
    {
        $this->events[] = $event;

        return $this;
    }

    /**
     * Remove event
     *
     * @param \AppBundle\Entity\Event $event
     */
    public function removeEvent(\AppBundle\Entity\Event $event)
    {
        $this->events->removeElement($event);
    }

    /**
     * Get events
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * Set facebookID
     *
     * @param string $facebookID
     *
     * @return User
     */
    public function setFacebookID($facebookID)
    {
        $this->facebookID = $facebookID;

        return $this;
    }

    /**
     * Get facebookID
     *
     * @return string
     */
    public function getFacebookID()
    {
        return $this->facebookID;
    }

    /**
     * Set googleID
     *
     * @param string $googleID
     *
     * @return User
     */
    public function setGoogleID($googleID)
    {
        $this->googleID = $googleID;

        return $this;
    }

    /**
     * Get googleID
     *
     * @return string
     */
    public function getGoogleID()
    {
        return $this->googleID;
    }

    /**
     * Set twitterID
     *
     * @param string $twitterID
     *
     * @return User
     */
    public function setTwitterID($twitterID)
    {
        $this->twitterID = $twitterID;

        return $this;
    }

    /**
     * Get twitterID
     *
     * @return string
     */
    public function getTwitterID()
    {
        return $this->twitterID;
    }
}
