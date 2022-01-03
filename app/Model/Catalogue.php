<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Catalogue extends Model
{
    protected $table = "catalogue";

    protected $fillable = [
        'name', 'description', 'amount', 'status', 'created_ip', 'updated_ip', 'created_at', 'updated_at'
    ];

    public function get_catelogue_images()
    {
        return $this->hasMany('App\Model\CatalogueImages', 'catalogue_id', 'id');
    }
}
