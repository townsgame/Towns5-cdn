<?php namespace app;
/**
 * @author ©Towns.cz
 * @fileOverview Functions for image resizing
 */


class Graphic
{





    public function imgreresizecroptb($img,$width, $ratio){

        $new_image = imagecreatetruecolor($width, $width/$ratio);

        $crop = (imagesy($img) - imagesx($img)/$ratio)/2;
        //$crop = ( imagesy($img) - (imagesx($img)/$ratio) )/2;


        imagealphablending($new_image, false);
        imagecopyresampled($new_image, $img,
            0, 0,
            0, $crop,
            imagesx($new_image), imagesy($new_image),
            imagesx($img), imagesy($img)-2*$crop
        );
        return ($new_image);


    }





    public function imgreresizecroplr($img, $width, $ratio){

        $new_image = imagecreatetruecolor($width, $width/$ratio);

        $crop = (imagesx($img) - imagesy($img)*$ratio)/2;

        imagealphablending($new_image, false);
        imagecopyresampled($new_image, $img,
            0, 0,
            $crop, 0,
            imagesx($new_image), imagesy($new_image),
            imagesx($img)-2*$crop, imagesy($img)
        );
        return ($new_image);

    }


    public function imgreresizecrop($img, $width, $ratio){


        $img_ratio = imagesx($img)/imagesy($img);

        if(round($img_ratio*100)/100==round($ratio*100)/100) {
            //die('imgresizew');
            return $this->imgresizew($img,$width);
        }else
            if($img_ratio<$ratio) {
                //die('imgreresizecroptb');
                return ($this->imgreresizecroptb($img, $width, $ratio));
            }else
                if($img_ratio>$ratio){
                    //die('imgreresizecroplr');
                    return($this->imgreresizecroplr($img, $width, $ratio));
                }

        return null;
    }



    public function imgresize($img, $width, $height)
    {
        $new_image = imagecreatetruecolor($width, $height);
        /*$clr = imagecolorallocate($new_image, 0, 255, 0);
        imagefill($new_image, 0, 0, $clr);*/



        imagealphablending($new_image, false);
        imagecopyresampled($new_image, $img, 0, 0, 0, 0, $width, $height, imagesx($img), imagesy($img));
        return ($new_image);
    }

    public function imgresizew($img, $width)
    {
        $ratio = $width / imagesx($img);
        $height = imagesy($img) * $ratio;
        return ($this->imgresize($img, $width, $height));
    }

}