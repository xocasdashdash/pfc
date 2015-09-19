<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace UAH\GestorActividadesBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UAH\GestorActividadesBundle\DataFixtures\FakerProviders\UAHUserProvider as UAHUserProvider;
use UAH\GestorActividadesBundle\DataFixtures\FakerProviders\UAHDegreeProvider as UAHDegreeProvider;
use UAH\GestorActividadesBundle\DataFixtures\FakerProviders\UAHActivityProvider as UAHActivityProvider;
use UAH\GestorActividadesBundle\Entity\User as User;
use UAH\GestorActividadesBundle\Entity\Role as Role;
use UAH\GestorActividadesBundle\Entity\DefaultPermit as DefaultPermit;
use UAH\GestorActividadesBundle\Entity\Statusactivity as Statusactivity;
use UAH\GestorActividadesBundle\Entity\Statuscategory as Statuscategory;
use UAH\GestorActividadesBundle\Entity\Statusenrollment as Statusenrollment;
use UAH\GestorActividadesBundle\Entity\Statusdegree as Statusdegree;
use UAH\GestorActividadesBundle\Entity\Statusapplication as Statusapplication;
use UAH\GestorActividadesBundle\Entity\Category as Category;
//use \Faker\Provider;
use Faker\Factory as FakerFactory;
use DateTime;

/**
 * Description of LoadUserData
 *
 * @author xokas
 */
class LoadFixtures extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        echo "Creando los estados..";
        /**
         * ACTIVIDADES
         */
        $statuses = array();
        $statuses[] = new Statusactivity();
        $statuses[count($statuses) - 1]->setCode("STATUS_PENDING");
        $statuses[count($statuses) - 1]->setNameEs("Pendiente de aprobación");
        $statuses[count($statuses) - 1]->setNameEn("Pending aproval ");

        $statuses[] = new Statusactivity();
        $statuses[count($statuses) - 1]->setCode("STATUS_PUBLISHED");
        $statuses[count($statuses) - 1]->setNameEs("Publicado");
        $statuses[count($statuses) - 1]->setNameEn("Published");

        $statuses[] = new Statusactivity();
        $statuses[count($statuses) - 1]->setCode("STATUS_DRAFT");
        $statuses[count($statuses) - 1]->setNameEs("Borrador");
        $statuses[count($statuses) - 1]->setNameEn("Draft");

        $statuses[] = new Statusactivity();
        $statuses[count($statuses) - 1]->setCode("STATUS_CLOSED");
        $statuses[count($statuses) - 1]->setNameEs("Cerrado");
        $statuses[count($statuses) - 1]->setNameEn("Closed");

        $statuses[] = new Statusactivity();
        $statuses[count($statuses) - 1]->setCode("STATUS_APPROVED");
        $statuses[count($statuses) - 1]->setNameEs("Aprobado");
        $statuses[count($statuses) - 1]->setNameEn("Approved");

        /**
         * INSCRIPCIONES
         */
        $statuses[] = new Statusenrollment();
        $statuses[count($statuses) - 1]->setCode("STATUS_ENROLLED");
        $statuses[count($statuses) - 1]->setNameEs("Inscrito");
        $statuses[count($statuses) - 1]->setNameEn("Enrolled");

        $statuses[] = new Statusenrollment();
        $statuses[count($statuses) - 1]->setCode("STATUS_VERIFIED");
        $statuses[count($statuses) - 1]->setNameEs("Verificado");
        $statuses[count($statuses) - 1]->setNameEn("Verified");

        $statuses[] = new Statusenrollment();
        $statuses[count($statuses) - 1]->setCode("STATUS_RECOGNIZED");
        $statuses[count($statuses) - 1]->setNameEs("Reconocido");
        $statuses[count($statuses) - 1]->setNameEn("Recognized");

        $statuses[] = new Statusenrollment();
        $statuses[count($statuses) - 1]->setCode("STATUS_NOT_RECOGNIZED");
        $statuses[count($statuses) - 1]->setNameEs("No reconocido");
        $statuses[count($statuses) - 1]->setNameEn("Not Recognized");

        $statuses[] = new Statusenrollment();
        $statuses[count($statuses) - 1]->setCode("STATUS_PENDING_VERIFICATION");
        $statuses[count($statuses) - 1]->setNameEs("No reconocido");
        $statuses[count($statuses) - 1]->setNameEn("Not Recognized");

        $statuses[] = new Statusenrollment();
        $statuses[count($statuses) - 1]->setCode("STATUS_UNENROLLED");
        $statuses[count($statuses) - 1]->setNameEs("Desinscrito");
        $statuses[count($statuses) - 1]->setNameEn("Unenrolled");

        /**
         * PLANES DE ESTUDIOS
         */
        $statuses[] = new Statusdegree();
        $statuses[count($statuses) - 1]->setCode("STATUS_RENEWED");
        $statuses[count($statuses) - 1]->setNameEs("Renovado");
        $statuses[count($statuses) - 1]->setNameEn("Renewed");

        $statuses[] = new Statusdegree();
        $statuses[count($statuses) - 1]->setCode("STATUS_NON_RENEWED");
        $statuses[count($statuses) - 1]->setNameEs("No renovado");
        $statuses[count($statuses) - 1]->setNameEn("Not renewed");

        $statuses[] = new Statusdegree();
        $statuses[count($statuses) - 1]->setCode("STATUS_INACTIVE");
        $statuses[count($statuses) - 1]->setNameEs("Inactivo");
        $statuses[count($statuses) - 1]->setNameEn("Inactive");

        /**
         * JUSTIFICANTES
         */
        $statuses[] = new Statusapplication();
        $statuses[count($statuses) - 1]->setCode("STATUS_CREATED");
        $statuses[count($statuses) - 1]->setNameEs("Creado");
        $statuses[count($statuses) - 1]->setNameEn("Created");

        $statuses[] = new Statusapplication();
        $statuses[count($statuses) - 1]->setCode("STATUS_VERIFIED");
        $statuses[count($statuses) - 1]->setNameEs("Verificado");
        $statuses[count($statuses) - 1]->setNameEn("Verified");

        $statuses[] = new Statusapplication();
        $statuses[count($statuses) - 1]->setCode("STATUS_ARCHIVED");
        $statuses[count($statuses) - 1]->setNameEs("Archivado");
        $statuses[count($statuses) - 1]->setNameEn("Archieved");

        /**
         * CATEGORIAS
         */
        $statuses[] = new Statuscategory();
        $statuses[count($statuses) - 1]->setCode("STATUS_ACTIVE");
        $statuses[count($statuses) - 1]->setNameEs("Activa");
        $statuses[count($statuses) - 1]->setNameEn("Active");

        $statuses[] = new Statuscategory();
        $statuses[count($statuses) - 1]->setCode("STATUS_INACTIVE");
        $statuses[count($statuses) - 1]->setNameEs("Inactiva");
        $statuses[count($statuses) - 1]->setNameEn("Inactive");

        foreach ($statuses as $status) {
            $manager->persist($status);
        }
        $manager->flush();
        echo "Estados creados\n";

        echo "Creando los roles...";

        $roles = array();
        //Roles
        $roles[] = new Role();
        $roles[count($roles) - 1]->setRole("ROLE_UAH_STUDENT");
        $roles[count($roles) - 1]->setName("Estudiante");

        $roles[] = new Role();
        $roles[count($roles) - 1]->setRole("ROLE_UAH_ADMIN");
        $roles[count($roles) - 1]->setName("Administrador");

        $roles[] = new Role();
        $roles[count($roles) - 1]->setRole("ROLE_UAH_SUPER_ADMIN");
        $roles[count($roles) - 1]->setName("Super Admin");

        $roles[] = new Role();
        $roles[count($roles) - 1]->setRole("ROLE_UAH_STAFF_PAS");
        $roles[count($roles) - 1]->setName("Secretaría/PAS");

        $roles[] = new Role();
        $roles[count($roles) - 1]->setRole("ROLE_UAH_STAFF_PDI");
        $roles[count($roles) - 1]->setName("Organizador/PDI");

        foreach ($roles as $role) {
            $manager->persist($role);
        }
        $manager->flush();
        echo "Creados todos los roles\n";
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 2;
    }

}