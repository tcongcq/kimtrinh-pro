<?php
namespace App\Http\Controllers\Admin\Customer;

use InnoSoft\CMS\API;
use App\Admin\Customer\Customer;
use App\Admin\Customer\CustomerContact;
use App\Admin\Resource\ReminderTemplate;

class CustomerContactController extends API
{   
    public function __construct() {
        $this->M = new CustomerContact();
        $this->view = 'admin.pages.customer.customer_contacts';
        $this->validator_msg = [];
    }

    protected function prepare_index(){
        return $this->M
                ->where('customer_id', 1)
                ->orderBy('created_at', 'DESC');
    }

    public function getIndex(){
        return view($this->view, [
            "reminder_templates" => ReminderTemplate::get_reminder_templates(),
            "user_assigned_list" => Customer::get_users()
        ]);
    }

    protected function prepare_add(){
        \Request::merge([
            'user_created_id' => \Auth::user()->id,
            'customer_id'     => 1,
            'active'          => 1
        ]);
        if (empty(\Request::get('phone')) && empty(\Request::get('email')))
            return ['status'=>'error', 'message'=>'Thông tin khách hàng phải có ít nhất số điện thoại hoặc email!'];
        if (!empty(\Request::get('phone')) && !empty($this->M->where('phone', \Request::get('phone'))->first()))
            return ['status'=>'error', 'message'=>'Số điện thoại khách hàng đã có trên hệ thống!'];
        return ['status'=>'success'];
    }

    public function postAssignUser(){
        try {
            foreach (\Request::get('ids') as $k => $id){
                CustomerContact::create_customer($id, \Request::get('user_id'), \Request::get('template_id'));
            }
            return ['status'=>'success','message'=>'Chọn Sale PIC cho khách hàng thành công!'];
        } catch (Exception $e) {
            return ['status'=>'error','message'=>$e->getMessage()];
        }
    }

    public function getCheckDuplicate(){
        $checkFields = ['name','phone','email','brand_name'];
        $checkExists = $this->M->where(function($query) use ($checkFields){
                            foreach ($checkFields as $col){
                                $query->orWhere($col, 'like', '%'.(\Request::get('input_data')).'%');
                            }
                        })->get();
        return $checkExists;
    }
}
