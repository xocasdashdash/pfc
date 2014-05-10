<?php

namespace UAH\GestorActividadesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BasicRegistration
 *
 * @ORM\Table(name="BasicRegistration")
 * @ORM\Entity
 */
class BasicRegistration
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="dni", type="string", length=255)
     */
    private $dni;
    
    /**
     * @var datetime
     * @ORM\Column(name="dateProcessed", type="datetime")
     */
    private $dateProcessed;
    
    /**
     * @var datetime
     * @ORM\Column(name="dateRegistered", type="datetime")
     */
    private $dateRegistered;
    
    /**
     * @var boolean
     * @ORM\Column(name="isProcessed", type="boolean")
     */
    private $isProcessed;
    
    /**
     *
     * @var integer
     * @ORM\ManyToOne(targetEntity="Activity", inversedBy="id")
     * @ORM\JoinColumn(name="activityId", referencedColumnName="id", nullable=false)
     */
    private $activity;
    /**
     * @var integer
     * @ORM\ManyToOne(targetEntity="ApplicationForm", inversedBy="id")
     * @ORM\JoinColumn(name="idApplicationForm", referencedColumnName="id", nullable=true)
     */
    private $applicationForm;
    
    
    /**
     *
     * @var float
     * @ORM\Column(name="recognizedCredits",type="float", nullable=true)
     */
    private $recognizedCredits;
    
    /**
     *
     * @var float
     * @ORM\Column(name="creditsType",type="float", nullable=true)
     */
    private $creditsType;



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
     * Set dni
     *
     * @param string $dni
     * @return BasicRegistration
     */
    public function setDni($dni)
    {
        $this->dni = $dni;
    
        return $this;
    }

    /**
     * Get dni
     *
     * @return string 
     */
    public function getDni()
    {
        return $this->dni;
    }

    /**
     * Set dateProcessed
     *
     * @param \DateTime $dateProcessed
     * @return BasicRegistration
     */
    public function setDateProcessed($dateProcessed)
    {
        $this->dateProcessed = $dateProcessed;
    
        return $this;
    }

    /**
     * Get dateProcessed
     *
     * @return \DateTime 
     */
    public function getDateProcessed()
    {
        return $this->dateProcessed;
    }

    /**
     * Set dateRegistered
     *
     * @param \DateTime $dateRegistered
     * @return BasicRegistration
     */
    public function setDateRegistered($dateRegistered)
    {
        $this->dateRegistered = $dateRegistered;
    
        return $this;
    }

    /**
     * Get dateRegistered
     *
     * @return \DateTime 
     */
    public function getDateRegistered()
    {
        return $this->dateRegistered;
    }

    /**
     * Set isProcessed
     *
     * @param boolean $isProcessed
     * @return BasicRegistration
     */
    public function setIsProcessed($isProcessed)
    {
        $this->isProcessed = $isProcessed;
    
        return $this;
    }

    /**
     * Get isProcessed
     *
     * @return boolean 
     */
    public function getIsProcessed()
    {
        return $this->isProcessed;
    }

    /**
     * Set actividad
     *
     * @param \UAH\GestorActividadesBundle\Entity\Activity $actividad
     * @return BasicRegistration
     */
    public function setActividad(\UAH\GestorActividadesBundle\Entity\Activity $actividad)
    {
        $this->actividad = $actividad;
    
        return $this;
    }

    /**
     * Get actividad
     *
     * @return \UAH\GestorActividadesBundle\Entity\Activity 
     */
    public function getActividad()
    {
        return $this->actividad;
    }

    /**
     * Set applicationForm
     *
     * @param \UAH\GestorActividadesBundle\Entity\ApplicationForm $applicationForm
     * @return BasicRegistration
     */
    public function setApplicationForm(\UAH\GestorActividadesBundle\Entity\ApplicationForm $applicationForm = null)
    {
        $this->applicationForm = $applicationForm;
    
        return $this;
    }

    /**
     * Get applicationForm
     *
     * @return \UAH\GestorActividadesBundle\Entity\ApplicationForm 
     */
    public function getApplicationForm()
    {
        return $this->applicationForm;
    }

    /**
     * Set recognizedCredits
     *
     * @param float $recognizedCredits
     * @return BasicRegistration
     */
    public function setRecognizedCredits($recognizedCredits)
    {
        $this->recognizedCredits = $recognizedCredits;
    
        return $this;
    }

    /**
     * Get recognizedCredits
     *
     * @return float 
     */
    public function getRecognizedCredits()
    {
        return $this->recognizedCredits;
    }

    /**
     * Set creditsType
     *
     * @param float $creditsType
     * @return BasicRegistration
     */
    public function setCreditsType($creditsType)
    {
        $this->creditsType = $creditsType;
    
        return $this;
    }

    /**
     * Get creditsType
     *
     * @return float 
     */
    public function getCreditsType()
    {
        return $this->creditsType;
    }

    /**
     * Set activity
     *
     * @param \UAH\GestorActividadesBundle\Entity\Activity $activity
     * @return BasicRegistration
     */
    public function setActivity(\UAH\GestorActividadesBundle\Entity\Activity $activity)
    {
        $this->activity = $activity;
    
        return $this;
    }

    /**
     * Get activity
     *
     * @return \UAH\GestorActividadesBundle\Entity\Activity 
     */
    public function getActivity()
    {
        return $this->activity;
    }
}
