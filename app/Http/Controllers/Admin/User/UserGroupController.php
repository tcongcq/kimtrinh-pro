<?php

namespace App\Http\Controllers\Admin\User;

use InnoSoft\CMS\API;
use App\Admin\User\User;
use App\Admin\User\UserGroup;
use App\Admin\Setting\System;

class UserGroupController extends API {

    protected $view = 'admin.pages.user.user_groups';
    protected $validator_msg = [];
    protected $belong_to_system  = 'MAIN';

    public function __construct(){
        $this->M = new UserGroup;
        if(!User::has_permission(['route'=>'user-group','permission_name'=>'access']))
            abort(404);
    }

    public function getIndex(){
        $permissions = collect(\DB::table('permissions')->orderBy('id')->get());
        return view($this->view)->with('permissions', $permissions);
    }

    protected function prepare_add(){
        if(!User::has_permission(['route'=>'user-group','permission_name'=>'insert']))
            return ['status'=>'error','message'=>"Bạn không có quyền thêm thông tin."];
        return ['status'=>'success'];
    }
    protected function callback_add($data){
        $permissions = \Request::has('permissions') ? \Request::get('permissions'):[];
        foreach ($permissions as $permission) {
            \DB::table('user_group_permissions')->insert(
                ['permission_id' => $permission,'user_group_id' => $data->id,'belong_to_system'=>$this->belong_to_system]
            );
        }
        return $data;
    }
    protected function prepare_update(){
      if(!User::has_permission(['route'=>'user-group','permission_name'=>'update'])) {
        return ['status'=>'error','message'=>'Bạn không quyền cập nhật thông tin.'];
      }
        $user_group_id = \Request::get('id');
        $this->M->rules['group_name'] .= ','. $user_group_id;
        $permissions = \Request::has('permissions') ? \Request::get('permissions'):[];
        $permissionsOld = \Request::has('permissionsOld') ? \Request::get('permissionsOld'):[];
        $permissionsAdd = collect($permissions)->diff($permissionsOld);
        $permissionsDelete = collect($permissionsOld)->diff($permissions);
        //delete user_group_permissions old
        \DB::table('user_group_permissions')
            ->where('user_group_id', $user_group_id)
            ->where('belong_to_system', $this->belong_to_system)
            ->whereIn('permission_id',$permissionsDelete)->delete();
        // add user_group_permissions new
        foreach ($permissionsAdd as $permission) {
            \DB::table('user_group_permissions')->insert(
              ['permission_id' => $permission,'user_group_id' => $user_group_id,'belong_to_system'=>$this->belong_to_system]
            );
        }
        return ['status'=>'success'];
    }
    protected function prepare_delete($ids){
        if(!User::has_permission(['route'=>'user-group','permission_name'=>'delete'])) {
            return ['status'=>'error','message'=>'Bạn không quyền xóa thông tin.'];
        }
        return ['status'=>'success'];
    }
    public function postUserGroupPermissions(){
        $user_group_id = \Request::get('user_group_id');
        $query = \DB::table('user_group_permissions')->where('user_group_id',$user_group_id)
            ->where('belong_to_system',$this->belong_to_system)
            ->orderBy('permission_id')->pluck('permission_id');
        return $query;
    }
  
}