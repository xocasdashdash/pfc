<?php

namespace UAH\GestorActividadesBundle\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * Enrollment
 *
 * @Table(name="UAH_GAT_Enrollment")
 * @Entity(repositoryClass="UAH\GestorActividadesBundle\Repository\EnrollmentRepository")
 * @HasLifecycleCallbacks
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
     * @Column(name="dateProcessed", type="datetime",nullable=true)
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
     * @ManyToOne(targetEntity="Statusenrollment")
     * @JoinColumn(name="status", referencedColumnName="id")
     */
    private $status;
    /**
     *
     * @var usuario
     * @ManyToOne(targetEntity="User", inversedBy="enrollments")
     * @JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     */
    private $user;

    /**
     *
     * @var integer
     * @ManyToOne(targetEntity="Activity", inversedBy="enrollees")
     * @JoinColumn(name="activity_id", referencedColumnName="id", nullable=false)
     */
    private $activity;

    /**
     * @var integer
     * @ManyToOne(targetEntity="Application", inversedBy="enrollments")
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

    /**
     * Set status
     *
     * @param \UAH\GestorActividadesBundle\Entity\Statusenrollment $status
     * @return Enrollment
     */
    public function setStatus(\UAH\GestorActividadesBundle\Entity\Statusenrollment $status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return \UAH\GestorActividadesBundle\Entity\Statusenrollment 
     */
    public function getStatus()
    {
        return $this->status;
    }
    
    /**
     * @PrePersist
     */
    public function prepare(LifecycleEventArgs $event){
        if(is_null($this->getDateRegistered())){
            $this->setDateRegistered(new \DateTime("now"));
        }
        if(is_null($this->getStatus())){
            $em = $event->getEntityManager();
            $default_status = $em->getRepository('UAHGestorActividadesBundle:Statusenrollment')->getDefault();
            $this->setStatus($default_status);
        }
        $this->setIsProcessed(false);
    }
}
