<?php

namespace App\Admin\Customer;

use App\Admin\General\Model;
use App\Admin\Resource\Notification;

class CustomerReminder extends Model
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
    // public function newQuery($excludeDeleted = true) {
    //     $query = parent::newQuery($excludeDeleted);
    //     // if (User::has_permission(['route'=>'customer-reminder','permission_name'=>'access-all']))
    //     //     return $query;
    //     return $query->where('user_created_id', \Auth::user()->id);
    // }

    public static function get_reminder($customer_id = null){
        return \DB::table('v_customer_reminders')
                ->where("customer_id", $customer_id)
                ->orderBy("created_at", "DESC")
                ->get();
    }

    public static function get_my_incomplete_reminder(){
        $reminder = \DB::table('v_customer_reminders')
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
            if ($request['customer_id'] == 1){
                $title = [
                    '<strong>'.\Auth::user()->fullname.'</strong>', 'đã thêm một lời nhắc vào thời gian',
                    '<strong>'.date("H:i d-m-Y",strtotime($request['remind_at'])).'</strong>',
                    'cho bạn.'
                ];
            } else {
                $title = [
                    '<strong>'.\Auth::user()->fullname.'</strong>', 'đã thêm nhắc nhở vào đơn hàng',
                    '<strong>'.$request['customer_id'].'</strong>',
                    'cho bạn.'
                ];
            }
            if ($request['reminder_user_id'] != $request['user_created_id']){
                Notification::create([
                    'title'             => implode(" ", $title),
                    'user_notified_id'  => $request['reminder_user_id'],
                    'user_created_id'   => \Auth::user()->id,
                    'ref_url'           => $request['customer_id'] != 1 ? 'admin/customer?id='.$request['customer_id'] : '',
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


/*

CREATE TABLE `custom_reminders` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `description` text,
  `type` varchar(255) DEFAULT NULL,
  `remind_at` datetime DEFAULT NULL,
  `customer_id` int unsigned NOT NULL DEFAULT '1',
  `reminder_user_id` int unsigned NOT NULL DEFAULT '1',
  `user_created_id` int unsigned NOT NULL DEFAULT '1',
  `approveed` tinyint NOT NULL DEFAULT '0',
  `approved_note` text,
  `approved_time` datetime DEFAULT NULL,
  `priority` tinyint NOT NULL DEFAULT '0',
  `active` tinyint NOT NULL DEFAULT '0',
  `note` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `public` tinyint DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `reminder_user_id` (`reminder_user_id`) USING BTREE,
  KEY `user_created_id` (`user_created_id`) USING BTREE,
  CONSTRAINT `custom_reminders_ibfk_1` FOREIGN KEY (`reminder_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `custom_reminders_ibfk_2` FOREIGN KEY (`user_created_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;


CREATE TABLE `custom_reminders_tracking` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `c_id` int NOT NULL,
  `name` text NOT NULL,
  `description` text,
  `type` varchar(255) DEFAULT NULL,
  `remind_at` datetime DEFAULT NULL,
  `customer_id` int unsigned NOT NULL DEFAULT '1',
  `reminder_user_id` int unsigned NOT NULL DEFAULT '1',
  `user_created_id` int unsigned NOT NULL DEFAULT '1',
  `approveed` tinyint NOT NULL DEFAULT '0',
  `approved_note` text,
  `approved_time` datetime DEFAULT NULL,
  `priority` tinyint NOT NULL DEFAULT '0',
  `active` tinyint NOT NULL DEFAULT '0',
  `note` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `public` tinyint DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;


DELIMITER $$
CREATE TRIGGER custom_reminder_after_insert AFTER INSERT ON custom_reminders FOR EACH ROW
BEGIN
    INSERT INTO custom_reminders_tracking values (NULL, NEW.id,NEW.name,NEW.description,NEW.type,NEW.remind_at,NEW.customer_id,NEW.reminder_user_id,NEW.user_created_id,NEW.approveed,NEW.approved_note,NEW.approved_time,NEW.priority,NEW.active,NEW.note,NEW.created_at,NEW.updated_at,NEW.public);
END $$
DELIMITER

DELIMITER $$
CREATE TRIGGER custom_reminder_after_update AFTER update ON custom_reminders FOR EACH ROW
BEGIN
    INSERT INTO custom_reminders_tracking values (NULL, NEW.id,NEW.name,NEW.description,NEW.type,NEW.remind_at,NEW.customer_id,NEW.reminder_user_id,NEW.user_created_id,NEW.approveed,NEW.approved_note,NEW.approved_time,NEW.priority,NEW.active,NEW.note,NEW.created_at,NEW.updated_at,NEW.public);
END $$
DELIMITER

CREATE VIEW `v_custom_reminders` AS select `custom_reminders`.`id` AS `id`,`custom_reminders`.`name` AS `name`,`custom_reminders`.`type` AS `type`,`custom_reminders`.`remind_at` AS `remind_at`,`custom_reminders`.`customer_id` AS `customer_id`,`custom_reminders`.`reminder_user_id` AS `reminder_user_id`,`custom_reminders`.`user_created_id` AS `user_created_id`,`custom_reminders`.`approveed` AS `approveed`,`custom_reminders`.`approved_note` AS `approved_note`,`custom_reminders`.`approved_time` AS `approved_time`,`custom_reminders`.`priority` AS `priority`,`custom_reminders`.`active` AS `active`,`custom_reminders`.`public` AS `public`,`custom_reminders`.`note` AS `note`,`custom_reminders`.`created_at` AS `created_at`,`custom_reminders`.`updated_at` AS `updated_at`,`uc`.`fullname` AS `uc_fullname`,`uc`.`avatar` AS `uc_avatar`,`ru`.`fullname` AS `ru_fullname`,`ru`.`avatar` AS `ru_avatar`,`ru`.`phone` AS `ru_phone`,`ru`.`email` AS `ru_email`,`ru`.`gender` AS `ru_gender`,`customers`.`name` AS `customer_name`,`customers`.`display_name` AS `display_name`,`customers`.`phone` AS `phone`,`customers`.`email` AS `email`,`customers`.`company_name` AS `company_name`,`customers`.`brand_name` AS `brand_name`,`customers`.`company_address` AS `company_address`,`customers`.`link` AS `link`,`customers`.`field` AS `field`,`customers`.`branch` AS `branch`,`customers`.`state` AS `state`,`customers`.`contract_state` AS `contract_state`,`customers`.`payment_state` AS `payment_state`,`customers`.`sign_date` AS `sign_date`,`customers`.`describe` AS `describe`,`customers`.`source` AS `source`,`customers`.`demand` AS `demand`,`customers`.`concern` AS `concern`,`customers`.`code` AS `code` from (((`custom_reminders` join `users` `uc` on((`custom_reminders`.`user_created_id` = `uc`.`id`))) join `users` `ru` on((`custom_reminders`.`reminder_user_id` = `ru`.`id`))) join `customers` on((`custom_reminders`.`customer_id` = `customers`.`id`)));

INSERT INTO custom_reminders SELECT * FROM customer_reminders WHERE customer_id=1;

*/

