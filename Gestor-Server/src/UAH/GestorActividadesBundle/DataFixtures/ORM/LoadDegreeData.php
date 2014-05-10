<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace UAH\GestorActividadesBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use \Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UAH\GestorActividadesBundle\Entity\Degree as Degree;

/**
 * Description of LoadUserData
 *
 * @author xokas
 */
class LoadDegreeData extends AbstractFixture implements OrderedFixtureInterface {

    //put your code here
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager) {
        $degrees = array(
            "Grado en A",
            "Grado en B",
            "Grado en C",
        );
        $indexes = range(0,25);
        
        $knowledge_areas = array(
            "Ciencias",
            "Ciencias de la Salud",
            "Ciencias Sociales y Jurídicas",
            "Ingeniería y Arquitectura",
            "Artes y Humanidades"
        );
        var_dump($knowledge_areas);
        shuffle($knowledge_areas);
        foreach ($indexes as $index) {
            $entity = new Degree();
            $entity->setName("Grado en ".  chr(65+$index));
            $entity->setAcademicCode("G".$index);
            $entity->setKnowledgeArea($knowledge_areas[rand(0,count($knowledge_areas)-1)]);
            $manager->persist($entity);            
        }
        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder() {
        return 1;
    }

}
