<?php 
use Model\ActiveRecord;
// Definir la zona horaria global para PHP
date_default_timezone_set('America/Mexico_City');
require __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

require 'funciones.php';
require 'database.php';

// Conectarnos a la base de datos
ActiveRecord::setDB($db);