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
use DateTime;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;

/**
 * User
 *
 * @Table(name="UAH_GAT_User")
 * @Entity(repositoryClass="UAH\GestorActividadesBundle\Repository\UserRepository")
 * @HasLifecycleCallbacks
 */
class User implements UserInterface
{
    const CREDIT_TYPE_ECTS = 'ECTS';
    const CREDIT_TYPE_LIBRE = 'LIBRE';
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
     *
     * @var date Fecha en la que se creo el usuario.
     * @Column(name="date_created",type="datetime", nullable=false)
     */
    private $date_created;

    /**
     *
     * @var date Fecha en la que se modificó el usuario.
     * @Column(name="date_updated",type="datetime", nullable=false)
     */
    private $date_updated;

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
     * @JoinColumn(name="degree_id", referencedColumnName="id", nullable=true,onDelete="SET NULL")
     */
    private $degree;

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
     * @OneToMany(targetEntity="Application", mappedBy="user")
     * @var type
     */
    private $applications;

    /**
     * @OneToMany(targetEntity="Enrollment", mappedBy="user")
     * @var type
     */
    private $enrollments;

    /**
     * @OneToMany(targetEntity="Application", mappedBy="verifiedByUser")
     * @var Applications
     */
    private $verifiedApplications;

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
     * @param  string $name
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
     * @param  string $email
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
     * Set creationIp
     *
     * @param  string $creationIp
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
     * @param  \UAH\GestorActividadesBundle\Entity\Degree $degree
     * @return User
     */
    public function setDegree(\UAH\GestorActividadesBundle\Entity\Degree $degree)
    {
        $this->degree = $degree;

        return $this;
    }

    /**
     * Get degreeId
     *
     * @return \UAH\GestorActividadesBundle\Entity\Degree
     */
    public function getDegree()
    {
        return $this->degree;
    }

    public function eraseCredentials()
    {
    }

    public function getRoles()
    {
        $roles = array();
        foreach ($this->roles as $role) {
            $roles[] = $role->getRole();
        }

        return $roles;
    }

    public function getUserRoles()
    {
        return $this->roles;
    }

    public function getSalt()
    {
    }

    public function getUsername()
    {
    }

    /**
     * Set uahName
     *
     * @param  string $uahName
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
     * @param  \UAH\GestorActividadesBundle\Entity\Role $roles
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

    /**
     * Set id_usuldap
     *
     * @param  string $idUsuldap
     * @return User
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

    public function getPassword()
    {
    }

    /**
     * Set apellido_1
     *
     * @param  string $apellido1
     * @return User
     */
    public function setApellido1($apellido1)
    {
        $this->apellido_1 = $apellido1;

        return $this;
    }

    /**
     * Get apellido_1
     *
     * @return string
     */
    public function getApellido1()
    {
        return $this->apellido_1;
    }

    /**
     * Set apellido_2
     *
     * @param  string $apellido2
     * @return User
     */
    public function setApellido2($apellido2)
    {
        $this->apellido_2 = $apellido2;

        return $this;
    }

    /**
     * Get apellido_2
     *
     * @return string
     */
    public function getApellido2()
    {
        return $this->apellido_2;
    }

    /**
     * Set documento_identidad
     *
     * @param  string $documentoIdentidad
     * @return User
     */
    public function setDocumentoIdentidad($documentoIdentidad)
    {
        $this->documento_identidad = $documentoIdentidad;

        return $this;
    }

    /**
     * Get documento_identidad
     *
     * @return string
     */
    public function getDocumentoIdentidad()
    {
        return $this->documento_identidad;
    }

    /**
     * Set tipo_documento_identidad
     *
     * @param  string $tipoDocumentoIdentidad
     * @return User
     */
    public function setTipoDocumentoIdentidad($tipoDocumentoIdentidad)
    {
        $this->tipo_documento_identidad = $tipoDocumentoIdentidad;

        return $this;
    }

    /**
     * Get tipo_documento_identidad
     *
     * @return string
     */
    public function getTipoDocumentoIdentidad()
    {
        return $this->tipo_documento_identidad;
    }

    /**
     * Add activities
     *
     * @param  \UAH\GestorActividadesBundle\Entity\Activity $activities
     * @return User
     */
    public function addActivity(\UAH\GestorActividadesBundle\Entity\Activity $activities)
    {
        $this->activities[] = $activities;

        return $this;
    }

    /**
     * Remove activities
     *
     * @param \UAH\GestorActividadesBundle\Entity\Activity $activities
     */
    public function removeActivity(\UAH\GestorActividadesBundle\Entity\Activity $activities)
    {
        $this->activities->removeElement($activities);
    }

    /**
     * Get activities
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getActivities()
    {
        return $this->activities;
    }

    /**
     * Add applications
     *
     * @param  \UAH\GestorActividadesBundle\Entity\Application $applications
     * @return User
     */
    public function addApplication(\UAH\GestorActividadesBundle\Entity\Application $applications)
    {
        $this->applications[] = $applications;

        return $this;
    }

    /**
     * Remove applications
     *
     * @param \UAH\GestorActividadesBundle\Entity\Application $applications
     */
    public function removeApplication(\UAH\GestorActividadesBundle\Entity\Application $applications)
    {
        $this->applications->removeElement($applications);
    }

    /**
     * Get applications
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getApplications()
    {
        return $this->applications;
    }

    /**
     * Add enrollments
     *
     * @param  \UAH\GestorActividadesBundle\Entity\Enrollment $enrollments
     * @return User
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

    /**
     * Add verifiedApplications
     *
     * @param  \UAH\GestorActividadesBundle\Entity\Application $verifiedApplications
     * @return User
     */
    public function addVerifiedApplication(\UAH\GestorActividadesBundle\Entity\Application $verifiedApplications)
    {
        $this->verifiedApplications[] = $verifiedApplications;

        return $this;
    }

    /**
     * Remove verifiedApplications
     *
     * @param \UAH\GestorActividadesBundle\Entity\Application $verifiedApplications
     */
    public function removeVerifiedApplication(\UAH\GestorActividadesBundle\Entity\Application $verifiedApplications)
    {
        $this->verifiedApplications->removeElement($verifiedApplications);
    }

    /**
     * Get verifiedApplications
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVerifiedApplications()
    {
        return $this->verifiedApplications;
    }

    public function isProfileComplete()
    {
        $resultado = true;
        $resultado &= strlen($this->getName()) > 0;
        $resultado &= strlen($this->getApellido1()) > 0;
        $resultado &= strlen($this->getDocumentoIdentidad()) > 0;
        $resultado &= strlen($this->getEmail()) > 0;
        $resultado &= (is_null($this->getDegree()) === false);
        $resultado &= strlen($this->getTipoDocumentoIdentidad()) > 0;

        return $resultado;
    }

    public function getCreditsType()
    {
        $degree = $this->getDegree();
        if (!is_null($degree)) {
            if ($degree->getStatus()->getCode() === 'STATUS_RENEWED') {
                return self::CREDIT_TYPE_ECTS;
            }
            if ($degree->getStatus()->getCode() === 'STATUS_NON_RENEWED') {
                return self::CREDIT_TYPE_LIBRE;
            }
        } else {
            return;
        }
    }

    /**
     * Set type
     *
     * @param  string $type
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
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set date_created
     *
     * @param  \DateTime $dateCreated
     * @return User
     */
    public function setDateCreated($dateCreated)
    {
        $this->date_created = $dateCreated;

        return $this;
    }

    /**
     * Get date_created
     *
     * @return \DateTime
     */
    public function getDateCreated()
    {
        return $this->date_created;
    }

    /**
     * Set date_updated
     *
     * @param  \DateTime $dateUpdated
     * @return User
     */
    public function setDateUpdated($dateUpdated)
    {
        $this->date_updated = $dateUpdated;

        return $this;
    }

    /**
     * Get date_updated
     *
     * @return \DateTime
     */
    public function getDateUpdated()
    {
        return $this->date_updated;
    }

    public function getUAHUser()
    {
        $pattern = '/^https?:\/\/yo\.rediris\.es\/soy\/(.+)@\w+\.+[a-z]{2,4}\/?/';
        if (preg_match($pattern, $this->getIdUsuldap(), $matches) === 1) {
            return $matches[0];
        } else {
            return 'No encontrado';
            //return 'http://yo.rediris.es/soy/adrian.bolonio@uah.es';
        }
    }
}
