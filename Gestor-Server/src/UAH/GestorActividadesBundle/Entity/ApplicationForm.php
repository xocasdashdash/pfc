<?php

namespace UAH\GestorActividadesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * Description of Application
 *
 * @ORM\Table(name="ApplicationForm")
 * @ORM\Entity()
 */
class ApplicationForm {
    //put your code here
    /**
     * @var integer Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @var integer userId;
     * @ORM\ManyToOne(targetEntity="User",fetch="EAGER",inversedBy="id")
     * @ORM\JoinColumn(name="userId", referencedColumnName="id")
     */
    private $userId;
    
    /**
     * @var date applicationDate
     * @ORM\Column(name="applicationDate", type="datetime")
     */
    private $applicationDate;
    /**
     * @var blob application_file
     * @ORM\Column(name="applicationFile", type="blob")
     */
    private $applicationFile;
    
    /**
     * @var string verification_code
     * @ORM\Column(name="verificationCode", type="string")
     */
    private $verificationCode;
    /**
     * @var boolean isProcessed
     * @ORM\Column(name="isProcessed", type="boolean")
     */
    private $isProcessed;

    

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
     * Set userId
     *
     * @param integer $userId
     * @return ApplicationForm
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    
        return $this;
    }

    /**
     * Get userId
     *
     * @return integer 
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set applicationDate
     *
     * @param \DateTime $applicationDate
     * @return ApplicationForm
     */
    public function setApplicationDate($applicationDate)
    {
        $this->applicationDate = $applicationDate;
    
        return $this;
    }

    /**
     * Get applicationDate
     *
     * @return \DateTime 
     */
    public function getApplicationDate()
    {
        return $this->applicationDate;
    }

    /**
     * Set applicationFile
     *
     * @param string $applicationFile
     * @return ApplicationForm
     */
    public function setApplicationFile($applicationFile)
    {
        $this->applicationFile = $applicationFile;
    
        return $this;
    }

    /**
     * Get applicationFile
     *
     * @return string 
     */
    public function getApplicationFile()
    {
        return $this->applicationFile;
    }

    /**
     * Set verificationCode
     *
     * @param string $verificationCode
     * @return ApplicationForm
     */
    public function setVerificationCode($verificationCode)
    {
        $this->verificationCode = $verificationCode;
    
        return $this;
    }

    /**
     * Get verificationCode
     *
     * @return string 
     */
    public function getVerificationCode()
    {
        return $this->verificationCode;
    }

    /**
     * Set isProcessed
     *
     * @param boolean $isProcessed
     * @return ApplicationForm
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
}
