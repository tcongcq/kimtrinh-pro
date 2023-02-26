<?php
namespace App\Admin\User;

use App\Admin\General\Model;

class Role extends Model
{
    protected $fillable = [
        'user_id',
        'user_group_id',
    ];
    public $rules       = [
        'user_id' => 'required',
        'user_group_id' => 'required',
    ];

    public $timestamps = true;
}