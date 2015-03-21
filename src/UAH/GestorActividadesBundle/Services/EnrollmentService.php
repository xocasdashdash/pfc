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
use UAH\GestorActividadesBundle\Exceptions\Enrollments as EnrollmentsExceptions;
use UAH\GestorActividadesBundle\Entity\User;
use UAH\GestorActividadesBundle\Entity\Activity;

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

    public function createEnrollment(Activity $activity, User $user)
    {
        if (false === $activity->hasFreePlaces()) {
            throw new EnrollmentsExceptions\activityWithouFreeSpotsException();
        }
        if (false === $user->isProfileComplete()) {
            throw new EnrollmentsExceptions\invalidProfileException();
        }
        if ($this->em->getRepository('UAHGestorActividadesBundle:Statusactivity')->getValidStatus() !== $activity->getStatus()) {
            throw new EnrollmentsExceptions\invalidActivityException();
        }
        if (false === $this->enrollmentRepository->checkEnrolled($user, $activity)) {
            throw new EnrollmentsExceptions\userAlreadyEnrolledException();
        }
        $enrollment = new Enrollment();
        $enrollment->setActivity($activity);
        $enrollment->setUser($user);
        $this->em->persist($enrollment);
        $activity->setNumberOfPlacesOccupied($activity->getNumberOfPlacesOccupied() + 1);
        $this->em->persist($activity);
        $this->em->flush();
        return true;
    }

    /**
     * 
     * @param Enrollment $enrollment
     * @return boolean
     * @throws EnrollmentsExceptions\wrongEnrollmentStatusException
     */
    public function removeEnrollment(Enrollment $enrollment)
    {
        $statusEnrolled = $this->em->getRepository('UAHGestorActividadesBundle:Statusenrollment')->getEnrolledStatus();
        if ($enrollment->getStatus() !== $statusEnrolled) {
            throw new EnrollmentsExceptions\wrongEnrollmentStatusException();
        }
        $this->em->remove($enrollment);
        $this->em->flush();
        return true;
    }

    /**
     * 
     * @param array $recognizements
     * @param Activity $activity
     * @param User $recognizedBy
     * @return boolean
     * @throws EnrollmentsExceptions\invalidRecognizementException
     */
    public function recognizeEnrollments(array $recognizements, Activity $activity, User $recognizedBy)
    {
        $enrollment_ids = array_map(function($e) {
            return isset($e['id']) ? $e['id'] : null;
        }, $recognizements);
        $assocRecognizements = array();
        foreach ($recognizements as $recognizement) {
            $assocRecognizements[$recognizement['id']] = $recognizement;
        }
        /* @var $enrollments Enrollment[] */
        $enrollments = $this->enrollmentRepository->getEnrollmentsByID($enrollment_ids);
        /* @var $statusEnrolled \UAH\GestorActividadesBundle\Entity\Statusenrollment */
        $statusEnrolled = $this->em->getRepository('UAHGestorActividadesBundle:Statusenrollment')->getEnrolledStatus();
        $statusRecognized = $this->em->getRepository('UAHGestorActividadesBundle:Statusenrollment')->getRecognizedStatus();
        $renewedDegreeStatus = $this->em->getRepository('UAHGestorActividadesBundle:Statusdegree')->getRenewed();
        $notRenewedDegreeStatus = $this->em->getRepository('UAHGestorActividadesBundle:Statusdegree')->getRenewed();
        $validDegreeStatusArray = array($renewedDegreeStatus, $notRenewedDegreeStatus);
        $responses = array();
        foreach ($enrollments as $enrollment) {
            $response = array();
            $user = $enrollment->getUser();
            $numberOfCredits = $this->parseNumberOfCredits($assocRecognizements[$enrollment->getId()]);
            $creditRange = $user->getCreditsRange($activity);
            if (false === $creditRange) {
                $response ['type'] = 'error';
                $response ['message'] = 'Usuario sin formato de créditos reconocido';
                $responses[$enrollment->getId()] = $response;
            } else if ($enrollment->getStatus() !== $statusEnrolled ||
                    $enrollment->getActivity() !== $activity) {
                $response['type'] = 'error';
                $response['code'] = self::RECOGNIZEMENT_ERROR_BASIC;
                $response['message'] = 'El estado no es el correcto';
                $responses[$enrollment->getId()] = $response;
            } elseif (!in_array($user->getDegree()->getStatus(), $validDegreeStatusArray)) {
                $response['type'] = 'error';
                $response['code'] = self::RECOGNIZEMENT_ERROR_NO_DEGREE;
                $response['message'] = 'No tiene un plan de estudios valido';
                $responses[$recognizement->id] = $response;
            } elseif (false === $numberOfCredits) {
                $response['type'] = 'error';
                $response['message'] = 'Formato de número incorrecto';
                $responses[$enrollment->getId()] = $response;
            } elseif ($numberOfCredits >= $creditRange['min'] &&
                    $numberOfCredits <= $creditRange['max']) {
                $enrollment->setRecognizedCredits($numberOfCredits);
                $enrollment->setRecognizedByUser($recognizedBy);
                $enrollment->setDateRecognized(new \DateTime(date("c", time())));
                $enrollment->setStatus($statusRecognized);
                $enrollment->setCreditsType($user->getCreditsType());
                $this->em->persist($enrollment);
            } else {
                $response['type'] = 'error';
                $response['message'] = 'Número de créditos fuera de rango';
                $responses[$enrollment->getId()] = $response;
            }
        }
        if (count($responses) > 0) {
            throw new EnrollmentsExceptions\invalidRecognizementException($responses, null, null);
        } else {
            $this->em->flush();
            return true;
        }
    }

    private function parseNumberOfCredits($number_of_credits)
    {
        $numberFormatter = new \NumberFormatter('es_ES', NumberFormatter::DECIMAL);
        $num_credits = $numberFormatter->parse($number_of_credits);

        if (!$num_credits) {
            $numberFormatter = new \NumberFormatter('en_US', NumberFormatter::DECIMAL);
            $num_credits = $numberFormatter->parse($number_of_credits);
            if (!$num_credits) {
                return false;
            }
            return $num_credits;
        }
    }

    public function unrecognizeEnrollments(array $unrecognizements_ids, Activity $activity)
    {
        /* @var $enrollments Enrollment[] */
        $enrollments = $this->enrollmentRepository->getEnrollmentsByID($unrecognizements_ids);
        $defaultStatus = $this->em->getRepository('UAHGestorActividadesBundle:Statusenrollment')
                ->getDefault();
        foreach ($enrollments as $enrollment) {
            if ($enrollment->getActivity() !== $activity) {
                throw new EnrollmentsExceptions\wrongActivityException();
            }
            $enrollment->setStatus($defaultStatus);
            $this->em->persist($enrollment);
        }
        $this->em->flush();
        return true;
    }

}
