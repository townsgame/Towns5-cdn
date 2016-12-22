<?php namespace app\Controllers;

/**
 * @author ©Towns.cz
 * @fileOverview Functions for rendring trees and rocks
 */

use app\Files;
use app\Graphic;
use Exception;

class HomeController extends BaseController
{

    /**
     * Holds used HTTP method
     * @var string
     */
    public $method;

    public function __construct()
    {
        $this->method = strtoupper($_SERVER['REQUEST_METHOD']);
    }

    /**
     * OPTIONS /
     */
    public function options() {
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    }

    /**
     * GET /
     */
    public function download() {

        define('IMAGE_QUALITY_JPG',60);
        define('IMAGE_QUALITY_PNG',9);

        if(isset($_GET['format'])){
            $format = $_GET['format'];
        }else{
            $format='jpg';
        }



        //todo $_GET['width'];
        if(isset($_GET['file'])) {
            list($uid, $filename) = explode('-', $_GET['file']);

            $filename = base64_decode($filename);

            $files = new Files();
            $graphic = new Graphic();

            $path = $files->storagePath(__DIR__ . '/../../storage/', $_GET['file']);

            //echo($path);


            if (!file_exists($path)) {
                //todo
                echo('wrong file');

            } else {
                //todo widths array

                $width = intval($_GET['width']);
                if($width<10)$width=10;
                if($width>2000)$width=2000;


                if(isset($_GET['rotation'])){
                    $user_rotation = intval($_GET['rotation']);
                }else{
                    $user_rotation = 0;
                }

                if($user_rotation==0 || $user_rotation==90 || $user_rotation==180 || $user_rotation==270){
                }else{
                    die('Rotation should be 0,90,180 or 270.');
                }


                if(isset($_GET['ratio'])){
                    $ratio = floatval($_GET['ratio']);
                }else{
                    $ratio = false;
                }


                $cache_path = $files->cacheFile(array($path, $width, $user_rotation, $ratio), 'dat', 'images');

                if (!file_exists($cache_path) or isset($_GET['notmp']) or filesize($cache_path) < 10/** or 1/**/) {


                    $src = imagecreatefromstring(file_get_contents($path));


                    //-----------------Rotation
                    try {
                        $exif = exif_read_data($path);
                        //print_r($exif);
                        $ort = $exif['Orientation'];
                        $exif_rotation = 0;
                        switch($ort)
                        {

                            case 3:
                                $exif_rotation=180;
                                break;


                            case 6:
                                $exif_rotation=-90;
                                break;

                            case 8:
                                $exif_rotation=90;
                                break;
                        }
                    }
                    catch (Exception $exp) {
                        $exif_rotation=0;
                    }


                    //-----------------


                    if($ratio){
                        $src_ = $graphic->imgreresizecrop($src,  $width, $ratio);
                        imagedestroy($src);
                        $src=$src_;
                    }else{
                        $src_ = $graphic->imgresizew($src, $width);
                        imagedestroy($src);
                        $src=$src_;
                    }



                    $rotation = -$user_rotation+$exif_rotation;
                    if($rotation) {
                        $src_ = imagerotate($src, $rotation, 0);
                        imagedestroy($src);
                        $src=$src_;
                    }
                    //-----------------




                    imagesavealpha($src, true);

                    if($format=='png'){
                        imagepng($src, $cache_path,IMAGE_QUALITY_PNG);

                    }elseif($format=='jpg'){
                        imagejpeg($src, $cache_path,IMAGE_QUALITY_JPG);

                    }else{
                        throw(new Exception('Unknown file format!'));
                    }
                }

                header("Content-Disposition: inline; filename=" . $filename);
                header("Cache-Control: max-age=" . (3600 * 24 * 100));
                header('Content-Type: ' . mime_content_type($path));
                readfile($cache_path);

            }
        }else{
            echo('unknown file');
        }

    }

    /**
     * POST /
     */
    public function upload() {
        //todo refactor move to config
        $file_accepted_types = array(
            'image/jpeg',
            'image/jpg',
            'image/gif',
            'image/png'
            //todo maybe bmp?
        );
        $file_max_size = '25MB';
        $file_max_megapixels = 20000000;
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

            $info = getimagesize($file['tmp_name']);
            $megapixels = ($info[0]*$info[1]);



            if ($file['size'] > $file_max_size) {
                //-----------------------------------------------------------------Size error

                $response[$key] = 'Error: extended max size';
                $response_all_ok = false;

                //  -----------------------------------------------------------------*/
            } elseif ($megapixels > $file_max_megapixels) {
                //-----------------------------------------------------------------Size error

                $response[$key] = 'Error: extended max megapixels';
                $response_all_ok = false;

                //-----------------------------------------------------------------*/
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


                $url = 'http://'.$_SERVER['HTTP_HOST'].'/?file=' . $filename . '&width=' . $image_info[0];
                $path = $files->storagePath(__DIR__ . '/../../storage/', $filename, true);


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

    }
}


