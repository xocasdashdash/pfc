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
use UAH\GestorActividadesBundle\Errors\Enrollments as EnrollmentsErrors;
use UAH\GestorActividadesBundle\Entity\User;
use UAH\GestorActividadesBundle\Entity\Activity;
use NumberFormatter;

class EnrollmentService implements EnrollmentInterface
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
            return new EnrollmentsErrors\activityWithoutFreeSpotsError();
        }
        if (false === $user->isProfileComplete()) {
            return new EnrollmentsErrors\invalidProfileError();
        }
        if ($this->em->getRepository('UAHGestorActividadesBundle:Statusactivity')->getValidStatus() !== $activity->getStatus()) {
            return new EnrollmentsErrors\invalidActivityError();
        }
        if (true === $this->enrollmentRepository->checkEnrolled($user, $activity)) {
            return new EnrollmentsErrors\userAlreadyEnrolledError();
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
     */
    public function removeEnrollment(Enrollment $enrollment)
    {
        $statusEnrolled = $this->em->getRepository('UAHGestorActividadesBundle:Statusenrollment')->getEnrolledStatus();
        if ($enrollment->getStatus() !== $statusEnrolled) {
            return new EnrollmentsErrors\wrongEnrollmentStatusError();
        }
        $activity = $enrollment->getActivity();
        $activity->setNumberOfPlacesOccupied($activity->getNumberOfPlacesOccupied() - 1);
        $this->em->persist($activity);
        $this->em->remove($enrollment);
        $this->em->flush();
        return true;
    }

    /**
     *
     * @param array $enrollmentsToRecognize
     * @param Activity $activity
     * @param User $recognizedBy
     * @return boolean
     */
    public function recognizeEnrollments(array $enrollmentsToRecognize, Activity $activity, User $recognizedBy)
    {
        $enrollment_ids = array_map(function ($e) {
            return isset($e['id']) ? $e['id'] : null;
        }, $enrollmentsToRecognize);
        $assocEnrollmentsToRecognize = array();
        foreach ($enrollmentsToRecognize as $enrollment) {
            $assocEnrollmentsToRecognize[$enrollment['id']] = $enrollment;
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
            $numberOfCredits = $this->parseNumberOfCredits($assocEnrollmentsToRecognize[$enrollment->getId()]);
            $creditRange = $user->getCreditsRange($activity);
            if (false === $creditRange) {
                $response ['type'] = 'error';
                $response ['message'] = 'Usuario sin formato de créditos reconocido';
            } elseif ($enrollment->getStatus() !== $statusEnrolled ||
                    $enrollment->getActivity() !== $activity) {
                $response['type'] = 'error';
                $response['code'] = self::RECOGNIZEMENT_ERROR_BASIC;
                $response['message'] = 'El estado no es el correcto';
            } elseif (!in_array($user->getDegree()->getStatus(), $validDegreeStatusArray)) {
                $response['type'] = 'error';
                $response['code'] = self::RECOGNIZEMENT_ERROR_NO_DEGREE;
                $response['message'] = 'No tiene un plan de estudios valido';
            } elseif (false === $numberOfCredits) {
                $response['type'] = 'error';
                $response['message'] = 'Formato de número incorrecto';
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
            }
            if (!empty($response)) {
                $responses[$enrollment->getId()] = $response;
            }
        }
        if (count($responses) > 0) {
            return new EnrollmentsErrors\invalidRecognizementError($responses);
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
                return new EnrollmentsErrors\wrongActivityError();
            }
            $enrollment->setStatus($defaultStatus);
            $this->em->persist($enrollment);
        }
        $this->em->flush();
        return true;
    }
}