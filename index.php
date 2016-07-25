<?php
/**
 * @author ©Towns.cz
 * @fileOverview Switch file
 */
require __DIR__ . '/vendor/autoload.php';

/**
 * Registers new application
 */
$app = new \app\Application();

/**
 * Define routes
 * get, post, delete, patch, put, options
 */
$app->get('/', "app\\Controllers\\HomeController@download");
$app->post('/', "app\\Controllers\\HomeController@upload");
$app->options('/', "app\\Controllers\\HomeController@options");

/**
 * Find the proper route and execute controller method
 */
$app->run();