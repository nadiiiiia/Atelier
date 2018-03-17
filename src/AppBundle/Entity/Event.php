<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Asserts;

/**
 * Event
 *
 * @ORM\Table(name="atl_event")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EventRepository")
 */
class Event
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
	    
    /**
     *
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="events", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @var string
     *
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
     * @var \string
     *
     * @ORM\Column(name="date_creation", type="text", nullable=true)
     */
    private $dateCreation;

    /**
     * @var \string
     *
     * @ORM\Column(name="date_debut", type="string", nullable=true)
     */
    private $dateDebut;

    /**
     * @var \string
     *
     * @ORM\Column(name="date_fin", type="string", nullable=true)
     */
    private $dateFin;
    
    /**
     * @var \string
     *
     * @ORM\Column(name="heure_debut", type="string", nullable=true)
     */
    private $heureDeb;

    /**
     * @var \string
     *
     * @ORM\Column(name="heure_fin", type="string", nullable=true)
     */
    private $heureFin;

    /**
     * @var string
     *
     * @ORM\Column(name="prix", type="string", length=45)
     */
    private $prix;

    /**
     * @var int
     *
     * @ORM\Column(name="nbr_max", type="integer")
     */
    private $nbrMax;

    
    /**
     *
     * @ORM\ManyToOne(targetEntity="Organisateur",inversedBy="events", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $organisateur;
    
      
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="events")
     * @ORM\JoinColumn(nullable=true)
     */
    
    private $utilisateur;
    
    /**
     * @ORM\OneToMany(targetEntity="Credit", mappedBy="event", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $credits;
    
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
     * @var string
     * @ORM\Column(name="image", type="string", length=255)
     */
    private $image;
    
    
     public function __construct()
    {
       
      //  $this->dateCreation = new \DateTime("now");  // get current date and time
        $this->credits = new ArrayCollection();
      
       
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set titre
     *
     * @param string $titre
     *
     * @return Event
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Event
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set dateCreation
     *
     * @param \string $dateCreation
     *
     * @return Event
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * Get dateCreation
     *
     * @return \string
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    /**
     * Set dateDebut
     *
     * @param \string $dateDebut
     *
     * @return Event
     */
    public function setDateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    /**
     * Get dateDebut
     *
     * @return \string
     */
    public function getDateDebut()
    {
        return $this->dateDebut;
    }

    /**
     * Set dateFin
     *
     * @param \string $dateFin
     *
     * @return Event
     */
    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    /**
     * Get dateFin
     *
     * @return \string
     */
    public function getDateFin()
    {
        return $this->dateFin;
    }

    /**
     * Set prix
     *
     * @param string $prix
     *
     * @return Event
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix
     *
     * @return string
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Set nbrMax
     *
     * @param integer $nbrMax
     *
     * @return Event
     */
    public function setNbrMax($nbrMax)
    {
        $this->nbrMax = $nbrMax;

        return $this;
    }

    /**
     * Get nbrMax
     *
     * @return int
     */
    public function getNbrMax()
    {
        return $this->nbrMax;
    }

    /**
     * Set heureDeb
     *
     * @param \DateTime $heureDeb
     *
     * @return Event
     */
    public function setHeureDeb($heureDeb)
    {
        $this->heureDeb = $heureDeb;

        return $this;
    }

    /**
     * Get heureDeb
     *
     * @return \DateTime
     */
    public function getHeureDeb()
    {
        return $this->heureDeb;
    }

    /**
     * Set heureFin
     *
     * @param \DateTime $heureFin
     *
     * @return Event
     */
    public function setHeureFin($heureFin)
    {
        $this->heureFin = $heureFin;

        return $this;
    }

    /**
     * Get heureFin
     *
     * @return \DateTime
     */
    public function getHeureFin()
    {
        return $this->heureFin;
    }

    /**
     * Set category
     *
     * @param \AppBundle\Entity\Category $category
     *
     * @return Event
     */
    public function setCategory(\AppBundle\Entity\Category $category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \AppBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set organisateur
     *
     * @param \AppBundle\Entity\Organisateur $organisateur
     *
     * @return Event
     */
    public function setOrganisateur(\AppBundle\Entity\Organisateur $organisateur = null)
    {
        $this->organisateur = $organisateur;

        return $this;
    }

    /**
     * Get organisateur
     *
     * @return \AppBundle\Entity\Organisateur
     */
    public function getOrganisateur()
    {
        return $this->organisateur;
    }

    /**
     * Set utilisateur
     *
     * @param \AppBundle\Entity\User $utilisateur
     *
     * @return Event
     */
    public function setUtilisateur(\AppBundle\Entity\User $utilisateur = null)
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    /**
     * Get utilisateur
     *
     * @return \AppBundle\Entity\User
     */
    public function getUtilisateur()
    {
        return $this->utilisateur;
    }

    /**
     * Add credit
     *
     * @param \AppBundle\Entity\Credit $credit
     *
     * @return Event
     */
    public function addCredit(\AppBundle\Entity\Credit $credit)
    {
        $this->credits[] = $credit;

        return $this;
    }

    /**
     * Remove credit
     *
     * @param \AppBundle\Entity\Credit $credit
     */
    public function removeCredit(\AppBundle\Entity\Credit $credit)
    {
        $this->credits->removeElement($credit);
    }

    /**
     * Get credits
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCredits()
    {
        return $this->credits;
    }

    


    /**
     * Set adresse
     *
     * @param string $adresse
     *
     * @return Event
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set codeP
     *
     * @param string $codeP
     *
     * @return Event
     */
    public function setCodeP($codeP)
    {
        $this->codeP = $codeP;

        return $this;
    }

    /**
     * Get codeP
     *
     * @return string
     */
    public function getCodeP()
    {
        return $this->codeP;
    }

    /**
     * Set ville
     *
     * @param \AppBundle\Entity\Ville $ville
     *
     * @return Event
     */
    public function setVille(\AppBundle\Entity\Ville $ville)
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * Get ville
     *
     * @return \AppBundle\Entity\Ville
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * Set region
     *
     * @param \AppBundle\Entity\Region $region
     *
     * @return Event
     */
    public function setRegion(\AppBundle\Entity\Region $region)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Get region
     *
     * @return \AppBundle\Entity\Region
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Set departement
     *
     * @param \AppBundle\Entity\Departement $departement
     *
     * @return Event
     */
    public function setDepartement(\AppBundle\Entity\Departement $departement)
    {
        $this->departement = $departement;

        return $this;
    }

    /**
     * Get departement
     *
     * @return \AppBundle\Entity\Departement
     */
    public function getDepartement()
    {
        return $this->departement;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Event
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }
}
