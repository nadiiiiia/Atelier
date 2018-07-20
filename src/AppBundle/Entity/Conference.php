<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Conference
 *
 * @ORM\Table(name="conference")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ConferenceRepository")
 * ORM\HasLifeCycleCallbacks()
 */
class Conference
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     *
     * @ORM\OneToMany(targetEntity="Speaker", mappedBy="conference", cascade={"persist", "remove"})
     */
    private $speakers;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->speakers = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Conference
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add speaker
     *
     * @param \AppBundle\Entity\Speaker $speaker
     *
     * @return Conference
     */
    public function addSpeaker(\AppBundle\Entity\Speaker $speaker)
    {
        $this->speakers[] = $speaker;
        $speaker->setConference($this);

        return $this;
    }

    /**
     * Remove speaker
     *
     * @param \AppBundle\Entity\Speaker $speaker
     */
    public function removeSpeaker(\AppBundle\Entity\Speaker $speaker)
    {
        $this->speakers->removeElement($speaker);
    }

    /**
     * Get speakers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSpeakers()
    {
        return $this->speakers;
    }
}
