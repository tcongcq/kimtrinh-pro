<?php
namespace App\Http\Controllers\Admin\Customer;

use InnoSoft\CMS\API;
use App\Admin\User\User;
use App\Admin\Customer\Customer;
use App\Admin\Customer\CustomerReminder;

class CustomerReminderController extends API
{   
    public function __construct() {
        $this->M = new CustomerReminder();
        $this->view = 'admin.pages.customer.customer_reminders';
        $this->validator_msg = [];
        $this->searches = ['name','approved_note','note','customer_id','customer_name','code','display_name','phone','email'];
    }

    public function postStat(){
        $data   = self::prepare_data();
        return view('admin.forms.customer.customer_reminder')->with($data);
    }

    public function prepare_data(){
        // chuẩn bị Reminder sort theo mới nhất
        $reminders = \DB::table('v_customer_reminders')->where('active', 1);
        $from_date = \Request::has('from_date') 
                    ? date('Y-m-d 00:00:00', strtotime(\Request::get('from_date')))
                    : date('Y-m-01 00:00:00');
        $to_date = \Request::has('to_date')
                    ? date('Y-m-d 23:59:59', strtotime(\Request::get('to_date')))
                    : date('Y-m-t 23:59:59');
        $reminders = $reminders->where('remind_at', '>=', $from_date)
                        ->where('remind_at', '<=', $to_date);
        if (!User::has_permission(['route'=>'customer-reminder','permission_name'=>'access-all']))
            $reminders = $reminders->where('reminder_user_id', \Auth::user()->id);
        elseif (!empty(\Request::get('reminder_user_ids')))
            $reminders = $reminders->whereIn('reminder_user_id', !empty(\Request::get('reminder_user_ids')) ? \Request::get('reminder_user_ids') : []);
        if (!empty(\Request::get('approveeds')))
            $reminders = $reminders->whereIn('approveed', !empty(\Request::get('approveeds')) ? \Request::get('approveeds') : []);
        if (!empty(\Request::get('states')))
            $reminders = $reminders->whereIn('state', !empty(\Request::get('states')) ? \Request::get('states') : []);
        if (!empty(\Request::get('search'))){
            // $reminders = $reminders->where('customer_id', trim(\Request::get('search')));
            $cols = $this->searches;
            $reminders->where(function($query) use ($cols){
                $search = \Request::get('search');
                foreach ($cols as $col){
                    $query->orWhere($col, 'like', '%'.($search).'%');
                }
            });
        }
        $query1 = clone $reminders;
        $query2 = clone $reminders;
        $approved_reminders     = $query1->where('approveed', 1)->count();
        $un_approved_reminders  = $query2->where('approveed', 0)->count();
        $reminders = $reminders->orderBy('approveed', 'ASC');
        $reminders = $reminders->orderBy('remind_at', 'ASC');
        $reminders = $reminders->take(2000)->get();
        return [
            'input_data'            => \Request::all(),
            'reminders'             => $reminders,
            'approved_reminders'    => $approved_reminders,
            'un_approved_reminders' => $un_approved_reminders
        ];
    }

    public function postConfirmationReminder(){
        try {
            CustomerReminder::find(\Request::get('id'))
                ->update([
                    'approveed'         => 1,
                    'approved_note'     => \Request::get('message'),
                    'approved_time'     => datetime_now()
                ]);
            return ['status'=>'success', 'message'=>'Update successfully!'];
        } catch (Exception $e) {
            return ['status'=>'error', 'message'=>'Whoops, looks like something went wrong', 'info'=>$e->getMessage()];
        }
    }

    public function getReminder(){
        $reminder = CustomerReminder::find(\Request::get('id'));
        $reminder->customer_info      = Customer::find($reminder->customer_id);
        $reminder->user_created_info  = User::find($reminder->user_created_id);
        $reminder->reminder_user_info = User::find($reminder->reminder_user_id);
        return $reminder;
    }

    public function postReminderChangeDate(){
        try {
            $reminder = CustomerReminder::find(\Request::get('id'));
            $from_date = date('Y-m-d', strtotime($reminder->remind_at));
            $to_date = date('Y-m-d', strtotime(\Request::get('new_date')));
            $day_diff = number_of_working_days($from_date, $to_date);
            if ($from_date != $to_date && $day_diff > 0){
                $reminders = CustomerReminder::where('customer_id', $reminder->customer_id)
                                        ->where('remind_at', '>', $reminder->remind_at)
                                        ->where('id', '<>', $reminder->id)
                                        ->get();
                foreach ($reminders as $k => $row){
                    CustomerReminder::find($row->id)
                            ->update([
                                'remind_at' => date('Y-m-d H:i:s', strtotime($row->remind_at.'+'.$day_diff.'weekday'))
                            ]);
                }
            }
            $reminder->update([
                'remind_at' => \Request::get('new_date'),
                'note'      => \Request::get('note')
            ]);
            return ['status'=>'success', 'message'=>'Cập nhật chuyển ngày nhắc nhở thành công!'];
        } catch (Exception $e) {
            return ['status'=>'error', 'message'=>'Whoops, looks like something went wrong', 'info'=>$e->getMessage()];
        }
    }
}

