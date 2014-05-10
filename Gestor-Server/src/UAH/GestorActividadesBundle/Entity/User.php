<?php

namespace UAH\GestorActividadesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
/**
 * User
 *
 * @ORM\Table(name="User")
 * @ORM\Entity
 */
class User implements UserInterface
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="creationIp", type="string", length=255, nullable=true)
     */
    private $creationIp;
    
    /**
     * @var integer 
     * @ORM\ManyToOne(targetEntity="Degree", inversedBy="id")
     * @ORM\JoinColumn(name="degreeId", referencedColumnName="id", nullable=true)
     */
    private $degreeId;
    
    /**
     * @var string
     *
     * @ORM\Column(name="uahName", type="string", length=255, nullable=true)
     */
    private $uahName;
    /**
     * @ORM\ManyToMany(targetEntity="Role", inversedBy="users")
     * @ORM\JoinTable(name="User_Roles")
     */
    private $roles;
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
     * Set name
     *
     * @param string $name
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set type
     *
     * @param integer $type
     * @return User
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return integer 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;
    
        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set creationIp
     *
     * @param string $creationIp
     * @return User
     */
    public function setCreationIp($creationIp)
    {
        $this->creationIp = $creationIp;
    
        return $this;
    }

    /**
     * Get creationIp
     *
     * @return string 
     */
    public function getCreationIp()
    {
        return $this->creationIp;
    }

    /**
     * Set degreeId
     *
     * @param \UAH\GestorActividadesBundle\Entity\Degree $degreeId
     * @return User
     */
    public function setDegreeId(\UAH\GestorActividadesBundle\Entity\Degree $degreeId)
    {
        $this->degreeId = $degreeId;
    
        return $this;
    }

    /**
     * Get degreeId
     *
     * @return \UAH\GestorActividadesBundle\Entity\Degree 
     */
    public function getDegreeId()
    {
        return $this->degreeId;
    }

    public function eraseCredentials() {
        
    }

    public function getRoles() {
        
    }

    public function getSalt() {
        
    }

    public function getUsername() {
        
    }


    /**
     * Set uahName
     *
     * @param string $uahName
     * @return User
     */
    public function setUahName($uahName)
    {
        $this->uahName = $uahName;
    
        return $this;
    }

    /**
     * Get uahName
     *
     * @return string 
     */
    public function getUahName()
    {
        return $this->uahName;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->roles = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add roles
     *
     * @param \UAH\GestorActividadesBundle\Entity\Role $roles
     * @return User
     */
    public function addRole(\UAH\GestorActividadesBundle\Entity\Role $roles)
    {
        $this->roles[] = $roles;

        return $this;
    }

    /**
     * Remove roles
     *
     * @param \UAH\GestorActividadesBundle\Entity\Role $roles
     */
    public function removeRole(\UAH\GestorActividadesBundle\Entity\Role $roles)
    {
        $this->roles->removeElement($roles);
    }
}
