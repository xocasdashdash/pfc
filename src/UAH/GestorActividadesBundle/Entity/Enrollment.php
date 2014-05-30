<?php

namespace UAH\GestorActividadesBundle\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Entity;

/**
 * Enrollment
 *
 * @Table(name="UAH_GAT_Enrollment")
 * @Entity
 */
class Enrollment {
    
    /**
     * @var integer
     *
     * @Column(name="id", type="integer")
     * @Id
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var datetime
     * @Column(name="dateProcessed", type="datetime")
     */
    private $dateProcessed;

    /**
     * @var datetime
     * @Column(name="dateRegistered", type="datetime")
     */
    private $dateRegistered;

    /**
     * @var boolean
     * @Column(name="isProcessed", type="boolean")
     */
    private $isProcessed;

    /**
     *
     * @var float
     * @Column(name="recognizedCredits",type="float", nullable=true)
     */
    private $recognizedCredits;

    /**
     *
     * @var float
     * @Column(name="creditsType",type="float", nullable=true)
     */
    private $creditsType;
    
    /**
     * @var int Estado del registro
     * @OneToOne(targetEntity="Status_enrollment")
     * @JoinColumn(name="status_enrollment", referencedColumnName="id")
     */
    private $status;
    /**
     *
     * @var usuario
     * @ManyToOne(targetEntity="User", inversedBy="id")
     * @JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     */
    private $user;

    /**
     *
     * @var integer
     * @ManyToOne(targetEntity="Activity", inversedBy="id")
     * @JoinColumn(name="activity_id", referencedColumnName="id", nullable=false)
     */
    private $activity;

    /**
     * @var integer
     * @ManyToOne(targetEntity="Application", inversedBy="id")
     * @JoinColumn(name="application_id", referencedColumnName="id", nullable=true)
     */
    private $applicationForm;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set dateProcessed
     *
     * @param \DateTime $dateProcessed
     * @return Enrollment
     */
    public function setDateProcessed($dateProcessed) {
        $this->dateProcessed = $dateProcessed;

        return $this;
    }

    /**
     * Get dateProcessed
     *
     * @return \DateTime 
     */
    public function getDateProcessed() {
        return $this->dateProcessed;
    }

    /**
     * Set dateRegistered
     *
     * @param \DateTime $dateRegistered
     * @return Enrollment
     */
    public function setDateRegistered($dateRegistered) {
        $this->dateRegistered = $dateRegistered;

        return $this;
    }

    /**
     * Get dateRegistered
     *
     * @return \DateTime 
     */
    public function getDateRegistered() {
        return $this->dateRegistered;
    }

    /**
     * Set isProcessed
     *
     * @param boolean $isProcessed
     * @return Enrollment
     */
    public function setIsProcessed($isProcessed) {
        $this->isProcessed = $isProcessed;

        return $this;
    }

    /**
     * Get isProcessed
     *
     * @return boolean 
     */
    public function getIsProcessed() {
        return $this->isProcessed;
    }

    /**
     * Set actividad
     *
     * @param \UAH\GestorActividadesBundle\Entity\Activity $actividad
     * @return Enrollment
     */
    public function setActividad(\UAH\GestorActividadesBundle\Entity\Activity $actividad) {
        $this->actividad = $actividad;

        return $this;
    }

    /**
     * Get actividad
     *
     * @return \UAH\GestorActividadesBundle\Entity\Activity 
     */
    public function getActividad() {
        return $this->actividad;
    }

    /**
     * Set applicationForm
     *
     * @param \UAH\GestorActividadesBundle\Entity\Application $applicationForm
     * @return Enrollment
     */
    public function setApplication(\UAH\GestorActividadesBundle\Entity\Application $applicationForm = null) {
        $this->applicationForm = $applicationForm;

        return $this;
    }

    /**
     * Get applicationForm
     *
     * @return \UAH\GestorActividadesBundle\Entity\Application 
     */
    public function getApplication() {
        return $this->applicationForm;
    }

    /**
     * Set recognizedCredits
     *
     * @param float $recognizedCredits
     * @return Enrollment
     */
    public function setRecognizedCredits($recognizedCredits) {
        $this->recognizedCredits = $recognizedCredits;

        return $this;
    }

    /**
     * Get recognizedCredits
     *
     * @return float 
     */
    public function getRecognizedCredits() {
        return $this->recognizedCredits;
    }

    /**
     * Set creditsType
     *
     * @param float $creditsType
     * @return Enrollment
     */
    public function setCreditsType($creditsType) {
        $this->creditsType = $creditsType;

        return $this;
    }

    /**
     * Get creditsType
     *
     * @return float 
     */
    public function getCreditsType() {
        return $this->creditsType;
    }

    /**
     * Set activity
     *
     * @param \UAH\GestorActividadesBundle\Entity\Activity $activity
     * @return Enrollment
     */
    public function setActivity(\UAH\GestorActividadesBundle\Entity\Activity $activity) {
        $this->activity = $activity;

        return $this;
    }

    /**
     * Get activity
     *
     * @return \UAH\GestorActividadesBundle\Entity\Activity 
     */
    public function getActivity() {
        return $this->activity;
    }

    /**
     * Set applicationForm
     *
     * @param \UAH\GestorActividadesBundle\Entity\Application $applicationForm
     * @return Enrollment
     */
    public function setApplicationForm(\UAH\GestorActividadesBundle\Entity\Application $applicationForm = null) {
        $this->applicationForm = $applicationForm;

        return $this;
    }

    /**
     * Get applicationForm
     *
     * @return \UAH\GestorActividadesBundle\Entity\Application 
     */
    public function getApplicationForm() {
        return $this->applicationForm;
    }


    /**
     * Set status_enrollment
     *
     * @param \UAH\GestorActividadesBundle\Entity\Status_enrollment $statusEnrollment
     * @return Enrollment
     */
    public function setStatusEnrollment(\UAH\GestorActividadesBundle\Entity\Status_enrollment $statusEnrollment = null)
    {
        $this->status_enrollment = $statusEnrollment;

        return $this;
    }

    /**
     * Get status_enrollment
     *
     * @return \UAH\GestorActividadesBundle\Entity\Status_enrollment 
     */
    public function getStatusEnrollment()
    {
        return $this->status_enrollment;
    }

    /**
     * Set user
     *
     * @param \UAH\GestorActividadesBundle\Entity\User $user
     * @return Enrollment
     */
    public function setUser(\UAH\GestorActividadesBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \UAH\GestorActividadesBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}
