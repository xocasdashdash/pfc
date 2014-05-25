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
 * @Table(name="UAH_GAT_v_TuibPersonaUser")
 * @Entity
 */
class TuibPersonaUser implements UserInterface {

    /**
     * @var integer
     *
     * @Column(name="codnum", type="integer")
     * @Id
     * @GeneratedValue(strategy="AUTO")
     */
    private $codnum;

    /**
     * @var string
     *
     * @Column(name="nomprs", type="string", length=32)
     */
    private $nomprs;

    /**
     * @var string
     *
     * @Column(name="email", type="string", length=100, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @Column(name="ll1prs", type="string", length=32, nullable=true)
     */
    private $ll1prs;

    /**
     * @var string
     *
     * @Column(name="ll2prs", type="string", length=32, nullable=true)
     */
    private $ll2prs;

    /**
     * @var string
     *
     * @Column(name="tlfprs", type="string", length=255)
     */
    private $tlfprs;

    /**
     * @var string Nombre de usuario interno de la UAH que saco de la conexión de REDIRIS
     * @Column(name="ID_USULDAP", type="string", length= 100, nullable=false, 
     * options={"comments"="ID que me devuelve REDIRIS al hacer la autentificación por OpenId. Lo uso para buscar el resto de la información en UXXIAC.TUIB_PERSONA"})
     * @OneToOne(targetEntity="User", mappedBy="id_usuldap")
     */
    private $id_usuldap;

    public function eraseCredentials() {
        
    }

    public function getPassword() {
        
    }

    public function getRoles() {
        
    }

    public function getSalt() {
        
    }

    public function getUsername() {
        
    }

    /**
     * Get codnum
     *
     * @return integer 
     */
    public function getCodnum() {
        return $this->codnum;
    }

    /**
     * Set nomprs
     *
     * @param string $nomprs
     * @return v_TuibPersonaUser
     */
    public function setNomprs($nomprs) {
        $this->nomprs = $nomprs;

        return $this;
    }

    /**
     * Get nomprs
     *
     * @return string 
     */
    public function getNomprs() {
        return $this->nomprs;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return v_TuibPersonaUser
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
     * Set ll1prs
     *
     * @param string $ll1prs
     * @return v_TuibPersonaUser
     */
    public function setLl1prs($ll1prs) {
        $this->ll1prs = $ll1prs;

        return $this;
    }

    /**
     * Get ll1prs
     *
     * @return string 
     */
    public function getLl1prs() {
        return $this->ll1prs;
    }

    /**
     * Set ll2prs
     *
     * @param string $ll2prs
     * @return v_TuibPersonaUser
     */
    public function setLl2prs($ll2prs) {
        $this->ll2prs = $ll2prs;

        return $this;
    }

    /**
     * Get ll2prs
     *
     * @return string 
     */
    public function getLl2prs() {
        return $this->ll2prs;
    }

    /**
     * Set tlfprs
     *
     * @param string $tlfprs
     * @return v_TuibPersonaUser
     */
    public function setTlfprs($tlfprs) {
        $this->tlfprs = $tlfprs;

        return $this;
    }

    /**
     * Get tlfprs
     *
     * @return string 
     */
    public function getTlfprs() {
        return $this->tlfprs;
    }

    /**
     * Set id_usuldap
     *
     * @param string $idUsuldap
     * @return v_TuibPersonaUser
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

    public function create_id_usuldap() {
        $id_usuldap = strtolower(static::toAscii("http://yo.rediris.es/soy/" . $this->getNomprs() . "." . $this->getLl1prs() . "@uah.es"));
        $id_usuldap = preg_replace('/\s+/', '', $id_usuldap);
        $this->id_usuldap = $id_usuldap;
    }
    public function create_email(){
        $email = strtolower(static::toAscii($this->getNomprs() . "." . $this->getLl1prs() . "@uah.es"));
        $email = preg_replace('/\s+/', '', $email);
        $this->email =$email;
    }

    private static function toAscii($string) {
        $from = array('á', 'Á', 'é', 'É', 'í', 'Í', 'ó', 'Ó', 'ú', 'Ú', 'ü', 'Ü', 'ñ', 'Ñ');
        $to = array('a', 'A', 'e', 'E', 'i', 'I', 'o', 'O', 'u', 'U', 'u', 'U', 'n', 'N');

        return str_replace($from, $to, $string);
    }

}
