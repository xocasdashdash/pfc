<?php

namespace UAH\GestorActividadesBundle\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Entity;

/**
 * Degree
 *
 * @Table(name="UAH_GAT_Degree")
 * @Entity
 */
class Degree {

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
     * @Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var array
     *
     * @Column(name="knowledgeArea", type="string")
     */
    private $knowledgeArea;

    /**
     * @var string
     *
     * @Column(name="academicCode", type="string", length=255)
     */
    private $academicCode;
    
    /**
     * @var int Estado del registro
     * @ManyToOne(targetEntity="Statusdegree")
     * @JoinColumn(name="status_degree", referencedColumnName="id")
     */
    private $status;

    
    /**
     * @OneToMany(targetEntity="User", mappedBy="degree_id")
     */
    private $degree_students;
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
     * @return Degree
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
     * Set knowledgeArea
     *
     * @param array $knowledgeArea
     * @return Degree
     */
    public function setKnowledgeArea($knowledgeArea) {
        $this->knowledgeArea = $knowledgeArea;

        return $this;
    }

    /**
     * Get knowledgeArea
     *
     * @return array 
     */
    public function getKnowledgeArea() {
        return $this->knowledgeArea;
    }

    /**
     * Set academicCode
     *
     * @param string $academicCode
     * @return Degree
     */
    public function setAcademicCode($academicCode) {
        $this->academicCode = $academicCode;

        return $this;
    }

    /**
     * Get academicCode
     *
     * @return string 
     */
    public function getAcademicCode() {
        return $this->academicCode;
    }


    /**
     * Set status
     *
     * @param \UAH\GestorActividadesBundle\Entity\Statusdegree $status
     * @return Degree
     */
    public function setStatus(\UAH\GestorActividadesBundle\Entity\Statusdegree $status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return \UAH\GestorActividadesBundle\Entity\Statusdegree 
     */
    public function getStatus()
    {
        return $this->status;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->degree_students = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add degree_students
     *
     * @param \UAH\GestorActividadesBundle\Entity\User $degreeStudents
     * @return Degree
     */
    public function addDegreeStudent(\UAH\GestorActividadesBundle\Entity\User $degreeStudents)
    {
        $this->degree_students[] = $degreeStudents;

        return $this;
    }

    /**
     * Remove degree_students
     *
     * @param \UAH\GestorActividadesBundle\Entity\User $degreeStudents
     */
    public function removeDegreeStudent(\UAH\GestorActividadesBundle\Entity\User $degreeStudents)
    {
        $this->degree_students->removeElement($degreeStudents);
    }

    /**
     * Get degree_students
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDegreeStudents()
    {
        return $this->degree_students;
    }
}
