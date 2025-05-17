<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait HandlesImageUploads
{
    function uploadImage($imageFile, $uploadPath, $width = null, $height = null)
    {
        $randomString = bin2hex(random_bytes(3)); 
        $image_name = date('dmy_H_s_i') . '_' . $randomString;
        $ext = strtolower($imageFile->getClientOriginalExtension());
        $image_full_name = $image_name . '.' . $ext;
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }
        $imageFile->move($uploadPath, $image_full_name);
        $imagePath = $uploadPath . $image_full_name;
        if ($width !== null && $height !== null) {
            $this->resizeImage($imagePath, $width, $height);
        }
        return $imagePath;
    }

    function resizeImage($imagePath, $width, $height)
    {
        list($originalWidth, $originalHeight, $type) = getimagesize($imagePath);
        switch ($type) {
            case IMAGETYPE_JPEG:
                $image = imagecreatefromjpeg($imagePath);
                break;
            case IMAGETYPE_PNG:
                $image = imagecreatefrompng($imagePath);
                break;
            case IMAGETYPE_GIF:
                $image = imagecreatefromgif($imagePath);
                break;
            default:
                return false;
        }

        $newImage = imagecreatetruecolor($width, $height);
        if ($type == IMAGETYPE_PNG || $type == IMAGETYPE_GIF) {
            imagecolortransparent($newImage, imagecolorallocatealpha($newImage, 0, 0, 0, 127));
            imagealphablending($newImage, false);
            imagesavealpha($newImage, true);
        }
        imagecopyresampled($newImage, $image, 0, 0, 0, 0, $width, $height, $originalWidth, $originalHeight);
        switch ($type) {
            case IMAGETYPE_JPEG:
                imagejpeg($newImage, $imagePath, 90);
                break;
            case IMAGETYPE_PNG:
                imagepng($newImage, $imagePath, 9);
                break;
            case IMAGETYPE_GIF:
                imagegif($newImage, $imagePath);
                break;
        }
        imagedestroy($image);
        imagedestroy($newImage);
        return true;
    }


    public function deleteImage($image)
    {
        if ($image != null) {
            unlink($image);
            return true;
        }
        return false;
    }
}