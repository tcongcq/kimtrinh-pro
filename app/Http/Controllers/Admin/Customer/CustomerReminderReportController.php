<?php
namespace App\Http\Controllers\Admin\Customer;

use InnoSoft\CMS\API;
use App\Admin\User\User;
use App\Admin\Customer\CustomReminder;
use App\Admin\Customer\CustomerReminder;

class CustomerReminderReportController extends API
{   
    public function __construct() {
        $this->M = new CustomerReminder();
        $this->view = 'admin.pages.customer.customer_reminder_reports';
        $this->validator_msg = [];
    }

    public function postStat(){
        $data   = self::prepare_data();
        return ['status'=>'success', 'message'=>'', 'data'=>$data];
    }

    public function prepare_data(){
        // chuẩn bị Reminder sort theo mới nhất
        $custom_reminders = self::prepare_data_query('v_custom_reminders');
        $customer_reminders = self::prepare_data_query('v_customer_reminders');
        return self::merge_result($custom_reminders, $customer_reminders);
        // return self::merge_result([], $customer_reminders);
        // return self::merge_result($custom_reminders, []);
    }

    public function merge_result($first, $seconds){
        $results = [];
        foreach ($first as $key => $row){
            array_push($results, $row);
        }
        foreach ($seconds as $key2 => $row2){
            array_push($results, $row2);
        }
        return $results;
    }

    public function prepare_data_query($view_name){
        $reminders = \DB::table($view_name)->where('active', 1);
        $from_date = \Request::has('from_date') 
                    ? date('Y-m-d 00:00:00', strtotime(\Request::get('from_date')))
                    : date('Y-m-01 00:00:00');
        $to_date = \Request::has('to_date')
                    ? date('Y-m-d 23:59:59', strtotime(\Request::get('to_date')))
                    : date('Y-m-t 23:59:59');
        if (!User::has_permission(['route'=>'customer-reminder','permission_name'=>'access-all']))
            $reminders = $reminders->where('reminder_user_id', \Auth::user()->id);
        if (!empty(\Request::get('states')))
            $reminders = $reminders->whereIn('state', !empty(\Request::get('states')) ? \Request::get('states') : []);
        if ($view_name == 'v_customer_reminders'){
            $reminders = $reminders->where('customer_id', '!=', 1);
            if (!empty(\Request::get('search')))
                $reminders = $reminders->where('customer_id', trim(\Request::get('search')))
                                        ->where('customer_id', '!=', 1);
        }
        $reminders = $reminders->where(function ($query){
                        if (!empty(\Request::get('reminder_user_ids')))
                            $query = $query->whereIn('reminder_user_id', \Request::get('reminder_user_ids'));
                        else
                            $query = $query->where('reminder_user_id', \Auth::user()->id);
                        $query = $query->orWhere('user_created_id', \Auth::user()->id)
                            ->orWhere('public', 1);
                    });
        $reminders = $reminders->where('remind_at', '>=', $from_date)
                        ->where('remind_at', '<=', $to_date);
        $reminders = $reminders->take(2000)->get();
        return $reminders;
    }

    public function postCreateReminder(){
        try {
            \Request::merge([
                'reminder_user_id' => !empty(\Request::get('reminder_user_id')) ? \Request::get('reminder_user_id') : \Auth::user()->id,
                'user_created_id'  => \Auth::user()->id,
                'customer_id'      => 1,
                'active'           => 1,
                'approveed'        => 0
            ]);
            return CustomReminder::create_reminder(\Request::all());
        } catch (Exception $e) {
            return ['status'=>'error', 'message'=>$e->getMessage()];
        }
    }

    public function postAcceptReminder(){
        try {
            \Request::merge([
                'approveed'     => 1,
                'approved_note' => \Request::get('message'),
                'approved_time' => datetime_now()
            ]);
            if (\Request::get('reminder_type') == 'CUSTOM')
                return CustomReminder::accept_reminder(\Request::all());
            return CustomerReminder::accept_reminder(\Request::all());
        } catch (Exception $e) {
            return ['status'=>'success','message'=>$e->getMessage()];
        }
    }

    public function postDeleteReminder(){
        try {
            if (\Request::get('reminder_type') == 'CUSTOM')
                return CustomReminder::delete_reminder(\Request::all());
            return CustomerReminder::delete_reminder(\Request::all());
        } catch (Exception $e) {
            return ['status'=>'success','message'=>$e->getMessage()];
        }
    }
}

