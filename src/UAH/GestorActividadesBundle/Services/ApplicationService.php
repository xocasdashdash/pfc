<?php

namespace UAH\GestorActividadesBundle\Services;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use UAH\GestorActividadesBundle\Repository\ApplicationRepository;
use UAH\GestorActividadesBundle\Repository\EnrollmentRepository;
use Doctrine\ORM\EntityManager;
use UAH\GestorActividadesBundle\Entity\Application;
use UAH\GestorActividadesBundle\Entity\User;
use UAH\GestorActividadesBundle\Entity\Enrollment;
use UAH\GestorActividadesBundle\Errors\Applications as ApplicationErrors;

class ApplicationService
{
    protected $applicationRepository;
    protected $em;
    private $enrollmentRepository;

    public function __construct(EntityManager $em, ApplicationRepository $applicationRepository, EnrollmentRepository $enrollmentRepository)
    {
        $this->em = $em;
        $this->applicationRepository = $applicationRepository;
        $this->enrollmentRepository = $enrollmentRepository;
    }

    /**
     *
     * @param array $enrollment_ids
     * @param User $user
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
        $application->setUser($user);
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
            return new ApplicationErrors\notYourApplicationError();
        } elseif ($application->getStatus() === $statusArchived && $application->getUser() === $user) {
            return new ApplicationErrors\applicationNotArchivedError();
        } elseif ($application->getStatus() === $statusArchived && $application->getUser() !== $user) {
            return new ApplicationErrors\notYourApplicationError();
        }
    }

    /**
     *
     * @param Application $application
     * @param Enrollment $enrollment
     * @param User $u
     */
    private function addEnrollment(Application $application, Enrollment $enrollment, User $u)
    {
        /* @var $statusEnrollmentRepo \UAH\GestorActividadesBundle\Repository\StatusEnrollmentRepository */
        $statusEnrollmentRepo = $this->em->getRepository('UAHGestorActividadesBundle:Statusenrollment');
        $status_recognized = $statusEnrollmentRepo->getRecognizedStatus();
        if ($enrollment->getStatus() !== $status_recognized) {
            return new ApplicationErrors\enrollmentNotRecognizedError();
        } elseif (false === is_null($enrollment->getApplication())) {
            return new ApplicationErrors\enrollmentAlreadyUsedError();
        } elseif ($u !== $enrollment->getUser()) {
            return new ApplicationErrors\notYourEnrollmentError();
        }
        $application->addEnrollment($enrollment);
        $enrollment->setStatus($statusEnrollmentRepo->getPendingVerificationStatus());
        $this->em->persist($enrollment);
    }

    /**
     *
     * @param Enrollment $enrollment
     * @param User $user
     * @return boolean
     */
    private function removeEnrollment(Enrollment $enrollment, User $user)
    {
        if ($enrollment->getUser() !== $user) {
            return new ApplicationErrors\notYourEnrollmentError();
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
     */
    private function checkEnrollmentCredits(array $enrollment_ids, $creditType)
    {
        $enrollments = $this->enrollmentRepository->getEnrollmentsByID($enrollment_ids);
        /* @var $enrollments Enrollment[] */
        foreach ($enrollments as $enrollment) {
            if ($creditType !== $enrollment->getCreditsType()) {
                return new ApplicationErrors\applicationMixedTypeOfCreditsError();
            }
        }
        if (count($enrollments) === 0) {
            return new ApplicationErrors\noEnrollmentsWithThoseIDsError();
        }
        return $enrollments;
    }

    public function verifyApplication(Application $application, User $verifiedBy)
    {
        /* @var $statusApplicationRepository \UAH\GestorActividadesBundle\Repository\StatusApplicationRepository */
        $statusApplicationRepository = $this->em->getRepository('UAHGestorActividadesBundle:Statusapplication');
        $defaultStatus = $statusApplicationRepository->getDefault();
        $verifiedApplicationStatus = $statusApplicationRepository->getVerified();

        if ($application->getStatus() !== $defaultStatus) {
            return new ApplicationErrors\applicationNotVerifiedError();
        }
        $application->setStatus($verifiedApplicationStatus);
        $application->setVerifiedByUser($verifiedBy);
        $application->setApplicationDateVerified(new \DateTime());
        $verifiedEnrollmentStatus = $this->em->getRepository('UAHGestorActividadesBundle:Statusenrollment')->getVerified();
        foreach ($application->getEnrollments() as $enrollment) {
            $enrollment->setStatus($verifiedEnrollmentStatus);
            $this->em->persist($enrollment);
        }
        $this->em->persist($application);
        $this->em->flush();
        return true;
    }
}
