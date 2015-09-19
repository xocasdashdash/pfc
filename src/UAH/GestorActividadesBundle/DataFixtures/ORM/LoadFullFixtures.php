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
class LoadFullFixtures extends AbstractFixture implements OrderedFixtureInterface
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
        $index_renewed = count($statuses) - 1;

        $statuses[] = new Statusdegree();
        $statuses[count($statuses) - 1]->setCode("STATUS_NON_RENEWED");
        $statuses[count($statuses) - 1]->setNameEs("No renovado");
        $statuses[count($statuses) - 1]->setNameEn("Not renewed");
        $index_non_renewed = count($statuses) - 1;

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

        echo "Estados creados\n";
        $activity_status = array_slice($statuses, 0, 5);
        $faker = FakerFactory::create('es_ES');
        $faker->addProvider(new UAHUserProvider($faker));
        $faker->addProvider(new UAHDegreeProvider($faker));
        $faker->addProvider(new \Faker\Provider\Internet($faker));
        $faker->addProvider(new UAHActivityProvider($faker));
        $faker->seed(10);
        $populator = new \Faker\ORM\Doctrine\Populator($faker, $manager);
        //Grados
        echo "Añadiendo grados...";
        $populator->addEntity('UAHGestorActividadesBundle:Degree', 20, array(
            'name' => function () use ($faker) {
        return "Grado en ".$faker->unique()->randomLetter();
    },
            'knowledgeArea' => function () use ($faker) {
        return $faker->knowledge_areas();
    }, 'academicCode' => function () use ($faker) {
        return "G".$faker->unique()->numberBetween(0, 100);
    }, 'status' => function () use ($faker, $statuses, $index_renewed, $index_non_renewed) {
        $new_status = $faker->statusDegree(array($statuses[$index_renewed],
            $statuses[$index_non_renewed], ));

        return $new_status;
    },
        ));
        echo "Grados añadidos!\n";
        //Usuarios
        echo "Añadiendo usuarios!";
        $populator->addEntity('UAHGestorActividadesBundle:User', 10, array(
            'name' => function () use ($faker) {
        return $faker->nomprs();
    }, 'apellido_1' => function () use ($faker) {
        return $faker->ll1prs();
    }, 'apellido_2' => function () use ($faker) {
        return $faker->ll2prs();
    }, 'type' => function () use ($faker) {

        return $faker->type();
    },
        ));
        echo "Creando las categorías básicas...\n";
        $categories = array();
        $categories[] = new Category();
        $categories[count($categories) - 1]->setName('Ciencias');
        $categories[count($categories) - 1]->setStatus($statuses[count($statuses) - 2]);
        $categories[count($categories) - 1]->setParentCategory(null);

        $categories[] = new Category();
        $categories[count($categories) - 1]->setName('Ciencias de la Salud');
        $categories[count($categories) - 1]->setStatus($statuses[count($statuses) - 2]);
        $categories[count($categories) - 1]->setParentCategory(null);

        $categories[] = new Category();
        $categories[count($categories) - 1]->setName('Ciencias Sociales y Juridicas');
        $categories[count($categories) - 1]->setStatus($statuses[count($statuses) - 2]);
        $categories[count($categories) - 1]->setParentCategory(null);

        $categories[] = new Category();
        $categories[count($categories) - 1]->setName('Arte y Humanidades');
        $categories[count($categories) - 1]->setStatus($statuses[count($statuses) - 2]);
        $categories[count($categories) - 1]->setParentCategory(null);

        $categories[] = new Category();
        $categories[count($categories) - 1]->setName('Ingeniería y Arquitectura');
        $categories[count($categories) - 1]->setStatus($statuses[count($statuses) - 2]);
        $categories[count($categories) - 1]->setParentCategory(null);

        foreach ($categories as $category) {
            $manager->persist($category);
        }
        $manager->flush();

        echo "Categorías básicas creadas";
        //Actividades
        echo "Añadidos usuarios!\n";
        echo "Añadiendo actividades...";
        $populator->addEntity('UAHGestorActividadesBundle:Activity', 100, array(
            'name' => function () use ($faker) {
        return $faker->sentence(10, true);
    },
            'englishName' => function () use ($faker) {
        return $faker->sentence(10, true);
    }, 'celebrationDates' => function () use ($faker) {
        $fechas = array();
        foreach (range(0, rand(1, 3)) as $i) {
            $fechas[] = strtotime($faker->dateTimeBetween('now', '+1 year')->format("Y-m-d")); //d-m-Y', '31/12/2014');
        }
        sort($fechas);
        //var_dump("FECHAS CREADAS\n".print_r($fechas,true));
        return ($fechas);
    }, 'url' => function () use ($faker) {
        return $faker->url;
    }, 'slug' => function () use ($faker) {
        $nbWords = (int) (6 * mt_rand(60, 140) / 100) + 1;
        $words = $faker->words($nbWords);
        $slug = join($words, '-');

        return $faker->slug;
    }, 'cost' => function () use ($faker) {
        return 0;
    }, 'numberOfECTSCreditsMin' => function () use ($faker) {
        return $faker->randomFloat(1, 0.5, 2);
    }, 'numberOfECTSCreditsMax' => function () use ($faker) {
        return $faker->randomFloat(1, 2.1, 4);
    }, 'numberOfCreditsMin' => function () use ($faker) {
        return $faker->randomFloat(1, 1, 4);
    }, 'numberOfCreditsMax' => function () use ($faker) {
        return $faker->randomFloat(1, 4.2, 8);
    }, 'numberOfPlacesOccupied' => function () use ($faker) {
        return 0;
    }, 'numberOfPlacesOffered' => function () use ($faker) {
        return $faker->numberBetween(20, 100);
    }, 'numberOfHours' => function () use ($faker) {
        return $faker->randomFloat(1, 10, 30);
    }, 'description' => function () use ($faker) {
        return $faker->realText(1000, 4);
    }, 'image_path' => function () use ($faker) {
        $image_route = $faker->image("web/upload/images", 240, 320);
        $image_route = explode("web/upload/images/", $image_route);
        $image_route = $image_route[1];

        return $image_route;
    }, 'publicityStartDate' => function () use ($faker) {
        return $faker->dateTimeBetween('-1 week', 'now');
    }, 'status' => function () use ($activity_status) {
        return $activity_status[array_rand($activity_status)];
    }, 'organizer_name' => function () use ($faker) {
        return $faker->company;
    }, 'date_approved' => function () use ($faker) {
        return;
    }, 'date_created' => function () use ($faker) {
        return $faker->dateTimeBetween('-2 month', '-1 month');
    }, 'date_modified' => function () use ($faker) {
        return $faker->dateTimeBetween('-1 month', 'now');
    }, 'date_pending_approval' => function () use ($faker) {
        return $faker->dateTimeBetween('-1 week', 'now');
    }, 'categories' => function () use ($categories) {
        $randomInteger = rand(1, count($categories));
        $random_categories_key = array_rand($categories, $randomInteger);
        $random_categories = array();
        if (is_array($random_categories_key)) {
            foreach ($random_categories_key as $value) {
                $random_categories[] = $categories[$value];
            }
        } else {
            $random_categories[] = $categories[$random_categories_key];
        }

        return $random_categories;
    },
        ));

        echo "Actividades creadas!\n";
        echo "Añadiendo inscripciones...";
        $populator->addEntity('UAHGestorActividadesBundle:Enrollment', 400, array(
            'dateRecognized' => function () {
        return;
    }, 'recognizedCredits' => function () {
        return;
    }, 'creditsType' => function () {
        return;
    },
        ));
        echo "inscripciones añadidas!\n";
        echo "Ejecutando el populator...";
        $populator->execute();
        echo "Populator ejecutado\n";

        echo "Creando los permisos...";
        $defaultpermits = array();
        $defaultpermits[] = new DefaultPermit();
        $defaultpermits[count($defaultpermits) - 1]->addRole($roles[2]);
        $defaultpermits[count($defaultpermits) - 1]->setIdUsuldap("http://yo.rediris.es/soy/joaquin.fernandez@uah.es/");

        $defaultpermits[] = new DefaultPermit();
        $defaultpermits[count($defaultpermits) - 1]->addRole($roles[2]);
        $defaultpermits[count($defaultpermits) - 1]->setIdUsuldap("http://yo.rediris.es/soy/maitane.fatoorehchi@uah.es/");

        $defaultpermits[] = new DefaultPermit();
        $defaultpermits[count($defaultpermits) - 1]->addRole($roles[2]);
        $defaultpermits[count($defaultpermits) - 1]->setIdUsuldap("http://yo.rediris.es/soy/luis.cereijo@uah.es/");

        $defaultpermits[] = new DefaultPermit();
        $defaultpermits[count($defaultpermits) - 1]->addRole($roles[4]);
        $defaultpermits[count($defaultpermits) - 1]->setIdUsuldap("http://yo.rediris.es/soy/adrian.bolonio@uah.es/");

        $defaultpermits[] = new DefaultPermit();
        $defaultpermits[count($defaultpermits) - 1]->addRole($roles[2]);
        $defaultpermits[count($defaultpermits) - 1]->setIdUsuldap("http://yo.rediris.es/soy/javier.acevedo@uah.es/");

        $defaultpermits[] = new DefaultPermit();
        $defaultpermits[count($defaultpermits) - 1]->addRole($roles[0]);
        $defaultpermits[count($defaultpermits) - 1]->setIdUsuldap("http://yo.rediris.es/soy/marta.lumeras@uah.es/");

        $defaultpermits[] = new DefaultPermit();
        $defaultpermits[count($defaultpermits) - 1]->addRole($roles[2]);
        $defaultpermits[count($defaultpermits) - 1]->setIdUsuldap("http://yo.rediris.es/soy/pedro.gullon@uah.es/");

        foreach ($defaultpermits as $defaultpermit) {
            $manager->persist($defaultpermit);
        }
        $manager->flush();

        echo "Permisos creados\n";
        $em_degree = $manager->getRepository('UAHGestorActividadesBundle:Degree')->findAll();
        echo "Creando el usuario Joaquin\n";
        $userAdmin = new User();
        $userAdmin->setName('Joaquin');
        $userAdmin->setApellido1('Fernandez');
        $userAdmin->setApellido2('Campo');
        $userAdmin->setType('admin');
        $userAdmin->setEmail("jfcampo@gmail.com");
        $userAdmin->setIdUsuldap("http://yo.rediris.es/soy/joaquin.fernandez@uah.es/");
        $degree = $em_degree[array_rand($em_degree)];
        $userAdmin->setDegree($degree);
        $userAdmin->addRole($roles[2]);
        $userAdmin->setDateCreated(new DateTime());
        $userAdmin->setDateUpdated(new DateTime());

        echo "Creando el usuario Bolonio\n";
        $userBolonio = new User();
        $userBolonio->setName('Adrián');
        $userBolonio->setApellido1('Bolonio');
        $userBolonio->setApellido2('cuesta');
        $userBolonio->setType('student');
        $userBolonio->setEmail("jfcampo@gmail.com");
        $userBolonio->setIdUsuldap("http://yo.rediris.es/soy/adrian.bolonio@uah.es/");
        $userBolonio->setDegree($em_degree[array_rand($em_degree)]);
        $userBolonio->addRole($roles[0]);
        $userBolonio->setDateCreated(new DateTime());
        $userBolonio->setDateUpdated(new DateTime());

        //Acevedo
//        echo "Creando el usuario Acevedo\n";
//        $userAcevedo = new User();
//        $userAcevedo->setName('Javier');
//        $userAcevedo->setApellido1('Acevedo');
//        $userAcevedo->setType('admin');
//        $userAcevedo->setEmail('javier.acevedo@uah.es');
//        $userAcevedo->setType('staff');
//        $userAcevedo->setIdUsuldap("http://yo.rediris.es/soy/javier.acevedo@uah.es/");
//        $userAcevedo->addRole($roles[2]);
//        $userAcevedo->setDateCreated(new DateTime());
//        $userAcevedo->setDateUpdated(new DateTime());
        //Marta
        echo "Creando el usuario Marta\n";
        $userMarta = new User();
        $userMarta->setName('Marta');
        $userMarta->setApellido1('Lumeras');
        $userMarta->setApellido2('Peralejo');
        $userMarta->setType('student');
        $userMarta->setEmail("marta.lumeras@edu.uah.es");
        $userMarta->setIdUsuldap("http://yo.rediris.es/soy/marta.lumeras@uah.es/");
        $userMarta->setDegree($em_degree[array_rand($em_degree)]);
        $userMarta->addRole($roles[3]);
        $userMarta->setDateCreated(new DateTime());
        $userMarta->setDateUpdated(new DateTime());

        //Marta
        echo "Creando el usuario Pedro\n";
        $userMarta = new User();
        $userMarta->setName('Pedro');
        $userMarta->setApellido1('Gullón');
        $userMarta->setApellido2('Tosio');
        $userMarta->setType('admin');
        $userMarta->setEmail("pedro.gullon@gmail.com");
        $userMarta->setIdUsuldap("http://yo.rediris.es/soy/pedro.gullon@uah.es/");
        $userMarta->setDegree($em_degree[array_rand($em_degree)]);
        $userMarta->addRole($roles[2]);
        $userMarta->setDateCreated(new DateTime());
        $userMarta->setDateUpdated(new DateTime());

        echo "Guardando los usuarios...\n";
        $manager->persist($userAdmin);
        $manager->persist($userBolonio);
        $manager->persist($userMarta);
        //$manager->persist($userAcevedo);
        $manager->flush();
        echo "Usuarios guardados\n";
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1;
    }
}
