<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Image
 *
 * @ORM\Table(name="atl_image")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ImageRepository")
 * @ORM\HasLifeCycleCallbacks
 */
class Image {

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
     * @ORM\Column(name="titre", type="string", length=255)
     * @Assert\NotBlank
     */
    public $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255, nullable=true)
     */
    public $path;
        /**
     * @var string
     *
     * @ORM\Column(name="file",type="string", length=255, nullable=true)
     */
    public $file;

    public function getUploadRootDir() {
        return __dir__ . '/../../../../web/uploads';
    }

    public function getAbsolutePath() {
        return null === $this->path ? null : $this->getUploadRootDir() . '/' . $this->path;
    }

    /**
     * ORM\PrePersist()
     * ORM\PreUpdate() 
     */
    public function preUpload(){
        $this->tempFile = $this->getAbsolutePath();
        $this->oldFile = $this->getPath();
        
        if(null !== $this->file){
            $this->path = sha1(uniqid(mt_rand(), true)).'.'.$this->file->guessExtension();
        }
        
    }
    
        /**
     * ORM\PostPersist()
     * ORM\PostUpdate() 
     */
    public function upload(){
           if(null !== $this->file){
               $this->file->move($this->getUploadRootDir(), $this->path);
               unset($this->file);
               
               if($this->oldFile != null){
                   unlink($this->tempFile);
               }
           }
    }
    
    public function preRemoveUpload(){
        $this->tempFile = $this->getAbsolutePath();
    }
    
  
    public function removeUpload(){
        if(file_exists($this->tempFile)){
            unlink($this->tempFile);
        }
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
     * @return Image
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
     * Set path
     *
     * @param string $path
     *
     * @return Image
     */
    public function setPath($path) {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath() {
        return $this->path;
    }

  

    public function __toString() {
        return $this->getTitre();
    }

}
