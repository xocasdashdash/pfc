<?php

namespace UAH\GestorActividadesBundle\Services;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use UAH\GestorActividadesBundle\Repository\EnrollmentRepository;
use Doctrine\ORM\EntityManager;
use UAH\GestorActividadesBundle\Entity\Enrollment;
use UAH\GestorActividadesBundle\Entity\Application;

class EnrollmentService
{
    /* @var $enrollmentRepository EnrollmentRepository */

    protected $enrollmentRepository;
    /* @var $em EntityManager */
    protected $em;

    public function __construct(EntityManager $em, EnrollmentRepository $er)
    {
        $this->em = $em;
        $this->enrollmentRepository = $er;
    }

    public function createEnrollment()
    {
        
    }

    public function removeEnrollment()
    {
        
    }

}
