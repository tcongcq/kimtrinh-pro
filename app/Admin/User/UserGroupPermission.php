<?php
namespace App\Admin\User;

use App\Admin\General\Model;

class UserGroupPermission extends Model
{
    protected $fillable = [
        'permission_id',
        'user_group_id',
        'ids',
        'belong_to_system',
    ];
    public $rules       = [
        'permission_id' => 'required',
        'user_group_id' => 'required',
        'ids' => 'required',
        'belong_to_system' => 'required',
    ];

    public $timestamps = true;
}