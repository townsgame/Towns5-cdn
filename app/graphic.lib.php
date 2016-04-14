<?php namespace app;
/**
 * @author ©Towns.cz
 * @fileOverview Functions for image resizing
 */


class Graphic
{
    public function imgresize($img, $width, $height)
    {
        $new_image = imagecreatetruecolor($width, $height);
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