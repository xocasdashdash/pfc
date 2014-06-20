<?php
// src/Acme/DemoBundle/Entity/OpenIdIdentity.php

namespace UAH\GestorActividadesBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;

use Doctrine\ORM\Mapping as ORM;

use Fp\OpenIdBundle\Entity\UserIdentity as BaseUserIdentity;
use Fp\OpenIdBundle\Model\UserIdentityInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="UAH_GAT_openid_identities")
 * 
 */
class OpenIdIdentity extends BaseUserIdentity
{
    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
      * The relation is made eager by purpose. 
      * More info here: {@link https://github.com/formapro/FpOpenIdBundle/issues/54}
      * 
      * @var Symfony\Component\Security\Core\User\UserInterface
      *@
      * @ORM\ManyToOne(targetEntity="User", fetch="EAGER")
      * @ORM\JoinColumns({
      *   @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
      * })
      */
    protected $user;


    //Cada vez que lance doctrine:generate:entities tengo que borrar todo lo que
    //se genera menos esto
    public function __construct()
    {
        parent::__construct();
        // your own logic (nothing for this example)
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
     * Set user
     *
     * @param \UAH\GestorActividadesBundle\Entity\UserInterface $user
     * @return OpenIdIdentity
     */
    public function setUser(UserInterface $user = null)
    {
        $this->user = $user;
        //parent::setUser($user);
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \UAH\GestorActividadesBundle\Entity\UserInterface 
     */
    public function getUser()
    {
        return $this->user;
    }
}
