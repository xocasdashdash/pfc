<?php

# app/config/params.php
$container->setParameter('database_driver', "pdo_mysql");
if (isset($_SERVER['HTTP_CLIENT_IP']) || isset($_SERVER['HTTP_X_FORWARDED_FOR']) || (isset($_SERVER['REMOTE_ADDR']) && !in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', 'fe80::1', '::1'))) || getEnv("OPENSHIFT_MYSQL_DB_HOST")) {
    
    $container->setParameter('database_host', getEnv("MYSQL_DB_HOST"));
    $container->setParameter('database_port', getEnv("MYSQL_DB_PORT"));
    $container->setParameter('database_name', getEnv("MYSQL_USER"));
    $container->setParameter('database_user', getEnv("MYSQL_DATABASE"));
    $container->setParameter('database_password', "");

} else {
    $container->setParameter('database_host', "localhost");
    $container->setParameter('database_port', "3306");
    $container->setParameter('database_name', "symfony");
    $container->setParameter('database_user', "root");
    $container->setParameter('database_password', "toor");
}
$container->setParameter('uploadImagesDir', 'web/upload/images');
