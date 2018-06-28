<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Event
 *
 * @ORM\Table(name="atl_event")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EventRepository")
 */
class Event {

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Assert\NotNull()
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="events", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @var string
     * @Assert\NotNull()
     * @ORM\Column(name="titre", type="string", length=255)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var \datetime
     *
     * @ORM\Column(name="date_creation", type="datetime", nullable=true)
     */
    private $dateCreation;

    /**
     * @var \datetime
     *
     * @ORM\Column(name="date_debut", type="datetime", nullable=true)
     */
    private $dateDebut;

    /**
     * @var \datetime
     *
     * @ORM\Column(name="date_fin", type="datetime", nullable=true)
     */
    private $dateFin;

    /**
     * @var float
     *
     * @ORM\Column(name="prix", type="float", length=45)
     */
    private $prix;

    /**
     * @var int
     *
     * @ORM\Column(name="nbr_max", type="integer")
     */
    private $nbrMax;

    /**
     * @var int
     *
     * @ORM\Column(name="nbr_participants", type="integer")
     */
    private $nbrParticipants;

    /**
     * @var float
     *
     * @ORM\Column(name="lng", type="float", length=45, options={"default" : 0})
     *
     */
    private $lng;

    /**
     * @var float
     *
     * @ORM\Column(name="lat", type="float", length=45, options={"default" : 0})
     * 
     */
    private $lat;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="events",  cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $utilisateur;

    /**
     * @ORM\OneToMany(targetEntity="Credit", mappedBy="event", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $orders;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255, nullable=true)
     */
    private $adresse;

    /**
     * @var string
     *
     * @ORM\Column(name="codeP", type="string", length=10, nullable=true)
     */
    private $codeP;

    /**
     * @ORM\ManyToOne(targetEntity="Ville", inversedBy="events", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $ville;

    /**
     * @ORM\ManyToOne(targetEntity="Region", inversedBy="events", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $region;

    /**
     * @ORM\ManyToOne(targetEntity="Departement", inversedBy="events", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $departement;

    /**
     * @var array
     *
     * @ORM\Column(name="images", type="array", nullable=true)
     */
    private $images;

    /**
     * @var int
     *
     * @ORM\Column(name="validation", type="integer",nullable=false, options={"default" : 0})
     */
    private $validation;
    
        /**
     * @var string
     *
     * @ORM\Column(name="note", type="text", nullable=true)
     */
    private $note;

    

    public function __construct() {

        $this->dateCreation = new \DateTime("now");  // get current date and time
        $this->credits = new ArrayCollection();
        $this->nbrParticipants = 0;
        $this->validation = 0;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set titre
     *
     * @param string $titre
     *
     * @return Event
     */
    public function setTitre($titre) {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre() {
        return $this->titre;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Event
     */
    public function setDescription($description) {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Set dateCreation
     *
     * @param \datetime $dateCreation
     *
     * @return Event
     */
    public function setDateCreation($dateCreation) {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * Get dateCreation
     *
     * @return \datetime
     */
    public function getDateCreation() {
        return $this->dateCreation;
    }

    /**
     * Set dateDebut
     *
     * @param \datetime $dateDebut
     *
     * @return Event
     */
    public function setDateDebut($dateDebut) {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    /**
     * Get dateDebut
     *
     * @return \datetime
     */
    public function getDateDebut() {
        return $this->dateDebut;
    }

    /**
     * Set dateFin
     *
     * @param \datetime $dateFin
     *
     * @return Event
     */
    public function setDateFin($dateFin) {
        $this->dateFin = $dateFin;

        return $this;
    }

    /**
     * Get dateFin
     *
     * @return \datetime
     */
    public function getDateFin() {
        return $this->dateFin;
    }

    /**
     * Set prix
     *
     * @param float $prix
     *
     * @return Event
     */
    public function setPrix($prix) {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix
     *
     * @return float
     */
    public function getPrix() {
        return $this->prix;
    }

    /**
     * Set nbrMax
     *
     * @param integer $nbrMax
     *
     * @return Event
     */
    public function setNbrMax($nbrMax) {
        $this->nbrMax = $nbrMax;

        return $this;
    }

    /**
     * Get nbrMax
     *
     * @return int
     */
    public function getNbrMax() {
        return $this->nbrMax;
    }

    /**
     * Set category
     *
     * @param \AppBundle\Entity\Category $category
     *
     * @return Event
     */
    public function setCategory(\AppBundle\Entity\Category $category) {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \AppBundle\Entity\Category
     */
    public function getCategory() {
        return $this->category;
    }

    /**
     * Add credit
     *
     * @param \AppBundle\Entity\Credit $credit
     *
     * @return Event
     */
    public function addCredit(\AppBundle\Entity\Credit $credit) {
        $this->credits[] = $credit;

        return $this;
    }

    /**
     * Remove credit
     *
     * @param \AppBundle\Entity\Credit $credit
     */
    public function removeCredit(\AppBundle\Entity\Credit $credit) {
        $this->credits->removeElement($credit);
    }

    /**
     * Get credits
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCredits() {
        return $this->credits;
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     *
     * @return Event
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
     * Set codeP
     *
     * @param string $codeP
     *
     * @return Event
     */
    public function setCodeP($codeP) {
        $this->codeP = $codeP;

        return $this;
    }

    /**
     * Get codeP
     *
     * @return string
     */
    public function getCodeP() {
        return $this->codeP;
    }

    /**
     * Set ville
     *
     * @param \AppBundle\Entity\Ville $ville
     *
     * @return Event
     */
    public function setVille(\AppBundle\Entity\Ville $ville) {
        $this->ville = $ville;

        return $this;
    }

    /**
     * Get ville
     *
     * @return \AppBundle\Entity\Ville
     */
    public function getVille() {
        return $this->ville;
    }

    /**
     * Set region
     *
     * @param \AppBundle\Entity\Region $region
     *
     * @return Event
     */
    public function setRegion(\AppBundle\Entity\Region $region) {
        $this->region = $region;

        return $this;
    }

    /**
     * Get region
     *
     * @return \AppBundle\Entity\Region
     */
    public function getRegion() {
        return $this->region;
    }

    /**
     * Set departement
     *
     * @param \AppBundle\Entity\Departement $departement
     *
     * @return Event
     */
    public function setDepartement(\AppBundle\Entity\Departement $departement) {
        $this->departement = $departement;

        return $this;
    }

    /**
     * Get departement
     *
     * @return \AppBundle\Entity\Departement
     */
    public function getDepartement() {
        return $this->departement;
    }

    /**
     * Set utilisateur
     *
     * @param \AppBundle\Entity\User $utilisateur
     *
     * @return Event
     */
    public function setUtilisateur(\AppBundle\Entity\User $utilisateur = null) {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    /**
     * Get utilisateur
     *
     * @return \AppBundle\Entity\User
     */
    public function getUtilisateur() {
        return $this->utilisateur;
    }

    /**
     * Set nbrParticipants
     *
     * @param integer $nbrParticipants
     *
     * @return Event
     */
    public function setNbrParticipants($nbrParticipants) {
        $this->nbrParticipants = $nbrParticipants;

        return $this;
    }

    /**
     * Get nbrParticipants
     *
     * @return integer
     */
    public function getNbrParticipants() {
        return $this->nbrParticipants;
    }

    /**
     * Update participants
     *
     * @return Event
     */
    public function updateParticipants() {
        $nbr = $this->nbrParticipants;
        $this->nbrParticipants = $nbr + 1;
        
        return $this;
    }

    /**
     * Set lng
     *
     * @param float $lng
     *
     * @return Event
     */
    public function setLng($lng) {
        $this->lng = $lng;

        return $this;
    }

    /**
     * Get lng
     *
     * @return float
     */
    public function getLng() {
        return $this->lng;
    }

    /**
     * Set lat
     *
     * @param float $lat
     *
     * @return Event
     */
    public function setLat($lat) {
        $this->lat = $lat;

        return $this;
    }

    /**
     * Get lat
     *
     * @return float
     */
    public function getLat() {
        return $this->lat;
    }

    /**
     * Set images
     *
     * @param array $images
     *
     * @return Event
     */
    public function setImages($images) {
        $this->images = $images;

        return $this;
    }

    /**
     * Get images
     *
     * @return array
     */
    public function getImages() {
        return $this->images;
    }


    /**
     * Set validation
     *
     * @param int $validation
     *
     * @return Event
     */
    public function setValidation($validation)
    {
        $this->validation = $validation;

        return $this;
    }

    /**
     * Get validation
     *
     * @return int
     */
    public function getValidation()
    {
        return $this->validation;
    }

    /**
     * Add order
     *
     * @param \AppBundle\Entity\Credit $order
     *
     * @return Event
     */
    public function addOrder(\AppBundle\Entity\Credit $order)
    {
        $this->orders[] = $order;

        return $this;
    }

    /**
     * Remove order
     *
     * @param \AppBundle\Entity\Credit $order
     */
    public function removeOrder(\AppBundle\Entity\Credit $order)
    {
        $this->orders->removeElement($order);
    }

    /**
     * Get orders
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     * Set note
     *
     * @param string $note
     *
     * @return Event
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }
}
