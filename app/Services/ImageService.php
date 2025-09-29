<?php

namespace App\Services;

use App\Models\Image;

class ImageService
{
    public function storeImageInDisk($image, $hashname)
    {
        $image->storePublicly('uploads', 'public', $hashname);
        return "uploads/$hashname";
    }

    public function storageImageInDatabase($title, $hashname)
    {
        Image::create([
            'title' => $title,
            'hashname' => $hashname
        ]);
    }
}
