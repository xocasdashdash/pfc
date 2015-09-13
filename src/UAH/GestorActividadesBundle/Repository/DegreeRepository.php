<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace UAH\GestorActividadesBundle\Repository;

use Doctrine\ORM\EntityRepository;
use UAH\GestorActividadesBundle\Entity\Activity;
use UAH\GestorActividadesBundle\Entity\User;

class DegreeRepository extends EntityRepository
{
    /**
     *
     * @param  type $user
     * @param  type $activity
     * @return Esta funcion devuelve un resultado verdadero o falso dependiendo de si esta inscrito o no
     */
    public function getActive()
    {
        $em = $this->getEntityManager();
        $active_statuses = $em->getRepository('UAHGestorActividadesBundle:Statusdegree')->getActive();
        $dql = "SELECT d from UAHGestorActividadesBundle:Degree d where d.status in (:active_statuses)";
        $consulta = $em->createQuery($dql);
        $consulta->setParameter('active_statuses', $active_statuses);

        return $consulta->getResult();
    }

    public function getDegrees($filter)
    {
        switch ($filter) {
            case 'CIENCIAS':
                $degrees = $this->getCiencias();
                break;
            case 'SALUD':
                $degrees = $this->getCCSS();
                break;
            case 'CCSSYJJ':
                $degrees = $this->getCCSSYJJ();
                break;
            case 'HUMANIDADES':
                $degrees = $this->getHumanidades();
                break;
            case 'INGYARQ':
                $degrees = $this->getIngYArq();
                break;
            case 'ALL':
                $em = $this->getEntityManager();
                $active_statuses = $em->getRepository('UAHGestorActividadesBundle:Statusdegree')->getActive();
                $dql = "SELECT d, s from UAHGestorActividadesBundle:Degree d LEFT JOIN d.status s";
                $consulta = $em->createQuery($dql);
                $degrees = $consulta->getResult();
                break;
        }

        return $degrees;
    }

    public function getCiencias()
    {
        return $this->getKnowledgeArea('Ciencias');
    }

    public function getCCSS()
    {
        return $this->getKnowledgeArea('Ciencias de la Salud');
    }

    public function getCCSSYJJ()
    {
        return $this->getKnowledgeArea('Ciencias Sociales y Jurídicas');
    }

    public function getIngYArq()
    {
        return $this->getKnowledgeArea('Ingeniería y Arquitectura');
    }

    public function getHumanidades()
    {
        return $this->getKnowledgeArea('Humanidades');
    }

    private function getKnowledgeArea($knowledgeArea)
    {
        $em = $this->getEntityManager();
        $active_statuses = $em->getRepository('UAHGestorActividadesBundle:Statusdegree')->getActive();
        $dql = "SELECT d, s tipo from UAHGestorActividadesBundle:Degree d LEFT JOIN d.status s where d.status in (:active_statuses) ".
                " AND d.knowledgeArea = :knowledgeArea";
        $consulta = $em->createQuery($dql);
        $consulta->setParameter('active_statuses', $active_statuses);
        $consulta->setParameter('knowledgeArea', $knowledgeArea);

        return $consulta->getResult();
    }
}
