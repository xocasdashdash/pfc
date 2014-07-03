<?php

# app/config/params.php
if (isset($_SERVER['HTTP_CLIENT_IP']) || isset($_SERVER['HTTP_X_FORWARDED_FOR']) || (isset($_SERVER['REMOTE_ADDR']) && !in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', 'fe80::1', '::1'))) || getEnv("OPENSHIFT_MYSQL_DB_HOST")) {
    echo"LOADING";
    $container->setParameter('database_driver', "pdo_mysql");
    $container->setParameter('database_host', getEnv("OPENSHIFT_MYSQL_DB_HOST"));
    $container->setParameter('database_port', getEnv("OPENSHIFT_MYSQL_DB_PORT"));
    $container->setParameter('database_name', getEnv("OPENSHIFT_APP_NAME"));
    $container->setParameter('database_user', getEnv("OPENSHIFT_MYSQL_DB_USERNAME"));
    $container->setParameter('database_password', getEnv("OPENSHIFT_MYSQL_DB_PASSWORD"));
} else {
    //Configuracion ORACLE
//    $container->setParameter('database_driver', "oci8");
//    $container->setParameter('database_name', "XE");
//    $container->setParameter('database_service', "true");
//    $container->setParameter('database_user', "uah_gat");
//    $container->setParameter('database_password', "jfc$24uah");
    //Configuracion MySQL

    $container->setParameter('database_host', "localhost");
    $container->setParameter('database_driver', "pdo_mysql");
    $container->setParameter('database_port', "1521");
    $container->setParameter('database_name', "symfony");
    $container->setParameter('database_user', "root");
    $container->setParameter('database_password', "toor");
}
$container->setParameter('uploadImagesDir', 'web/upload/images')
?>