<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
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
/**
 * @Table(name="UAH_GAT_DefaultPermits")
 * @Entity
 */
class DefaultPermit{
    
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ManyToMany(targetEntity="Role", fetch="EAGER")
     * @JoinTable(name="UAH_GAT_DefaultPermits_Roles",
     * joinColumns={@JoinColumn(name="default_permits_id", referencedColumnName="id")}, 
     * inverseJoinColumns={@JoinColumn(name="role_id", referencedColumnName="id")})
     */
    private $roles;
    
    /**
     *  @Column(name="ID_USULDAP", type="string", length= 255, nullable=false)
     * 
     */
    private $id_usuldap;

    /**
     * Get id
     *
     * @return \int 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set id_usuldap
     *
     * @param string $idUsuldap
     * @return DefaultPermit
     */
    public function setIdUsuldap($idUsuldap)
    {
        $this->id_usuldap = $idUsuldap;

        return $this;
    }

    /**
     * Get id_usuldap
     *
     * @return string 
     */
    public function getIdUsuldap()
    {
        return $this->id_usuldap;
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
     * @return DefaultPermit
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

    /**
     * Get roles
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRoles()
    {
        return $this->roles;
    }
}
