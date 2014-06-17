<?php

namespace UAH\GestorActividadesBundle\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * User
 *
 * @Table(name="UAH_GAT_User")
 * @Entity
 */
class User implements UserInterface {

    /**
     * @var integer
     *
     * @Column(name="id", type="integer")
     * @Id
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @Column(name="name", type="string", length=255,nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @Column(name="type", type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @var string
     *
     * @Column(name="creationIp", type="string", length=255, nullable=true)
     */
    private $creationIp;

    /**
     * @var string
     *
     * @Column(name="uahName", type="string", length=255, nullable=true)
     */
    private $uahName;

    /**
     * @var string Apellido 1
     * @Column(name="apellido_1", type="string", length=255, nullable=true)
     */
    private $apellido_1;

    /**
     * @var string Apellido 2
     * @Column(name="apellido_2", type="string", length=255, nullable=true)
     */
    private $apellido_2;

    /**
     * @var string Numero de documento de identidad/NIE con letra
     * @Column(name="documento_identidad", type="string", length=255, nullable=true)
     */
    private $documento_identidad;

    /**
     * @var string Tipo de documento de identidad: Pasaporte, NIE o DNI 
     * @Column(name="tipo_documento_identidad", type="string", length=255, nullable=true)
     */
    private $tipo_documento_identidad;

    /**
     * @var integer 
     * @ManyToOne(targetEntity="Degree", inversedBy="degree_students")
     * @JoinColumn(name="degree_id", referencedColumnName="id", nullable=true)
     */
    private $degree_id;

    /**
     * @ManyToMany(targetEntity="Role", inversedBy="users",cascade={"persist"})
     * @JoinTable(name="UAH_GAT_User_Roles",
     * joinColumns={@JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")},
     * inverseJoinColumns={@JoinColumn(name="role_id", referencedColumnName="id", onDelete="CASCADE")})
     */
    private $roles;

    /**
     * @var string Nombre de usuario interno de la UAH que saco de la conexión de REDIRIS
     * @Column(name="ID_USULDAP", type="string", length= 255, nullable=true), 
     * options={"comments"="ID que me devuelve REDIRIS al hacer la autentificación por OpenId. Lo uso para buscar el resto de la información en UXXIAC.TUIB_PERSONA"})
     * @OneToOne(targetEntity="TuibPersonaUser",inversedBy="id_usuldap")
     * @JoinColumn(name="usuldap_id", referencedColumnName="id_usuldap")
     */
    private $id_usuldap;

    /**
     * @OneToMany(targetEntity="Activity", mappedBy="Organizer")
     * @var type 
     */
    private $activities;
    
    /**
     * @OneToMany(targetEntity="Application", mappedBy="userId")
     * @var type 
     */
    private $applications;
    
    /**
     * @OneToMany(targetEntity="Enrollment", mappedBy="user")
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
     * Set name
     *
     * @param string $name
     * @return User
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
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email) {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Set type
     *
     * @param integer $type
     * @return User
     */
    public function setType($type) {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer 
     */
    public function getType() {
        return $this->type;
    }

    /**
     * Set creationIp
     *
     * @param string $creationIp
     * @return User
     */
    public function setCreationIp($creationIp) {
        $this->creationIp = $creationIp;

        return $this;
    }

    /**
     * Get creationIp
     *
     * @return string 
     */
    public function getCreationIp() {
        return $this->creationIp;
    }

    /**
     * Set degreeId
     *
     * @param \UAH\GestorActividadesBundle\Entity\Degree $degree_id
     * @return User
     */
    public function setDegreeId(\UAH\GestorActividadesBundle\Entity\Degree $degree_id) {
        $this->degree_id = $degree_id;

        return $this;
    }

    /**
     * Get degreeId
     *
     * @return \UAH\GestorActividadesBundle\Entity\Degree 
     */
    public function getDegreeId() {
        return $this->degree_id;
    }

    public function eraseCredentials() {
        
    }

    public function getRoles() {
        $roles = array();
        foreach ($this->roles as $role) {
            $roles[] = $role->getRole();
        }
        return $roles;
    }

    public function getUserRoles() {
        return $this->roles;
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
    public function setUahName($uahName) {
        $this->uahName = $uahName;

        return $this;
    }

    /**
     * Get uahName
     *
     * @return string 
     */
    public function getUahName() {
        return $this->uahName;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->roles = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add roles
     *
     * @param \UAH\GestorActividadesBundle\Entity\Role $roles
     * @return User
     */
    public function addRole(\UAH\GestorActividadesBundle\Entity\Role $roles) {
        $this->roles[] = $roles;

        return $this;
    }

    /**
     * Remove roles
     *
     * @param \UAH\GestorActividadesBundle\Entity\Role $roles
     */
    public function removeRole(\UAH\GestorActividadesBundle\Entity\Role $roles) {
        $this->roles->removeElement($roles);
    }

    /**
     * Set id_usuldap
     *
     * @param string $idUsuldap
     * @return User
     */
    public function setIdUsuldap($idUsuldap) {
        $this->id_usuldap = $idUsuldap;

        return $this;
    }

    /**
     * Get id_usuldap
     *
     * @return string 
     */
    public function getIdUsuldap() {
        return $this->id_usuldap;
    }

    public function getPassword() {
        
    }

    /**
     * Set apellido_1
     *
     * @param string $apellido1
     * @return User
     */
    public function setApellido1($apellido1) {
        $this->apellido_1 = $apellido1;

        return $this;
    }

    /**
     * Get apellido_1
     *
     * @return string 
     */
    public function getApellido1() {
        return $this->apellido_1;
    }

    /**
     * Set apellido_2
     *
     * @param string $apellido2
     * @return User
     */
    public function setApellido2($apellido2) {
        $this->apellido_2 = $apellido2;

        return $this;
    }

    /**
     * Get apellido_2
     *
     * @return string 
     */
    public function getApellido2() {
        return $this->apellido_2;
    }

    /**
     * Set documento_identidad
     *
     * @param string $documentoIdentidad
     * @return User
     */
    public function setDocumentoIdentidad($documentoIdentidad) {
        $this->documento_identidad = $documentoIdentidad;

        return $this;
    }

    /**
     * Get documento_identidad
     *
     * @return string 
     */
    public function getDocumentoIdentidad() {
        return $this->documento_identidad;
    }

    /**
     * Set tipo_documento_identidad
     *
     * @param string $tipoDocumentoIdentidad
     * @return User
     */
    public function setTipoDocumentoIdentidad($tipoDocumentoIdentidad) {
        $this->tipo_documento_identidad = $tipoDocumentoIdentidad;

        return $this;
    }

    /**
     * Get tipo_documento_identidad
     *
     * @return string 
     */
    public function getTipoDocumentoIdentidad() {
        return $this->tipo_documento_identidad;
    }

}
