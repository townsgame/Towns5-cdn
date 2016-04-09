<?php
/**
 * @author ©Towns.cz
 * @fileOverview Functions for rendring trees and rocks
 */
//======================================================================================================================



//todo $_GET['width'];


list($uid,$filename) = explode('-',$_GET['file']);

$filename=base64_decode($filename);



$path=files\storagePath(__DIR__.'/storage/',$_GET['file']);



if(!file_exists($path)){

    //todo
    echo('wrong file');

}else{


    header("Content-Disposition: inline; filename=" . $filename);
    header("Cache-Control: max-age=".(3600*24*100));
    header('Content-Type: '.mime_content_type($path));
    readfile($path);


}




/**/
