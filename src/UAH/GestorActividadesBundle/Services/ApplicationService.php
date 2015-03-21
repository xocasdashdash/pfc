<?php

namespace UAH\GestorActividadesBundle\Services;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use UAH\GestorActividadesBundle\Repository\ApplicationRepository;
use Doctrine\ORM\EntityManager;
use UAH\GestorActividadesBundle\Entity\Application;
use UAH\GestorActividadesBundle\Entity\User;
use UAH\GestorActividadesBundle\Entity\Enrollment;
use UAH\GestorActividadesBundle\Exceptions\Applications as ApplicationExceptions;

class ApplicationService
{

    protected $applicationRepository;
    protected $em;

    public function __construct(EntityManager $em, ApplicationRepository $applicationRepository)
    {
        $this->em = $em;
        $this->applicationRepository = $applicationRepository;
    }

    /**
     * 
     * @param array $enrollment_ids
     * @param User $user
     * @throws ApplicationExceptions\enrollmentNotRecognizedException
     * @throws ApplicationExceptions\enrollmentAlreadyUsed
     * @throws ApplicationExceptions\notYourEnrollmentException
     * @return Application
     */
    public function createApplication(array $enrollment_ids, User $user)
    {
        /* @var $enrollments Enrollment[] */
        $enrollments = $this->checkEnrollmentCredits($enrollment_ids, $user->getCreditsType());
        $application = new Application();
        $application->setStatus($this->em->getRepository('UAHGestorActividadesBundle:Statusapplication')->getDefault());
        $application->setApplicationDateCreated(new \DateTime(date("c", time())));
        $sr = new \Symfony\Component\Security\Core\Util\SecureRandom();
        $application->setVerificationCode(bin2hex($sr->nextBytes(10)));
        $application->setUser($this->getUser());
        foreach ($enrollments as $enrollment) {
            $this->addEnrollment($application, $enrollment, $user);
        }
        $application->setTypeOfCredits($user->getCreditsType());
        $this->em->persist($application);
        $this->em->flush();
        return $application;
    }

    public function deleteApplication(Application $application, User $user)
    {
        /* @var $enrollments Enrollment[] */
        $enrollments = $application->getEnrollments();
        foreach ($enrollments as $enrollment) {
            $this->removeEnrollment($enrollment, $user);
        }
        $this->em->remove($application);
        $this->em->flush();
        return true;
    }

    /**
     * 
     * @param Application $application
     * @param User $user
     * @return boolean
     * @throws ApplicationExceptions\notYourApplicationException
     * @throws ApplicationExceptions\applicationNotArchived
     */
    public function archiveApplication(Application $application, User $user)
    {
        /* @var $statusApplicationRepository \UAH\GestorActividadesBundle\Repository\StatusApplicationRepository */
        $statusApplicationRepository = $this->em->getRepository('UAHGestorActividadesBundle:Statusapplication');
        $statusVerified = $statusApplicationRepository->getVerified();
        $statusArchived = $statusApplicationRepository->getArchived();
        if ($application->getStatus() === $statusVerified &&
                $application->getUser() === $user) {
            $application->setStatus($statusArchived);
            $this->em->persist($application);
            $this->em->flush();
            return true;
        } elseif ($application->getStatus() === $statusVerified && $application->getUser() !== $user) {
            throw new ApplicationExceptions\notYourApplicationException();
        } elseif ($application->getStatus() === $statusArchived && $application->getUser() === $user) {
            throw new ApplicationExceptions\applicationNotArchived();
        } elseif ($application->getStatus() === $statusArchived && $application->getUser() !== $user) {
            throw new ApplicationExceptions\notYourApplicationException();
        }
    }

    /**
     * 
     * @param Application $application
     * @param Enrollment $enrollment
     * @param User $u
     * @throws ApplicationExceptions\enrollmentNotRecognizedException
     * @throws ApplicationExceptions\enrollmentAlreadyUsed
     * @throws ApplicationExceptions\notYourEnrollmentException
     */
    private function addEnrollment(Application $application, Enrollment $enrollment, User $u)
    {
        /* @var $statusEnrollmentRepo \UAH\GestorActividadesBundle\Repository\StatusEnrollmentRepository */
        $statusEnrollmentRepo = $this->em->getRepository('UAHGestorActividadesBundle:Statusenrollment');
        $status_recognized = $statusEnrollmentRepo->getRecognizedStatus();
        if ($enrollment->getStatus() !== $status_recognized) {
            throw new ApplicationExceptions\enrollmentNotRecognizedException();
        } else if (false === is_null($enrollment->getApplication())) {
            throw new ApplicationExceptions\enrollmentAlreadyUsed();
        } else if ($u !== $enrollment->getUser()) {
            throw new ApplicationExceptions\notYourEnrollmentException();
        }
        $application->addEnrollment($enrollment);
        $enrollment->setStatus($statusEnrollmentRepo->getPendingVerificationStatus());
        $this->em->persist($enrollment);
    }

    /**
     * 
     * @param Enrollment $enrollment
     * @param User $user
     * @throws ApplicationExceptions\applicationNotDeleted
     * @return boolean
     */
    private function removeEnrollment(Enrollment $enrollment, User $user)
    {
        if ($enrollment->getUser() !== $user) {
            throw new ApplicationExceptions\notYourEnrollmentException();
        }
        /* @var $statusEnrollmentRepo \UAH\GestorActividadesBundle\Repository\StatusEnrollmentRepository */
        $statusEnrollmentRepo = $this->em->getRepository('UAHGestorActividadesBundle:Statusenrollment');
        $status_recognized = $statusEnrollmentRepo->getRecognizedStatus();
        $enrollment->setStatus($status_recognized);
        $enrollment->setApplication(null);
        $this->em->persist($enrollment);
    }

    /**
     * 
     * @param array $enrollment_ids
     * @param type $creditType
     * @return Enrollment[]
     * @throws ApplicationExceptions\applicationMixedTypeOfCredits
     * @throws ApplicationExceptions\noEnrollmentsWithThoseIDsException
     */
    private function checkEnrollmentCredits(array $enrollment_ids, $creditType)
    {
        $enrollments = $this->enrollmentRepository->getEnrollmentsByID($enrollment_ids);
        /* @var $enrollments Enrollment[] */
        foreach ($enrollments as $enrollment) {
            if ($creditType !== $enrollment->getCreditsType()) {
                throw new ApplicationExceptions\applicationMixedTypeOfCredits();
            }
        }
        if (count($enrollments) === 0) {
            throw new ApplicationExceptions\noEnrollmentsWithThoseIDsException();
        }
        return $enrollments;
    }

    public function verifyApplication(Application $application, User $user)
    {
        /* @var $statusApplicationRepository \UAH\GestorActividadesBundle\Repository\StatusApplicationRepository */
        $statusApplicationRepository = $this->em->getRepository('UAHGestorActividadesBundle:Statusapplication');
        $defaultStatus = $statusApplicationRepository->getDefault();
        $verifiedApplicationStatus = $statusApplicationRepository->getVerified();

        if ($application->getStatus() !== $defaultStatus) {
            throw new ApplicationExceptions\applicationNotVerified();
        }
        $application->setStatus($verifiedApplicationStatus);
        $application->setApplicationDateVerified(new \DateTime());
        $verifiedEnrollmentStatus = $em->getRepository('UAHGestorActividadesBundle:Statusenrollment')->getVerified();
        foreach ($application->getEnrollments() as $enrollment) {
            $enrollment->setStatus($verifiedEnrollmentStatus);
            $this->em->persist($enrollment);
        }
        $em->persist($application);
        $em->flush();
        return true;
    }

}
