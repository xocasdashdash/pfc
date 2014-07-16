<?php

namespace UAH\GestorActividadesBundle\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Entity;

/**
 * Description of Application
 *
 * @Table(name="UAH_GAT_Application")
 * @Entity()
 */
class Application {

    //put your code here
    /**
     * @var integer Id
     * @Column(name="id", type="integer")
     * @Id
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer user;
     * @ManyToOne(targetEntity="User",fetch="EAGER",inversedBy="applications")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var date applicationDate
     * @Column(name="applicationDateCreated", type="datetime")
     */
    private $applicationDateCreated;

    /**
     * @var date applicationDate
     * @Column(name="applicationDateVerified", type="datetime", nullable=true)
     */
    private $applicationDateVerified;

    /**
     * @var blob application_file
     * @Column(name="applicationFile", type="blob", nullable=true)
     */
    private $applicationFile;

    /**
     * @var string verification_code
     * @Column(name="verificationCode", type="string")
     */
    private $verificationCode;

    /**
     * @OneToMany(targetEntity="Enrollment", fetch="EAGER", mappedBy="application")
     * @var type 
     */
    private $enrollments;

    /**
     * @var int Estado del registro
     * @ManyToOne(targetEntity="Statusapplication")
     * @JoinColumn(name="status", referencedColumnName="id")
     */
    private $status;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set user
     *
     * @param integer $user
     * @return Application
     */
    public function setUser($user) {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return integer 
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * Set applicationFile
     *
     * @param string $applicationFile
     * @return Application
     */
    public function setApplicationFile($applicationFile) {
        $this->applicationFile = $applicationFile;

        return $this;
    }

    /**
     * Get applicationFile
     *
     * @return string 
     */
    public function getApplicationFile() {
        return $this->applicationFile;
    }

    /**
     * Set verificationCode
     *
     * @param string $verificationCode
     * @return Application
     */
    public function setVerificationCode($verificationCode) {
        $this->verificationCode = $verificationCode;

        return $this;
    }

    /**
     * Get verificationCode
     *
     * @return string 
     */
    public function getVerificationCode() {
        return $this->verificationCode;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->enrollments = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add enrollments
     *
     * @param \UAH\GestorActividadesBundle\Entity\Enrollment $enrollments
     * @return Application
     */
    public function addEnrollment(\UAH\GestorActividadesBundle\Entity\Enrollment $enrollments) {
        $this->enrollments[] = $enrollments;

        return $this;
    }

    /**
     * Remove enrollments
     *
     * @param \UAH\GestorActividadesBundle\Entity\Enrollment $enrollments
     */
    public function removeEnrollment(\UAH\GestorActividadesBundle\Entity\Enrollment $enrollments) {
        $this->enrollments->removeElement($enrollments);
    }

    /**
     * Get enrollments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEnrollments() {
        return $this->enrollments;
    }

    /**
     * Set status
     *
     * @param \UAH\GestorActividadesBundle\Entity\Statusapplication $status
     * @return Application
     */
    public function setStatus(\UAH\GestorActividadesBundle\Entity\Statusapplication $status = null) {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return \UAH\GestorActividadesBundle\Entity\Statusapplication 
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * Set applicationDateCreated
     *
     * @param \DateTime $applicationDateCreated
     * @return Application
     */
    public function setApplicationDateCreated(\DateTime $applicationDateCreated) {
        $this->applicationDateCreated = $applicationDateCreated;

        return $this;
    }

    /**
     * Get applicationDateCreated
     *
     * @return \DateTime 
     */
    public function getApplicationDateCreated() {
        return $this->applicationDateCreated;
    }

    /**
     * Set applicationDateVerified
     *
     * @param \DateTime $applicationDateVerified
     * @return Application
     */
    public function setApplicationDateVerified(\DateTime $applicationDateVerified) {
        $this->applicationDateVerified = $applicationDateVerified;

        return $this;
    }

    /**
     * Get applicationDateVerified
     *
     * @return \DateTime 
     */
    public function getApplicationDateVerified() {
        return $this->applicationDateVerified;
    }

    public function getNumberOfCredits() {
        $resultado = 0;
        
        $this->getEnrollments()->map(function($entity) use (&$resultado) {
            $resultado +=$entity->getRecognizedCredits();            
        });

        return $resultado;
    }

}
