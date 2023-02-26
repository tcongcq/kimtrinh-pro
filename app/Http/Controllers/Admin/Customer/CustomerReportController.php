<?php
namespace App\Http\Controllers\Admin\Customer;

use InnoSoft\CMS\API;
use App\Admin\User\User;
use App\Admin\Customer\Customer;

class CustomerReportController extends API
{   
    public function __construct() {
        $this->M = new Customer();
        $this->view = 'admin.pages.customer.customer_reports';
        $this->validator_msg = [];
    }

    public function postStat(){
        $data   = self::prepare_data();
        return $data;
        // return view('admin.forms.customer.customer_report')->with($data);
    }

    public function prepare_data(){
        $from_date  = \Request::has('from_date') 
                        ? date('Y-m-d 00:00:00', strtotime(\Request::get('from_date')))
                        : date('Y-m-01 00:00:00');
        $to_date    = \Request::has('to_date')
                        ? date('Y-m-d 23:59:59', strtotime(\Request::get('to_date')))
                        : date('Y-m-t 23:59:59');
        $input_data = \Request::all();
        if (!empty(\Request::get('reminder_user_ids')))
            $input_data['reminder_users'] = User::whereIn('id', \Request::get('reminder_user_ids'))->get();
        return array_merge(
            ['input_data' => $input_data],
            self::get_reminders($from_date, $to_date),
            self::get_contacts($from_date, $to_date)
        );
    }
    public function get_reminders($from_date, $to_date){
        $reminders = \DB::table('v_customer_reminders')->where('active', 1);
        $reminders = $reminders->where('remind_at', '>=', $from_date)
                        ->where('remind_at', '<=', $to_date);
        if (!User::has_permission(['route'=>'customer-report','permission_name'=>'access-all']))
            $reminders = $reminders->where('reminder_user_id', \Auth::user()->id);
        elseif (!empty(\Request::get('reminder_user_ids')))
            $reminders = $reminders->whereIn('reminder_user_id', !empty(\Request::get('reminder_user_ids')) ? \Request::get('reminder_user_ids') : []);
        if (!empty(\Request::get('states')))
            $reminders = $reminders->whereIn('state', !empty(\Request::get('states')) ? \Request::get('states') : []);
        $query1 = clone $reminders;
        $query2 = clone $reminders;
        $approved_reminders     = $query1->where('approveed', 1)->pluck('id');
        $un_approved_reminders  = $query2->where('approveed', 0)->pluck('id');
        $customers = $reminders->where('customer_id', '>', 1)
                            ->where('state', 'POTENTIAL')
                            ->groupBy('customer_id')
                            ->pluck('customer_id');
        return [
            'potential_customers'   => $customers,
            'approved_reminders'    => $approved_reminders,
            'un_approved_reminders' => $un_approved_reminders
        ];
    }
    public function get_contacts($from_date, $to_date){
        $contacts = \DB::table('v_customer_contacts');
        $query1 = clone $contacts;
        $query2 = clone $contacts;
        if (!User::has_permission(['route'=>'customer-report','permission_name'=>'access-all']))
            $contacts = $contacts->where('user_sale_id', \Auth::user()->id);
        elseif (!empty(\Request::get('reminder_user_ids')))
            $contacts = $contacts->whereIn('user_sale_id', !empty(\Request::get('reminder_user_ids')) ? \Request::get('reminder_user_ids') : [\Auth::user()->id]);
        $query3 = clone $contacts;
        $query4 = clone $contacts;
        $sale_pics = !empty(\Request::get('reminder_user_ids')) ? \Request::get('reminder_user_ids') : [\Auth::user()->id];
        $input_contacts     = $query1->where('created_at', '>=', $from_date)
                        ->where('created_at', '<=', $to_date)
                        ->where('customer_id', '>', 1)
                        ->whereIn('user_created_id', $sale_pics)
                        ->groupby('customer_id')
                        ->pluck('customer_id');
        return [
            'input_contacts'        => $input_contacts
        ];
    }

    public function postShowDetail(){
        switch (\Request::get('type')){
            case 'reminder':
                $details = \DB::table('v_customer_reminders')
                                ->whereIn('id', \Request::get('ids'))
                                ->get();
                break;
            case 'customer':
                $details = \DB::table('v_customers')->whereIn('id', \Request::get('ids'))
                                ->get();

                break;
            default:
                $details = \DB::table('v_customer_contacts')
                                ->where('id', '<>', 1)
                                ->whereIn('customer_id', \Request::get('ids'))
                                ->get();
                break;
        }
        return $details;
    }
}

