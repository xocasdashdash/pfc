<?php

namespace UAH\GestorActividadesBundle\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * User
 *
 * @Table(name="UAH_GAT_Session")
 * @Entity
 */
class Session {
    
    /**
     * @Column(name="session_id", type="string", length=255)
     * @Id
     */
    private $session_id;
    
    /**
     * @Column(name="session_value", type="string", length=4000)
     * 
     */
    private $session_value;
    
    /**
     * @Column(name="session_time", type="integer")
     * 
     */
    private $session_time;
    

    /**
     * Set session_id
     *
     * @param string $sessionId
     * @return Session
     */
    public function setSessionId($sessionId)
    {
        $this->session_id = $sessionId;

        return $this;
    }

    /**
     * Get session_id
     *
     * @return string 
     */
    public function getSessionId()
    {
        return $this->session_id;
    }

    /**
     * Set session_value
     *
     * @param string $sessionValue
     * @return Session
     */
    public function setSessionValue($sessionValue)
    {
        $this->session_value = $sessionValue;

        return $this;
    }

    /**
     * Get session_value
     *
     * @return string 
     */
    public function getSessionValue()
    {
        return $this->session_value;
    }

    /**
     * Set session_time
     *
     * @param integer $sessionTime
     * @return Session
     */
    public function setSessionTime($sessionTime)
    {
        $this->session_time = $sessionTime;

        return $this;
    }

    /**
     * Get session_time
     *
     * @return integer 
     */
    public function getSessionTime()
    {
        return $this->session_time;
    }
}
