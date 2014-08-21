<?php

namespace UAH\GestorActividadesBundle\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * Degree
 *
 * @Table(name="UAH_GAT_Category",uniqueConstraints={@UniqueConstraint(name="search_idx", columns={"hash_category"})})
 * @Entity(repositoryClass="UAH\GestorActividadesBundle\Repository\CategoryRepository")
 */
class Category {

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
     * @Column(name="name", type="string", length=400)
     */
    private $name;

    /**
     * @Column(name="hash_category", type="string", length=40)
     */
    private $hash;

    /**
     * @var int Estado del registro
     * @ManyToOne(targetEntity="Statuscategory")
     * @JoinColumn(name="status_category", referencedColumnName="id")
     */
    private $status;

    /**
     * @ManyToMany(targetEntity="Activity", inversedBy="categories",cascade={"persist"})
     * @JoinTable(name="UAH_GAT_Activities_Categories",
     * joinColumns={@JoinColumn(name="activity_id", referencedColumnName="id", onDelete="RESTRICT")},
     * inverseJoinColumns={@JoinColumn(name="category_id", referencedColumnName="id", onDelete="RESTRICT")})
     */
    private $activities;

    /**
     * @OneToOne(targetEntity="Category")
     * @JoinColumn(name="parent_category_id", referencedColumnName="id")
     */
    private $parent_category;

    /**
     * Constructor
     */
    public function __construct() {
        $this->activities = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Category
     */
    public function setName($name) {
        $this->hash = sha1($name . (is_null($this->getStatus()) ? null : $this->getStatus()->getId()) .
                (is_null($this->getParentCategory()) ? null : $this->getParentCategory()->getId()));
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
     * Get hash
     *
     * @return string 
     */
    public function getHash() {
        return $this->hash;
    }

    /**
     * Set status
     *
     * @param \UAH\GestorActividadesBundle\Entity\Statuscategory $status
     * @return Category
     */
    public function setStatus(\UAH\GestorActividadesBundle\Entity\Statuscategory $status = null) {

        $this->hash = sha1($this->getName() . (is_null($status) ? null : $status->getId()) .
                (is_null($this->getParentCategory()) ? null : $this->getParentCategory()->getId()));

        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return \UAH\GestorActividadesBundle\Entity\Statuscategory 
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * Add activities
     *
     * @param \UAH\GestorActividadesBundle\Entity\Activity $activities
     * @return Category
     */
    public function addActivity(\UAH\GestorActividadesBundle\Entity\Activity $activities) {
        $this->activities[] = $activities;

        return $this;
    }

    /**
     * Remove activities
     *
     * @param \UAH\GestorActividadesBundle\Entity\Activity $activities
     */
    public function removeActivity(\UAH\GestorActividadesBundle\Entity\Activity $activities) {
        $this->activities->removeElement($activities);
    }

    /**
     * Get activities
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getActivities() {
        return $this->activities;
    }

    /**
     * Set parent_category
     *
     * @param \UAH\GestorActividadesBundle\Entity\Category $parentCategory
     * @return Category
     */
    public function setParentCategory(\UAH\GestorActividadesBundle\Entity\Category $parentCategory = null) {
        $this->parent_category = $parentCategory;
        
        $this->hash = sha1($this->getName() . (is_null($this->getStatus()) ? null : $this->getStatus()->getId()) .
                (is_null($parentCategory) ? null : $this->getParentCategory()->getId()));

        return $this;
    }

    /**
     * Get parent_category
     *
     * @return \UAH\GestorActividadesBundle\Entity\Category 
     */
    public function getParentCategory() {
        return $this->parent_category;
    }

}
