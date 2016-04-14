<?php
/**
 * @author ©Towns.cz
 * @fileOverview Switch file
 */

use app\Controllers\HomeController;

require __DIR__ . '/vendor/autoload.php';

require_once(__DIR__ . '/app/Init.php');
//require_once(__DIR__ . '/app/Files.php');
//require_once(__DIR__.' /app/Graphic.php');

$controller = new HomeController();
$controller->response();









