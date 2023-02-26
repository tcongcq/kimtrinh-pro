<?php

namespace App\Admin\Setting;

use App\Admin\General\Model;

class Permission extends Model
{
    protected $fillable = [
        'name',
        'table',
        'alias',
        'description',
        'note'
    ];
    public $rules       = [
        'name'          => 'required',
        'table'   		=> '',
        'alias'         => 'required',
        'description'   => '',
        'note'    		=> ''
    ];

}
