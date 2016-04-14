<?php
/**
 * @author ©Towns.cz
 * @fileOverview Switch file
 */
//======================================================================================================================


require __DIR__ . '/vendor/autoload.php';

//require_once(__DIR__ . '/lib/Files.php');
require_once(__DIR__.'/lib/graphic.lib.php');
require_once(__DIR__.'/lib/init.php');



//----------------------------------------------------------------------------------------------------------------------


$method = strtoupper($_SERVER['REQUEST_METHOD']);


if($method == 'OPTIONS'){

    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");


}elseif($method=='GET'){

    require(__DIR__.'/download.php');

}elseif($method=='POST') {

    require(__DIR__.'/upload.php');

}else{

    http_response_code(400);//Bad Request

}









