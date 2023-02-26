<?php

namespace App\Admin\Resource;

use App\Admin\General\Model;

class Product extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'note',
        'active'
    ];
    public $rules       = [
        'code'          => '',
        'name'          => 'required',
        'description'   => '',
        'note'          => '',
        'active'        => 'required'
    ];

    public static function get_active_products(){
        return self::where('active', 1)->get();
    }
}
