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
use UAH\GestorActividadesBundle\Entity\TuibPersonaUser as TuibPersonaUser;
use UAH\GestorActividadesBundle\DataFixtures\FakerProviders\UAHUserProvider as UAHUserProvider;
use UAH\GestorActividadesBundle\DataFixtures\FakerProviders\UAHDegreeProvider as UAHDegreeProvider;
use UAH\GestorActividadesBundle\DataFixtures\FakerProviders\UAHActivityProvider as UAHActivityProvider;
use UAH\GestorActividadesBundle\Entity\User as User;
//use \Faker\Provider;
use \Faker\Factory as FakerFactory;

/**
 * Description of LoadUserData
 *
 * @author xokas
 */
class LoadFixtures extends AbstractFixture implements OrderedFixtureInterface {

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager) {
        $faker = FakerFactory::create('es_ES');
        $faker->addProvider(new UAHUserProvider($faker));
        $faker->addProvider(new UAHDegreeProvider($faker));
        $faker->addProvider(new \Faker\Provider\Internet($faker));
        $faker->addProvider(new UAHActivityProvider($faker));

        $faker->seed(10);
        $populator = new \Faker\ORM\Doctrine\Populator($faker, $manager);
        //Personas de la bd
        $populator->addEntity('UAHGestorActividadesBundle:TuibPersonaUser', 10, array(
            'nomprs' => function() use ($faker) {
        return $faker->nomprs();
    },
            'll1prs' => function() use($faker) {
        return $faker->ll1prs();
    },
            'll2prs' => function() use($faker) {
        return $faker->ll2prs();
    },
            'tlfprs' => function() use($faker) {
        return $faker->tlfprs();
    },
            'id_usuldap' => null,
            'email' => null,
                ), array(function($vista) {
        $vista->create_id_usuldap();
        $vista->create_email();
    },));
        //Grados
        $populator->addEntity('UAHGestorActividadesBundle:Degree', 20, array(
            'name' => function() use ($faker) {
        return "Grado en " . $faker->unique()->randomLetter();
    },
            'knowledgeArea' => function() use ($faker) {
        return $faker->knowledge_areas();
    }, 'academicCode' => function() use ($faker) {
        return "G" . $faker->unique()->randomNumber(null, 100);
    },
        ));
        //Usuarios
        $populator->addEntity('UAHGestorActividadesBundle:User', 10, array(
            'name' => function() use ($faker) {
        return $faker->nomprs();
    }, 'apellido_1' => function() use ($faker) {
        return $faker->ll1prs();
    }, 'apellido_2' => function() use ($faker) {
        return $faker->ll2prs();
    }, 'type' => function() use ($faker) {

        return $faker->type();
    },
        ));
        //Actividades
        $populator->addEntity('UAHGestorActividadesBundle:Activity', 10, array(
            'name' => function() use($faker) {
        return $faker->sentence(10, true);
    },
            'englishName' => function() use($faker) {
        return $faker->sentence(10, true);
    }, 'celebrationDates' => function() use($faker) {
        $fechas = array();
        foreach (range(0, rand(1, 3)) as $i) {
            $fechas[] = $faker->dateTimeBetween('-1 year', '+1 year'); //d-m-Y', '31/12/2014');
        }
        return json_encode($fechas);
    }, 'url' => function() use($faker) {
        return $faker->url;
    }, 'slug' => function() use($faker) {
        $nbWords = (int) (6 * mt_rand(60, 140) / 100) + 1;
        $words = $faker->words($nbWords);
        $slug = join($words, '-');
        return $faker->slug;
    }, 'cost' => function() use($faker) {
        return 0;
    }, 'status' => function () use($faker) {
        return $faker->status;
    }, 'numberOfECTSCreditsMin' => function() use($faker) {
        return $faker->randomFloat(1, 0, 10);
    }, 'numberOfECTSCreditsMax' => function() use($faker) {
        return $faker->randomFloat(1, 0, 10);
    }, 'numberOfCreditsMin' => function() use($faker) {
        return $faker->randomFloat(1, 0, 10);
    }, 'numberOfCreditsMax' => function() use($faker) {
        return $faker->randomFloat(1, 0, 10);
    }, 'numberOfPlacesOccupied' => function() use($faker) {
        return 0;
    }, 'numberOfPlacesOffered' => function() use($faker) {
        return $faker->randomNumber(null, 100);
    }, 'numberOfHours' => function() use($faker) {
        return $faker->randomFloat(1, 0, 30);
    }, 'description' => function() use($faker) {
        return $faker->realText(1000, 4);
    }, 'image' => function() use($faker) {
        $image_route = $faker->image("web/upload/images", 240, 320);
        $image_route = explode("web/", $image_route);
        $image_route = $image_route[1];
        return $image_route;
    }, 'publicityStartDate' => function() use($faker) {
        return $faker->dateTimeBetween('-1 month', '+1 month');
    }
        ));
        $populator->execute();
        
        $userAdmin = new User();
        $userAdmin->setName('Joaquin');
        $userAdmin->setApellido1('Fernandez');
        $userAdmin->setApellido2('Campo');
        $userAdmin->setType('admin');
        $userAdmin->setEmail("jfcampo@gmail.com");
        $userAdmin->setIdUsuldap("http://yo.rediris.es/soy/joaquin.fernandez@uah.es/");
        $em = $manager->getRepository('UAHGestorActividadesBundle:Degree');
        $userAdmin->setDegreeId($em->findAll()[array_rand($em->findAll())]);

        $userStudent = new User();
        $userStudent->setName('Adrián');
        $userStudent->setApellido1('Bolonio');
        $userStudent->setApellido2('cuesta');
        $userStudent->setType('student');
        $userStudent->setEmail("jfcampo@gmail.com");
        $userStudent->setIdUsuldap("http://yo.rediris.es/soy/adrian.bolonio@uah.es/");
        $em = $manager->getRepository('UAHGestorActividadesBundle:Degree');
        $userStudent->setDegreeId($em->findAll()[array_rand($em->findAll())]);

        //Roles
        $role_student = new \UAH\GestorActividadesBundle\Entity\Role();
        $role_student->setRole("UAH_STUDENT");
        $role_student->setName("student");

        $role_admin = new \UAH\GestorActividadesBundle\Entity\Role();
        $role_admin->setRole("UAH_ADMIN");
        $role_admin->setName("admin");
        $manager->persist($role_admin);
        $manager->persist($role_student);

        $userStudent->addRole($role_student);
        $userAdmin->addRole($role_admin);
        $userAdmin->addRole($role_student);

        $userAcevedo = new User();
        $userAcevedo->setName('Javier');
        $userAcevedo->setApellido1('Acevedo');
        $userAcevedo->setType('admin');
        $userAcevedo->setEmail('javier.acevedo@uah.es');
        $userAcevedo->setType('staff');
        $userAcevedo->setIdUsuldap("http://yo.rediris.es/soy/javier.acevedo@uah.es/");
        $userAcevedo->addRole($role_admin);
        $userAcevedo->addRole($role_student);        
        
        $userMarta = new User();
        $userMarta->setName('Marta');
        $userMarta->setApellido1('Lumeras');
        $userMarta->setApellido2('Peraljeo');
        $userMarta->setType('student');
        $userMarta->setEmail("marta.lumeras@edu.uah.es");
        $userMarta->setIdUsuldap("http://yo.rediris.es/soy/marta.lumeras@uah.es/");
        $em = $manager->getRepository('UAHGestorActividadesBundle:Degree');
        $userMarta->setDegreeId($em->findAll()[array_rand($em->findAll())]);
        $userMarta->addRole($role_student);
        
        $manager->persist($userAdmin);
        $manager->persist($userStudent);
        $manager->persist($userMarta);
        $manager->persist($userAcevedo);
        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder() {
        return 1;
    }

}
