<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Lib\UploadFile;

class CatalogueImages extends Model
{
    protected $table = "catalogue_images";

    protected $appends = ['catalogue_images_full_path'];

    public function getCatalogueImagesFullPathAttribute(){
        $upload_file = new UploadFile();
        return  $upload_file->get_s3_file_path('catalogue_images',$this->image);
    }
}
