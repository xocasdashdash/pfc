<?php

namespace UAH\GestorActividadesBundle\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Entity;

/**
 * Description of Application
 *
 * @Table(name="UAH_GAT_Application")
 * @Entity(repositoryClass="UAH\GestorActividadesBundle\Repository\ApplicationRepository")
 */
class Application
{
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
     * @var User verifiedByUser
     * @ManyToOne(targetEntity="User", inversedBy="verifiedApplications")
     * @JoinColumn(name="verified_by_id", referencedColumnName="id", nullable=true,onDelete="SET NULL")     
     */
    private $verifiedByUser;

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
     * Get verificationCode separated by a space
     *
     * @return string 
     */
    public function getVerificationCodeSeparado($code_length = 5, $separador = "<br>") {
        $arr_resultado = str_split($this->verificationCode, $code_length);
        $resultado = "";
        end($arr_resultado);
        $end_key = key($arr_resultado);
        foreach ($arr_resultado as $key => $arr) {
            $resultado .=$arr . ($key === $end_key ? "" : $separador);
        }
        return $resultado;
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

    /**
     * Set verifiedByUser
     *
     * @param \UAH\GestorActividadesBundle\Entity\User $verifiedByUser
     * @return Application
     */
    public function setVerifiedByUser(\UAH\GestorActividadesBundle\Entity\User $verifiedByUser = null) {
        $this->verifiedByUser = $verifiedByUser;

        return $this;
    }

    /**
     * Get verifiedByUser
     *
     * @return \UAH\GestorActividadesBundle\Entity\User 
     */
    public function getVerifiedByUser() {
        return $this->verifiedByUser;
    }

    /**
     * Get verifiedByUser
     *
     * @return \UAH\GestorActividadesBundle\Entity\User
     */
    public function getVerifiedByUser()
    {
        return $this->verifiedByUser;
    }
}
