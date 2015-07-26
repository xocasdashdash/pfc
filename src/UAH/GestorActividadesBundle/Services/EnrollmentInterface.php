<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace UAH\GestorActividadesBundle\Services;

use UAH\GestorActividadesBundle\Entity\Activity;
use UAH\GestorActividadesBundle\Entity\Enrollment;
use UAH\GestorActividadesBundle\Errors\Enrollments\activityWithoutFreeSpotsError;

/**
 *
 * @author xokas
 */
interface EnrollmentInterface
{

    /**
     * 
     * @param \UAH\GestorActividadesBundle\Services\Activity $activity
     * @param \UAH\GestorActividadesBundle\Services\User $user
     * @return bool|activityWithoutFreeSpotsError Description
     */
    public function createEnrollment(Activity $activity, User $user);

    /**
     * 
     * @param \UAH\GestorActividadesBundle\Services\Enrollment $enrollment
     */
    public function removeEnrollment(Enrollment $enrollment);

    /**
     * 
     * @param array $enrollmentsToRecognize
     * @param \UAH\GestorActividadesBundle\Services\Activity $activity
     * @param \UAH\GestorActividadesBundle\Services\User $recognizedBy
     */
    public function recognizeEnrollments(array $enrollmentsToRecognize, Activity $activity, User $recognizedBy);

    /**
     * 
     * @param array $unrecognizements_ids
     * @param \UAH\GestorActividadesBundle\Services\Activity $activity
     * @return bool|\UAH\GestorActividadesBundle\Errors\AbstractError Description
     */
    public function unrecognizeEnrollments(array $unrecognizements_ids, Activity $activity);
}