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
     * @var integer userId;
     * @ManyToOne(targetEntity="User",fetch="EAGER",inversedBy="applications")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $userId;

    /**
     * @var date applicationDate
     * @Column(name="applicationDate", type="datetime")
     */
    private $applicationDate;

    /**
     * @var blob application_file
     * @Column(name="applicationFile", type="blob")
     */
    private $applicationFile;

    /**
     * @var string verification_code
     * @Column(name="verificationCode", type="string")
     */
    private $verificationCode;

    /**
     * @var boolean isProcessed
     * @Column(name="isProcessed", type="boolean")
     */
    private $isProcessed;
    /**
     * @OneToMany(targetEntity="Enrollment", mappedBy="applicationForm")
     * @var type 
     */
    private $enrollments;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     * @return Application
     */
    public function setUserId($userId) {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer 
     */
    public function getUserId() {
        return $this->userId;
    }

    /**
     * Set applicationDate
     *
     * @param \DateTime $applicationDate
     * @return Application
     */
    public function setApplicationDate($applicationDate) {
        $this->applicationDate = $applicationDate;

        return $this;
    }

    /**
     * Get applicationDate
     *
     * @return \DateTime 
     */
    public function getApplicationDate() {
        return $this->applicationDate;
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
     * Set isProcessed
     *
     * @param boolean $isProcessed
     * @return Application
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
     * Constructor
     */
    public function __construct()
    {
        $this->enrollments = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add enrollments
     *
     * @param \UAH\GestorActividadesBundle\Entity\Enrollment $enrollments
     * @return Application
     */
    public function addEnrollment(\UAH\GestorActividadesBundle\Entity\Enrollment $enrollments)
    {
        $this->enrollments[] = $enrollments;

        return $this;
    }

    /**
     * Remove enrollments
     *
     * @param \UAH\GestorActividadesBundle\Entity\Enrollment $enrollments
     */
    public function removeEnrollment(\UAH\GestorActividadesBundle\Entity\Enrollment $enrollments)
    {
        $this->enrollments->removeElement($enrollments);
    }

    /**
     * Get enrollments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEnrollments()
    {
        return $this->enrollments;
    }
}
