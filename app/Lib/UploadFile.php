<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Lib;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use DateTime;
use Illuminate\Support\Facades\Storage;
use Image;

/**
 * Description of UploadFile
 *
 * @author kishan
 */
class UploadFile {

    public function __construct() {

    }

    public function upload_image($file,$path,$height="",$width="")
    {
        // return $file->store($path);
        $file = $file->store($path);

        //Resize image here
        if(!empty($height) && !empty($width)){
            $thumbnailpath = public_path('storage/'.str_replace('public','',$file));
            $img = Image::make($thumbnailpath)->resize($height, $width, function($constraint) {
                $constraint->aspectRatio();
            });
            $img->save($thumbnailpath);
        }

        return $file;
    }

    public function get_file_path($path,$type){

        switch ($type) {
            case "catelogue_images":
                return str_replace('public/','',$path);
                break;

            default:
                return str_replace('public/','',$path);
                break;
        }
    }

    public function delete_file_folder($path){
        return Storage::delete($path);
    }

    // S3
    public static function upload_s3_file($request,$path,$file_name,$height="",$width="") {  //this
        $file = $request->file($file_name);
        $extension=$file->getClientOriginalExtension();

        $new_file_name=time().rand(10000,99999).'.'.$extension;
        if(!empty($height) && !empty($width)){
            $resize = Image::make($file)->resize($height, $width)->encode($extension);
            $response=Storage::disk('s3')->put($path.'/'.$new_file_name,(string)$resize,'public');
        }else{
            $response=Storage::disk('s3')->put($path.'/'.$new_file_name,file_get_contents($file),'public');
        }

        return $path."/".$new_file_name;
    }

    public function upload_s3_file_multiple($request,$path,$height="",$width=""){
        $file = $request;
        $extension=$file->getClientOriginalExtension();

        $new_file_name=time().rand(10000,99999).'.'.$extension;
        if(!empty($height) && !empty($width)){
            $resize = Image::make($file)->resize($height, $width)->encode($extension);
            $response=Storage::disk('s3')->put($path.'/'.$new_file_name,(string)$resize,'public');
        }else{
            $response=Storage::disk('s3')->put($path.'/'.$new_file_name,file_get_contents($file),'public');
        }

        return $path."/".$new_file_name;
    }


    public static function get_s3_file_path($file_type,$file_name) {  //this

        $s3_link = config('app.s3_link');
        /* switch ($file_type) {
            case 'profile_image':
                //$full_path=asset("audio_file/".$file_name);
                $full_path= $s3_link."/".$file_name;
                break;

            default:
                $full_path=$s3_link."/".$file_name;
                break;
        }
        return $full_path; */

        return $s3_link."/".$file_name;
    }

    public function delete_s3_file($file_name){
        // $s3_link = config('app.s3_link');
        // $res = Storage::disk('s3')->delete($s3_link."/".$file_name);
        // $files = Storage::disk('s3')->files('profile_image');

        $res = Storage::disk('s3')->delete($file_name);
        return $res;
    }

    // product image
    public function product_image($file_name){
        $folderPath = 'product_image/';
        $image_parts = explode(";base64,", $file_name);
        $image_type_aux = explode("image/", $image_parts[0]);
        
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $file = uniqid()."".uniqid() . '.'.$image_type;
        $db_path1 = $folderPath . $file;
        Storage::disk('s3')->put($db_path1, $image_base64,'public');
        return $db_path1;
    }
}
