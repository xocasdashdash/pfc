<?php

namespace UAH\GestorActividadesBundle\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;
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
     * @Column(name="dateRecognized", type="datetime",nullable=true)
     */
    private $dateRecognized;

    /**
     * @var datetime
     * @Column(name="dateRegistered", type="datetime")
     */
    private $dateRegistered;

    /**
     *
     * @var float
     * @Column(name="recognizedCredits",type="float", nullable=true)
     */
    private $recognizedCredits;

    /**
     *
     * @var float
     * @Column(name="creditsType",type="string", nullable=true)
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
    private $application;

    /**
     * @var User verifiedByUser
     * @ManyToOne(targetEntity="User", inversedBy="recognizedApplications")
     * @JoinColumn(name="recognized_by_id", referencedColumnName="id", nullable=true,onDelete="SET NULL")     
     */
    private $recognizedByUser;
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
     * Set dateRecognized
     *
     * @param \DateTime $dateRecognized
     * @return Enrollment
     */
    public function setDateProcessed($dateRecognized) {
        $this->dateRecognized = $dateRecognized;

        return $this;
    }

    /**
     * Get dateRecognized
     *
     * @return \DateTime
     */
    public function getDateProcessed() {
        return $this->dateRecognized;
    }

    /**
     * Set dateRegistered
     *
     * @param  \DateTime  $dateRegistered
     * @return Enrollment
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
     * Set recognizedCredits
     *
     * @param  float      $recognizedCredits
     * @return Enrollment
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
     * @param string $creditsType
     * @return Enrollment
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
     * @param  \UAH\GestorActividadesBundle\Entity\Activity $activity
     * @return Enrollment
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

    /**
     * Set application
     *
     * @param \UAH\GestorActividadesBundle\Entity\Application $application
     * @return Enrollment
     */
    public function setApplication(\UAH\GestorActividadesBundle\Entity\Application $application = null) {
        $this->application = $application;

        return $this;
    }

    /**
     * Get application
     *
     * @return \UAH\GestorActividadesBundle\Entity\Application
     */
    public function getApplication() {
        return $this->application;
    }

    /**
     * Set user
     *
     * @param  \UAH\GestorActividadesBundle\Entity\User $user
     * @return Enrollment
     */
    public function setUser(\UAH\GestorActividadesBundle\Entity\User $user = null) {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \UAH\GestorActividadesBundle\Entity\User
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * Set status
     *
     * @param  \UAH\GestorActividadesBundle\Entity\Statusenrollment $status
     * @return Enrollment
     */
    public function setStatus(\UAH\GestorActividadesBundle\Entity\Statusenrollment $status = null) {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return \UAH\GestorActividadesBundle\Entity\Statusenrollment
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * @PrePersist
     */
    public function prepare(LifecycleEventArgs $event) {
        if (is_null($this->getDateRegistered())) {
            $this->setDateRegistered(new \DateTime("now"));
        }
        if (is_null($this->getStatus())) {
            $em = $event->getEntityManager();
            $default_status = $em->getRepository('UAHGestorActividadesBundle:Statusenrollment')->getDefault();
            $this->setStatus($default_status);
        }
    }

    /**
     * PreUpdate
     */
//    public function preupdate(LifecycleEventArgs $event) {
//
//        if ($event->hasChangedField('status')) {
//            $em = $event->getEntityManager();
//            if ($this->getStatus() === $em->
//                            getRepository('UAHGestorActividadesBundle:Statusenrollment')->getRecognizedStatus()) {
//                $this->setDateProcessed(new \DateTime("now"));
//            } elseif ($this->getStatus() === $em->
//                            getRepository('UAHGestorActividadesBundle:Statusenrollment')->getDefault()) {
//                $event->getEntity()->
//                
//            }
//        }
//    }

    /**
     * Set dateRecognized
     *
     * @param \DateTime $dateRecognized
     * @return Enrollment
     */
    public function setDateRecognized($dateRecognized)
    {
        $this->dateRecognized = $dateRecognized;

        return $this;
    }

    /**
     * Get dateRecognized
     *
     * @return \DateTime 
     */
    public function getDateRecognized()
    {
        return $this->dateRecognized;
    }

    /**
     * Set recognizedByUser
     *
     * @param \UAH\GestorActividadesBundle\Entity\User $recognizedByUser
     * @return Enrollment
     */
    public function setRecognizedByUser(\UAH\GestorActividadesBundle\Entity\User $recognizedByUser = null)
    {
        $this->recognizedByUser = $recognizedByUser;

        return $this;
    }

    /**
     * Get recognizedByUser
     *
     * @return \UAH\GestorActividadesBundle\Entity\User 
     */
    public function getRecognizedByUser()
    {
        return $this->recognizedByUser;
    }
}
