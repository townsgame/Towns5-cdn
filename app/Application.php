<?php namespace app;

class Application
{
    public function __construct() {
        error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED ^ E_WARNING );
        error_reporting(E_ALL);
        ini_set("register_globals","off");
        ini_set("display_errors","on");
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");
    }
    
}