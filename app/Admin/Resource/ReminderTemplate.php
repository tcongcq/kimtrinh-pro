<?php

namespace App\Admin\Resource;

use App\Admin\General\Model;

class ReminderTemplate extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'note',
        'priority',
        'active'
    ];
    public $rules       = [
        'code'          => '',
        'name'          => 'required',
        'description'   => '',
        'note'          => '',
        'priority'      => '',
        'active'        => 'required'
    ];

    public static function get_reminder_templates(){
        return self::where('active', 1)->get();
    }
}
