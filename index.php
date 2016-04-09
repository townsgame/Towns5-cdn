<?php
/**
 * @author ©Towns.cz
 * @fileOverview Switch file
 */
//======================================================================================================================



require_once(__DIR__.'/lib/files.lib.php');
require_once(__DIR__.'/lib/graphic.lib.php');
require_once(__DIR__.'/lib/init.php');



//----------------------------------------------------------------------------------------------------------------------


$mathod = strtoupper($_SERVER['REQUEST_METHOD']);



if($mathod=='GET'){

    require(__DIR__.'/download.php');

}elseif($mathod=='POST') {

    require(__DIR__.'/upload.php');

}else{

    http_response_code(400);//Bad Request

}









