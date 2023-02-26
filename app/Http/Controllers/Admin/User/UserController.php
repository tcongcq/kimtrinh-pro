<?php

namespace App\Http\Controllers\Admin\User;

use InnoSoft\CMS\API;
use App\Admin\User\User;
use App\Admin\User\Role;
use App\Admin\User\UserGroup;
use App\Admin\Setting\System;

class UserController extends API
{
    protected $view = 'admin.pages.user.users';
    protected $validator_msg = [
        'username' => 'Tên đăng nhập',
        'password' => 'Mật khẩu'
    ];

    public function __construct() {
        $this->M = new User();
    }
    public function prepare_index(){
        $this->M = $this->M->where("id", ">", 1);
        if(!\Auth::user()->anonymous)
            return $this->M->where('anonymous', '<>', 1);
        return $this->M;
    }
    protected function callback_index($data){
        $rows = $data['rows'];
        foreach ($rows as $key => $row){
            $row->user_group_id = Role::where('user_id', $row->id)->value('user_group_id');
            $row->user_group_name = UserGroup::where('id', $row->user_group_id)->value('group_name');
        }
        return $data;
    }
    public function getIndex(){
        $userGroups = UserGroup::all();
        return view($this->view)->with('user_groups', $userGroups);
    }
    protected function prepare_add(){
    	if (!empty(\Request::get('password'))) {
            $password = \Hash::make(\Request::get('password'));
            \Request::merge([
                'password'              => $password,
                'password_confirmation' => $password
            ]);
        } else {
            return ['status'=>'error', 'message' => 'Mật khẩu không được rỗng'];
        }
        return ['status'=>'success'];
    }
    protected function callback_add($data){
        $user_group_id = \Request::get('user_group_id');
        \DB::table('roles')->insert([
            'user_id'           =>  $data->id,
            'user_group_id'     =>  $user_group_id
        ]);
        return $data;
    }
    protected function prepare_update(){
        $user_id = \Request::get('id');
        $user_group_id = \Request::get('user_group_id');
        $this->M->rules['username'] .= ','.$user_id;
        $this->M->rules['email'] .= ','.$user_id;
        $this->M->rules['phone'] = 'regex:/^[0]{1}[1-9]{1}[0-9]{8,9}$/|unique:users,phone,'.$user_id;
        if (\Request::has('password') && \Request::get('password')!='') {
            $password = \Hash::make(\Request::get('password'));
            \Request::merge([
                'password'      => $password,
                'password_confirmation' => $password
            ]);
        }else{
            $password = User::findOrFail(\Request::get('id'))->password;
            \Request::merge([
                'password' => $password,
                'password_confirmation' => $password
            ]);
        }
        // update table roles
        try {
            \DB::table('roles')->where('user_id', $user_id)
                ->update([
                    'user_group_id' => $user_group_id
                ]);
        } catch (Exception $e) {
            
        }
        return ['status'=>'success'];
    }

    protected function prepare_delete($ids){
        if(collect($ids)->contains(\Auth::user()->id)){
            return ['status'=>'error','message'=>'Bạn không quyền xóa tài khoản của chính mình.'];
        }
        if(!\App\Admin\User::has_permission(['route'=>'user','permission_name'=>'delete'])) {
            return ['status'=>'error','message'=>'Bạn không quyền xóa thông tin.'];
        }
      return ['status'=>'success'];
    }
}
