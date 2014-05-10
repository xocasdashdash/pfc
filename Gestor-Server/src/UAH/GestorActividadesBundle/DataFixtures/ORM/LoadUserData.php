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
use UAH\GestorActividadesBundle\Entity\User as User;

/**
 * Description of LoadUserData
 *
 * @author xokas
 */
class LoadUserData extends AbstractFixture implements OrderedFixtureInterface {

    //put your code here
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager) {
        $userAdmin = new User();
        $userAdmin->setName('admin');
        $userAdmin->setPassword('test');
        $userAdmin->setType('ADMIN');
        $userAdmin->setEmail("jfcampo@gmail.com");
        $em = $manager->getRepository('UAHGestorActividadesBundle:Degree');
        
        //$objeto = ;
        $userAdmin->setDegreeId($em->findAll()[array_rand($em->findAll())]);
        //$userAdmin->setCreationIp("127.0.0.1");

        $manager->persist($userAdmin);
        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder() {
        return 2;
    }

}
