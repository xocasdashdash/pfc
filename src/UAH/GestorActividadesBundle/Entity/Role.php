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
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Doctrine\ORM\Mapping\Index;

use Symfony\Component\Security\Core\Role\RoleInterface;
use UAH\GestorActividadesBundle\Entity\User as User;

/**
 * @Table(name="UAH_GAT_Role",uniqueConstraints={@UniqueConstraint(name="UAH_GAT_UniqueRole_idx", columns={"role"})}, indexes={@Index(name="UAH_GAT_PK_ROLE",columns={"id"})})
 * @Entity(repositoryClass="UAH\GestorActividadesBundle\Repository\RoleRepository")
 */
class Role implements RoleInterface {

    /**
     * @Column(name="id", type="integer")
     * @Id()
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Column(name="name", type="string", length=30)
     */
    private $name;

    /**
     * @Column(name="role", type="string", length=20)
     */
    private $role;

    /**
     * @ManyToMany(targetEntity="User", mappedBy="roles")
     * @JoinTable(name="UAH_GAT_User_Roles")
     */
    private $users;

    public function __construct() {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @see RoleInterface
     */
    public function getRole() {
        return $this->role;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Role
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set role
     *
     * @param string $role
     * @return Role
     */
    public function setRole($role) {
        $this->role = $role;

        return $this;
    }

    /**
     * Add users
     *
     * @param \UAH\GestorActividadesBundle\Entity\User $users
     * @return Role
     */
    public function addUser(\UAH\GestorActividadesBundle\Entity\User $users) {
        $this->users[] = $users;

        return $this;
    }

    /**
     * Remove users
     *
     * @param \UAH\GestorActividadesBundle\Entity\User $users
     */
    public function removeUser(\UAH\GestorActividadesBundle\Entity\User $users) {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsers() {
        return $this->users;
    }

}
