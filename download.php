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


    //todo widths array

    $width=intval($_GET['width']);

    $cache_path=files\cacheFile(array($path,$width),'dat','images');

    if(!file_exists($cache_path) or isset($_GET['notmp']) or filesize($cache_path)<10 /** or 1/**/) {
        //_________________________________________

        $src = imagecreatefromstring(file_get_contents($path));
        $dest = graphic\imgresizew($src, $width);



        imagesavealpha($dest, true);
        imagepng($dest,$cache_path);

        //_________________________________________
    }




    header("Content-Disposition: inline; filename=" . $filename);
    header("Cache-Control: max-age=".(3600*24*100));
    header('Content-Type: '.mime_content_type($path));
    readfile($cache_path);


}




/**/
