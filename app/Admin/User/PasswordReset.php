<?php
namespace App\Admin\User;

use App\Admin\General\Model;

class PasswordReset extends Model
{
    protected $fillable = [
        'email',
        'token',
    ];
    public $rules       = [
        'email' => 'required',
        'token' => 'required',
    ];

    public $timestamps = true;
}