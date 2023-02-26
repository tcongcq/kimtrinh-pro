<?php
namespace App\Admin\Setting;

use App\Admin\General\Model;

class Language extends Model
{
    protected $fillable = [
        'language',
        'default',
    ];
    public $rules       = [
        'language' => 'required',
        'default' => 'required',
    ];
}
