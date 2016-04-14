<?php
/**
 * @author ©Towns.cz
 * @fileOverview Functions for rendring trees and rocks
 */

use lib\Files;

//todo refactor move to config
$file_accepted_types = array(
    'image/jpeg'
, 'image/jpg'
, 'image/gif'
, 'image/png'
    //todo maybe bmp?

);
$file_max_size = '7MB';
$files = new Files();

$file_max_size = $files->toByteSize($file_max_size);
$all_files_max_size = $file_max_size * count($_FILES) + 1000;


ini_set('post_max_size', $all_files_max_size);
ini_set('upload_max_filesize', $all_files_max_size);


$response = array();
$response_all_ok = true;

//todo check and then move files
foreach ($_FILES as $key => $file) {

    //print_r($file);

    if ($file['size'] > $file_max_size) {
        //-----------------------------------------------------------------Size error

        $response[$key] = 'Error: extended max type';
        $response_all_ok = false;

        //-----------------------------------------------------------------*/
        /**/
    } elseif (in_array($file['type'], $file_accepted_types) === false) {
        //-----------------------------------------------------------------Type error

        $response[$key] = 'Error: wrong file type ' . $file['type'];
        $response_all_ok = false;

        //-----------------------------------------------------------------*/
    } else {
        //-----------------------------------------------------------------Success


        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $image_info = getimagesize($file['tmp_name']);


        $filename = uniqid() . '-' . base64_encode($file['name']);


        $url = 'http://localhost/towns/towns-cdn/?file=' . $filename . '&width=' . $image_info[0];
        $path = $files->storagePath(__DIR__ . '/storage/', $filename, true);


        //-----------------------------Moving files

        move_uploaded_file($file['tmp_name'], $path);

        //-----------------------------Setting response URL

        $response[$key] = $url;

        //-----------------------------------------------------------------
    }


}

if ($response_all_ok) {
    http_response_code(200);//OK
} else {
    http_response_code(403);//Forbidden
}


header('Content-Type: application/json');
echo(json_encode($response));



