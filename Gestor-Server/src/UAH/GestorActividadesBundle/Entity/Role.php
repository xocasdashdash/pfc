<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace UAH\GestorActividadesBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Role\RoleInterface;
use UAH\GestorActividadesBundle\Entity\User as User;


/**
 * @ORM\Table(name="Role",uniqueConstraints={@ORM\UniqueConstraint(name="UAH_GAT_UniqueRole_idx", columns={"role"})}, indexes={@ORM\Index(name="UAH_GAT_PK_ROLE",columns={"id"})})
 * @ORM\Entity()
 */
class Role implements RoleInterface
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", length=30)
     */
    private $name;

    /**
     * @ORM\Column(name="role", type="string", length=20)
     */
    private $role;

    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="roles")
     * @ORM\JoinTable(name="UAH_GAT_User_Roles")
     */
    private $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    /**
     * @see RoleInterface
     */
    public function getRole()
    {
        return $this->role;
    }


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
     * @return Role
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
     * Set role
     *
     * @param string $role
     * @return Role
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Add users
     *
     * @param \UAH\GestorActividadesBundle\Entity\User $users
     * @return Role
     */
    public function addUser(\UAH\GestorActividadesBundle\Entity\User $users)
    {
        $this->users[] = $users;

        return $this;
    }

    /**
     * Remove users
     *
     * @param \UAH\GestorActividadesBundle\Entity\User $users
     */
    public function removeUser(\UAH\GestorActividadesBundle\Entity\User $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsers()
    {
        return $this->users;
    }
}
