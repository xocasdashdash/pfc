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
use Doctrine\ORM\Mapping\InheritanceType;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
/**
 * @Table(name="UAH_GAT_Status")
 * @Entity
 * @InheritanceType("SINGLE_TABLE")
 * @DiscriminatorColumn(name="discr", type="string")
 * @DiscriminatorMap({"activity" = "Status_activity", "enrollment" = "Status_enrollment"})
 */
class Status{
    
    /**
     * @Column(name="id",type="integer")
     * @Id
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     *
     * @var String Cadena que uso para saber en que clase se usa el estado 
     * Todas los nombres siguen el esquema STATUS_{descripcion sin espacios}
     * @Column(name="name_es", type="string", length=255, nullable=false,
     * options={
     * "comments"="Cadena que uso para mostrar en que estado esta. En castellano"
     * })
     * 
     */
    private $name_es;
    
    /**
     *
     * @var String Cadena que uso para saber en que clase se usa el estado 
     * Todas los nombres siguen el esquema STATUS_{descripcion sin espacios}
     * @Column(name="name_en", type="string", length=255, nullable=false,
     * options={
     * "comments"="Cadena que uso para mostrar en que estado esta. En ingles"
     * })
     * 
     */
    private $name_en;
    
    /**
     *
     * @var String Cadena que uso para saber en que clase se usa el estado 
     * Todas los nombres siguen el esquema STATUS_{descripcion sin espacios}
     * @Column(name="status", type="string", length=255, nullable=false,
     * options={
     * "comments"="Cadena que uso para saber en que clase se usa el estado.Todos los nombres siguen el esquema STATUS_{descripcion sin espacios}"
     * })
     * 
     */
    private $status;  
    
    
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
     * Set name_es
     *
     * @param string $nameEs
     * @return Status
     */
    public function setNameEs($nameEs)
    {
        $this->name_es = $nameEs;

        return $this;
    }

    /**
     * Get name_es
     *
     * @return string 
     */
    public function getNameEs()
    {
        return $this->name_es;
    }

    /**
     * Set name_en
     *
     * @param string $nameEn
     * @return Status
     */
    public function setNameEn($nameEn)
    {
        $this->name_en = $nameEn;

        return $this;
    }

    /**
     * Get name_en
     *
     * @return string 
     */
    public function getNameEn()
    {
        return $this->name_en;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return Status
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }
}