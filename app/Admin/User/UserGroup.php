<?php
namespace App\Admin\User;

use App\Admin\General\Model;

class UserGroup extends Model
{
    protected $fillable = [
        'group_name',
        'file_group',
        'note',
    ];
    public $rules       = [
        'group_name' => 'required|unique:user_groups,group_name',
        'file_group' => '',
        'note'       => 'required'
    ];

    public $timestamps = true;
}