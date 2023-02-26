<?php

namespace App\Admin\Resource;

use App\Admin\General\Model;
use App\Admin\User\User;

class Notification extends Model
{
    protected $fillable = [
        'title',
        'user_notified_id',
        'user_created_id',
        'ref_url',
        'seen'
    ];
    public $rules       = [
        'title'             => 'required',
        'user_notified_id'  => 'required',
        'user_created_id'   => 'required',
        'ref_url'           => '',
        'seen'              => ''
    ];

    public static function get_my_notif(){
        $notifs = self::where('user_notified_id', \Auth::user()->id)
                    ->orderBy('created_at', 'DESC')
                    ->take(50)
                    ->get();
        foreach ($notifs as $key => $row){
            $row->uc_avatar = User::find($row->user_created_id)->avatar;
        }
        return $notifs;
    }
}
