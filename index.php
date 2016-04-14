<?php
/**
 * @author ©Towns.cz
 * @fileOverview Switch file
 */
require __DIR__ . '/vendor/autoload.php';


$app = new \app\Application();


$controller = new \app\Controllers\HomeController();
$controller->response();