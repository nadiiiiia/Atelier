<?php

// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="atl_user")
 */
class User extends BaseUser {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * 
     * @Assert\NotBlank()
     * @ORM\Column(type="string", nullable=true)
     */
    protected $first_name;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", nullable=true)
     */
    protected $last_name;

    /**
     * @ORM\Column(type="string", name="facebook_id", nullable=true)
     */
    protected $facebookId;

    /**
     * @ORM\Column(type="string", name="google_id", nullable=true)
     */
    protected $googleId;

    /**
     * @ORM\Column(type="string", name="twitter_id", nullable=true)
     */
    protected $twitterId;

    /**
     * @ORM\Column(type="string", name="tel", nullable=true)
     */
    protected $tel;

    /**
     * @var \datetime
     *
     * @ORM\Column(name="date_naissance", type="datetime", nullable=true)
     */
    protected $date_naissance;

    /**
     * @var Image
     * Assert\Valid()
     * Assert\Type(type="AppBundle\Entity\Image")
     * --liaison unidirectionelle entre user et Image
     * @ORM\OneToOne(targetEntity="Image", cascade={"persist"})
     */
    protected $cin;

    /**
     * @ORM\Column(type="string", name="photo", nullable=true)
     */
    protected $photo;

    /**
     * @ORM\OneToMany(targetEntity="Certif", mappedBy="user", cascade={"persist", "remove"})
     */
    protected $certifs;

    /**
     * @ORM\Column(type="string", name="adresse", nullable=true)
     */
    protected $adresse;

    /**
     *
     * @ORM\OneToMany(targetEntity="Event",mappedBy="utilisateur", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $events;

    public function __construct() {
        parent::__construct();
        $this->events = new ArrayCollection();
        $this->photo = 'login-avatar.jpg';
        $this->certifs = new ArrayCollection();
    }

    /**
     * Add event
     *
     * @param \AppBundle\Entity\Event $event
     *
     * @return User
     */
    public function addEvent(\AppBundle\Entity\Event $event) {
        $this->events[] = $event;

        return $this;
    }

    /**
     * Remove event
     *
     * @param \AppBundle\Entity\Event $event
     */
    public function removeEvent(\AppBundle\Entity\Event $event) {
        $this->events->removeElement($event);
    }

    /**
     * Get events
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEvents() {
        return $this->events;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName($firstName) {
        $this->first_name = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName() {
        return $this->first_name;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName($lastName) {
        $this->last_name = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName() {
        return $this->last_name;
    }

    /**
     * @return mixed
     */
    public function getFacebookId() {
        return $this->facebookId;
    }

    /**
     * @param mixed $facebookId
     */
    public function setFacebookId($facebookId) {
        $this->facebookId = $facebookId;
    }

    /**
     * @return mixed
     */
    public function getGoogleId() {
        return $this->googleId;
    }

    /**
     * @param mixed $googleId
     */
    public function setGoogleId($googleId) {
        $this->googleId = $googleId;
    }

    /**
     * @return mixed
     */
    public function getTwitterId() {
        return $this->twitterId;
    }

    /**
     * @param mixed $twitterId
     */
    public function setTwitterId($twitterId) {
        $this->twitterId = $twitterId;
    }

    /**
     * Set tel
     *
     * @param string $tel
     *
     * @return User
     */
    public function setTel($tel) {
        $this->tel = $tel;

        return $this;
    }

    /**
     * Get tel
     *
     * @return string
     */
    public function getTel() {
        return $this->tel;
    }

    /**
     * Set photo
     *
     * @param string $photo
     *
     * @return User
     */
    public function setPhoto($photo) {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return string
     */
    public function getPhoto() {
        return $this->photo;
    }


    /**
     * Set dateNaissance
     *
     * @param \DateTime $dateNaissance
     *
     * @return User
     */
    public function setDateNaissance($dateNaissance) {
        $this->date_naissance = $dateNaissance;

        return $this;
    }

    /**
     * Get dateNaissance
     *
     * @return \DateTime
     */
    public function getDateNaissance() {
        return $this->date_naissance;
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     *
     * @return User
     */
    public function setAdresse($adresse) {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string
     */
    public function getAdresse() {
        return $this->adresse;
    }

    /**
     * Set cin
     *
     * @param \AppBundle\Entity\Image $cin
     *
     * @return User
     */
    public function setCin(\AppBundle\Entity\Image $cin = null) {
        $this->cin = $cin;

        return $this;
    }

    /**
     * Get cin
     *
     * @return \AppBundle\Entity\Image
     */
    public function getCin() {
        return $this->cin;
    }


    /**
     * Add certif
     *
     * @param \AppBundle\Entity\Certif $certif
     *
     * @return User
     */
    public function addCertif(\AppBundle\Entity\Certif $certif)
    {
        $this->certifs[] = $certif;
        $certif->setUser($this);

        return $this;
    }

    /**
     * Remove certif
     *
     * @param \AppBundle\Entity\Certif $certif
     */
    public function removeCertif(\AppBundle\Entity\Certif $certif)
    {
        $this->certifs->removeElement($certif);
    }

    /**
     * Get certifs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCertifs()
    {
        return $this->certifs;
    }
}
