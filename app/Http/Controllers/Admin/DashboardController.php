<?php

namespace App\Http\Controllers\Admin;

use InnoSoft\CMS\API;
use App\Admin\User\User;
use App\Admin\Customer\Customer;
use App\Admin\Resource\Notification;
use App\Admin\Customer\CustomerMessage;
use App\Admin\Customer\CustomerReminder;

class DashboardController extends API
{   
    public function __construct() {
        $this->M = new \Account();
        $this->view = 'admin.pages.dashboards';
        $this->validator_msg = [];
        $this->searches = ['code','name','display_name','phone','email','position','company_name','brand_name','company_address','link','field','branch','state','contract_state','payment_state','describe','source','demand','concern','note'];
    }

    public function getIndex(){
        return view($this->view, [
            'device_token'   => User::get_device_token()
        ]);
    }

    public function getSearchCustomer(){
        $customers = Customer::where("active", "<>", 0);
        if (!empty(\Request::get('search'))){
            $cols = $this->searches;
            $customers->where(function($query) use ($cols){
                $search = trim(\Request::get('search'));
                foreach ($cols as $col){
                    $query->orWhere($col, 'like', '%'.($search).'%');
                }
            });
        }
        $customers = $customers->orderBy("created_at", "DESC")->take(50)->get();
        if (\Request::get('search_by') == 'search-bar')
            return view('admin.blocks.dashboard.search_customer', ['customers'=>$customers])->render();
        return view('admin.forms.dashboard', ['customers'=>$customers])->render();
    }

    public function getMessageList(){
        $messages = CustomerMessage::get_message(\Request::get('customer_id'));
        $data = [
            'customer_id' => \Request::get('customer_id'),
            'messages' => $messages
        ];
        if (\Request::get('type_get') == 'JSON')
            return $data;
        return view('admin.blocks.dashboard.message', $data)->render();
    }
    public function getUnfinishedReminder(){
        $data = CustomerReminder::get_my_incomplete_reminder();
        if (\Request::get('type_get') == 'JSON')
            return $data;
        return view('admin.blocks.dashboard.reminder', ['reminders'=>$data])->render();
    }


    public function getProfile(){
        return view('admin.pages.personal_informations')->with('profile', \Auth::user() );
    }
    public function postProfile(){
        $input = \Request::all();
        $data = [];
        foreach ($input['data'] as $element) {
            $data[ $element['name'] ] = $element['value'];
        }
        $data['birthday'] = \Request::get('dayOfBirth');
        if ( empty( $data['password'] ) || $data['password'] != $data['r_password'] ){
            unset( $data['password'], $data['r_password'] );
        } else {
            $data['password'] = \Hash::make( $data['password'] );
            unset( $data['r_password'] );
        }
        $curUser = User::find( \Auth::user()->id );
        if ( empty($curUser) )
            return ['status'=>'error', 'message'=>'Không có người dùng này!'];
        $curUser->update($data);
        return ['status'=>'success', 'message'=>'Cập nhật thành công!'];
    }

    public function getUnseenNotif(){
        $data = Notification::get_my_notif();
        if (\Request::get('type_get') == 'JSON')
            return $data;
        return view('admin.blocks.dashboard.notification', ['notifs'=>$data])->render();
    }
    public function getSeenNotif(){
        Notification::find(\Request::get('id'))->update(['seen'=>1]);
        return ['status'=>'success'];
    }
}



/* 
    Vào trang cá nhân đổi hình đại diện vs mật khẩu

    cho 1 sản phẩm tên chưa xác định
    Địa chỉ và giấy tờ tùy thân cho qua tab sản phẩm.
*/

