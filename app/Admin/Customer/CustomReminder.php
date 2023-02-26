<?php

namespace App\Admin\Customer;

use App\Admin\General\Model;
use App\Admin\Resource\Notification;

class CustomReminder extends Model
{
    protected $fillable = [
        'name',
        'description',
        'type',
        'remind_at',
        'customer_id',
        'reminder_user_id',
        'user_created_id',
        'approveed',
        'approved_note',
        'approved_time',
        'active',
        'public',
        'note',
    ];
    public $rules       = [
        'name'              => 'required',
        'description'       => '',
        'type'              => '',
        'remind_at'         => '',
        'customer_id'       => 'required',
        'reminder_user_id'  => 'required',
        'user_created_id'   => 'required',
        'approveed'         => '',
        'approved_note'     => '',
        'approved_time'     => '',
        'active'            => 'required',
        'public'            => '',
        'note'              => ''
    ];

    public static function get_reminder($customer_id = null){
        return \DB::table('v_custom_reminders')
                ->where("customer_id", $customer_id)
                ->orderBy("created_at", "DESC")
                ->get();
    }

    public static function get_my_incomplete_reminder(){
        $reminder = \DB::table('v_custom_reminders')
                ->where("reminder_user_id", \Auth::user()->id)
                ->where('approveed', 0)
                ->where('remind_at', '>', date("Y-m-01 00:00:00",strtotime("-1 month")))
                ->where('remind_at', '<', date("Y-m-d 23:59:59"));
        return $reminder->orderBy("remind_at", "DESC")
                ->get();
    }

    public static function create_reminder($request){
        try {
            self::create($request);
            $title = [
                '<strong>'.\Auth::user()->fullname.'</strong>', 'đã thêm một lời nhắc vào thời gian',
                '<strong>'.date("H:i d-m-Y",strtotime($request['remind_at'])).'</strong>',
                'cho bạn.'
            ];
            if ($request['reminder_user_id'] != $request['user_created_id']){
                Notification::create([
                    'title'             => implode(" ", $title),
                    'user_notified_id'  => $request['reminder_user_id'],
                    'user_created_id'   => \Auth::user()->id,
                    'ref_url'           => '',
                    'seen'              => 0
                ]);
            }
            return ['status'=>'success', 'message'=>'Create successfully!'];
        } catch (Exception $e) {
            return ['status'=>'error', 'message'=>$e->getMessage()];
        }
    }

    public static function accept_reminder($request){
        try {
            self::find($request['id'])->update($request);
            return ['status'=>'success', 'message'=>'Update successfully!'];
        } catch (Exception $e) {
            return ['status'=>'error', 'message'=>$e->getMessage()];
        }
    }

    public static function delete_reminder($request){
        try {
            self::find($request['id'])->delete();
            return ['status'=>'success', 'message'=>'Delete successfully!'];
        } catch (Exception $e) {
            return ['status'=>'error', 'message'=>$e->getMessage()];
        }
    }
}
