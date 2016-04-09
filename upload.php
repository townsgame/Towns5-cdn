<?php
/**
 * @author ©Towns.cz
 * @fileOverview Functions for rendring trees and rocks
 */
//======================================================================================================================



//todo refactor move to config
$file_accepted_types=array(
    'image/jpeg'
    ,'image/jpg'
    ,'image/gif'
    ,'image/png'
    //todo maybe bmp?

);
$file_max_size='15MB';


ini_set('post_max_size', $file_max_size);
ini_set('upload_max_filesize', $file_max_size);



$response=array();
$response_all_ok=true;


foreach($_FILES as $key=>$file){


    if($file['size']>files\toByteSize($file_max_size)){
        //-----------------------------------------------------------------Size error

        $response[$key]='Error: extended max type';
        $response_all_ok=false;

        //-----------------------------------------------------------------
    }elseif(in_array($file['type'],$file_accepted_types)===false){
        //-----------------------------------------------------------------Type error

        $response[$key]='Error: wrong file type';
        $response_all_ok=false;

        //-----------------------------------------------------------------
    }else{
        //-----------------------------------------------------------------Success


        $url='http://localhost/towns/towns-cdn/'.uniqid().'-'.base64_encode($file['name']);



        $image_info = getimagesize($file['tmp_name']);
        $url.='?width='.$image_info[0];


        $response[$key]=$url;

        //-----------------------------------------------------------------
    }



}

if($response_all_ok){
    http_response_code(200);//OK
}else{
    http_response_code(403);//Forbidden
}



header('Content-Type: application/json');
echo(json_encode($response));



