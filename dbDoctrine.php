<?php
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

require_once __DIR__ . '/vendor/autoload.php';

$paths            = array(__DIR__ . '/entities');
$isDevMode        = false;
$connectionParams = array(
    'driver'   => 'pdo_mysql',
    'user'     => 'morgan',
    'password' => '1234',
    'dbname'   => 'work',
    'host'     => 'localhost',
    'charset'  => 'utf8',
    'driverOptions' => array(1002=>'SET NAMES utf8')
);

$config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
$em = EntityManager::create($connectionParams, $config);
