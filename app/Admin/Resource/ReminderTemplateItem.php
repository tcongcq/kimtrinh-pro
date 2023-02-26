<?php

namespace App\Admin\Resource;

use App\Admin\General\Model;

class ReminderTemplateItem extends Model
{
    protected $fillable = [
        'name',
        'description',
        'type',
        'remind_at',
        'reminder_template_id',
        'approveed',
        'approved_note',
        'approved_time',
        'active',
        'note',
    ];
    public $rules       = [
        'name'              => 'required',
        'description'       => '',
        'type'              => '',
        'remind_at'         => '',
        'reminder_template_id'  => 'required',
        'approveed'         => '',
        'approved_note'     => '',
        'approved_time'     => '',
        'active'            => 'required',
        'note'              => '',
    ];

    public static function get_reminder_template_items($reminder_template_id = null, $active=null){
        $items = self::where("reminder_template_id", $reminder_template_id);
        if ($active != null)
            $items = $items->where('active', $active);
        return $items->orderBy("active", "DESC")
                // ->orderByRaw("remind_at")
                ->orderByRaw("CONVERT(SUBSTR(remind_at, 2),UNSIGNED INTEGER)")
                // ->orderBy("remind_at", "ASC")
                ->get();
    }
}
