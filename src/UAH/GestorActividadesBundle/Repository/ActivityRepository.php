<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace UAH\GestorActividadesBundle\Repository;

use Doctrine\ORM\EntityRepository;
use UAH\GestorActividadesBundle\Entity\Activity;
use UAH\GestorActividadesBundle\Entity\Statusactivity;

class ActivityRepository extends EntityRepository {

    /**
     * A partir de un array de id's devuelvo los objetos actividades
     * @param type $activities
     * @return null
     */
    public function getActivitiesByID($activities) {
        $em = $this->getEntityManager();
        if (count($activities) > 0) {
//        $dql = " SELECT e.id, a, " .
//                " se.code as status_enrollment, " .
//                " sd.code as status_degree " .
//                " FROM UAHGestorActividadesBundle:Enrollment e " .
//                " JOIN UAHGestorActividadesBundle:User u " .
//                " WITH u.id = e.user " .
//                " JOIN UAHGestorActividadesBundle:Statusenrollment se " .
//                " WITH e.status = se.id " .
//                " JOIN UAHGestorActividadesBundle:Degree d " .
//                " WITH d.id = u.degree_id " .
//                " JOIN UAHGestorActividadesBundle:Statusdegree sd " .
//                " WITH d.status = sd.id " .
//                " JOIN UAHGestorActividadesBundle:Activity a " .
//                " WITH e.activity = a.id " .
//                " WHERE e IN (:enrollments) " .
//                " ORDER BY e.id ASC";
            $dql = " SELECT a " .
                    " FROM UAHGestorActividadesBundle:Activity a " .
                    " WHERE a IN (:activities) " .
                    " ORDER BY a.id ASC";
            $consulta = $em->createQuery($dql);
            $consulta->setParameter('activities', $activities);
            $results = $consulta->getResult();
            return $results;
        } else {
            return null;
        }
    }

    /**
     * Devuelvo aquellas actividades cuyo estado no sea cerrado usado en la vista myactivities
     * 
     * @param \UAH\GestorActividadesBundle\Entity\User $user
     * @return type
     */
    public function getNotClosedActivities(\UAH\GestorActividadesBundle\Entity\User $user) {
        $em = $this->getEntityManager();
        $dql = " SELECT a " .
                " FROM UAHGestorActividadesBundle:Activity a " .
                " WHERE a.Organizer = :user " .
                " AND a.status != :closed_status" .
                " ORDER BY a.id ASC";

        $a = "SELECT a FROM UAHGestorActividadesBundle:Activity a WHEREa a.User = :user AND WHEREr a.Status != :closed_status ORDER BY a.id ASC";
        $consulta = $em->createQuery($dql);
        $consulta->setParameter('user', $user);
        $consulta->setParameter('closed_status', $em->getRepository('UAHGestorActividadesBundle:Statusactivity')->getClosed());
        $results = $consulta->getResult();
        return $results;
    }

    /**
     * 
     * @param \UAH\GestorActividadesBundle\Repository\Activity $activity
     */
    public function isFullyEditable(Activity $activity) {
        $em = $this->getEntityManager();
        $draft_status = $em->getRepository('UAHGestorActividadesBundle:Statusactivity')->getDraft();
        return $draft_status === $activity->getStatus();
    }

    public function getPendingId() {
        $em = $this->getEntityManager();
        $pending_status = $em->getRepository('UAHGestorActividadesBundle:Statusactivity')->getPending();
        $dql = ' SELECT a.id ' .
                ' FROM UAHGestorActividadesBundle:Activity a ' .
                ' WHERE a.status = :pending_status ' .
                ' ORDER BY a.date_pending_approval DESC';
        $consulta = $em->createQuery($dql);
        $consulta->setParameter('pending_status', $pending_status);
        $resultado = $consulta->getScalarResult();
        return $resultado;
    }

    public function getPending() {
        $em = $this->getEntityManager();
        $pending_status = $em->getRepository('UAHGestorActividadesBundle:Statusactivity')->getPending();
        $dql = ' SELECT a ' .
                ' FROM UAHGestorActividadesBundle:Activity a ' .
                ' WHERE a.status = :pending_status ' .
                ' ORDER BY a.date_pending_approval DESC';
        $consulta = $em->createQuery($dql);
        $consulta->setParameter('pending_status', $pending_status);
        $resultado = $consulta->getArrayResult();
        return $resultado;
    }

    public function getAll() {
        $em = $this->getEntityManager();
        $dql = ' SELECT a ' .
                ' FROM UAHGestorActividadesBundle:Activity a ' .
                ' ORDER BY a.date_created DESC';
        $consulta = $em->createQuery($dql);
        $resultado = $consulta->getArrayResult();
        return $resultado;
    }

    public function getAllByStatus(Statusactivity $status) {
        if (is_null($status)) {
            return $this->getAll();
        } else {
            $em = $this->getEntityManager();
            $dql = ' SELECT a ' .
                    ' FROM UAHGestorActividadesBundle:Activity a ' .
                    ' WHERE a.status = :status ' .
                    ' ORDER BY a.date_created DESC';
            $consulta = $em->createQuery($dql);
            $consulta->setParameter('status', $status);
            $resultado = $consulta->getArrayResult();
            return $resultado;
        }
    }

    public function getExportData($filter) {
        $em = $this->getEntityManager();
        $activity_status_repository = $em->getRepository('UAHGestorActividadesBundle:Statusactivity');
        $all = false;
        switch ($filter) {
            case "pending":
                $status = $activity_status_repository->getPending();
                break;
            case "approved":
                $status = $activity_status_repository->getApproved();
                break;
            case "published":
                $status = $activity_status_repository->getPublished();
                break;
            case "closed":
                $status = $activity_status_repository->getClosed();
                break;
            case "draft":
                $status = $activity_status_repository->getDraft();
                break;
            case "all":
                $all = true;
                $year0 = false;
                break;
            case "year0":
                $all = true;
                $year0 = true;
                break;
        }
        $dql = "SELECT a . id AS Id_Actividad  , a.name Nombre , a.englishName NombreEn" .
                " ,a.hasAdditionalWorkload TrabajoAdicional,a.numberOfECTSCreditsMin ECTSminimos, " .
                " a.numberOfECTSCreditsMax ECTSMaximos, a.numberOfCreditsMin LibreMinimo, " .
                " a.numberOfCreditsMax LibreMaximo, " .
                " COUNT( e.id ) inscripciones, " .
                " (select sum(e1.recognizedCredits) from UAHGestorActividadesBundle:Enrollment e1 where e1.creditsType = 'ECTS' " .
                " and e1.activity = a.id) ECTSReconocidos, " .
                " (select sum(e2.recognizedCredits) from UAHGestorActividadesBundle:Enrollment e2 where e2.creditsType = 'LIBRE' " .
                " and e2.activity = a.id) CreditosLibreReconocidos, " .
                " a.date_created FechaCreada, a.date_pending_approval FechaSolicitudAprobacion, " .
                " a.date_approved FechaAprobacion " .
                " FROM UAHGestorActividadesBundle:Activity a JOIN UAHGestorActividadesBundle:Enrollment e " .
                " WITH a.id = e.activity ";
        if ($all & $year0) {
            //No hago nada
        } elseif ($all & !$year0) {
            $year = date('Y');
            $dql .= " WHERE a.date_created > :year ";
        } elseif (!$all) {
            $year = date('Y');
            $dql .= " WHERE a.status=:status and a.date_created > :year ";
        }
        $dql .=" GROUP BY e.activity ";
        $consulta = $em->createQuery($dql);
        if (isset($year)) {
            $consulta->setParameter('year', $year);
        }
        if (isset($status)) {
            $consulta->setParameter('status', $status);
        }
        $resultado = $consulta->getArrayResult();// iterate();
        return $resultado;
    }

}
