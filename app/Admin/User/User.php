<?php
namespace App\Admin\User;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'fullname',
        'username',
        'password',
        'avatar',
        'remember_token',
        'token_login',
        'device_token',
        'login_backend',
        'login_frontend',
        'gender',
        'birthday',
        'phone',
        'email',
        'address',
        'active',
        'locked',
        'guest',
        'attribs',
        'last_login',
        'protected',
        'anonymous',
        'user_group_id',
        'user_group_name',
        'note',
    ];
    public $rules       = [
        'fullname'          => '',
        'username'          => 'required|unique:users,username',
        'password'          => 'required|min:6|confirmed',
        'avatar'            => '',
        'remember_token'    => '',
        'token_login'       => '',
        'device_token'      => '',
        'login_backend'     => '',
        'login_frontend'    => '',
        'gender'            => '',
        'birthday'          => '',
        'phone'             => /*'required|regex:/^[0]{1}[189]{1}[0-9]{8,9}$/|unique:users,phone'*/'',
        'email'             => 'required|email|unique:users,email',
        'address'           => '',
        'active'            => 'required',
        'locked'            => '',
        'guest'             => '',
        'attribs'           => '',
        'last_login'        => '',
        'protected'         => '',
        'anonymous'         => '',
        'user_group_id'     => '',
        'user_group_name'   => '',
        'note'              => '',
    ];
    protected $hidden = [
        'password', 'remember_token',
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public $timestamps = true;

    protected static $belong_to_system  = 'MAIN';

    public static function get_info() {
        return \Auth::user();
    }
    public static function get_device_token(){
        try {
            if (!empty(\Auth::user()->device_token))
                return \Auth::user()->device_token;
            $device_token = \Str::random(50);
            self::find(\Auth::user()->id)->update(['device_token'=>$device_token]);
            return $device_token;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

    }

    public static function get_current($id){
        return self::where('id', $id)->first();
    }
    public static function has_permission($params){
        // return true;
        $alias  = $params['route'];
        if ($alias == 'dashboard')
            return true;
        $per_name = isset($params['permission_name']) ? $params['permission_name'] : 'access';
        $user_id  = !empty($params['user_id']) ? $params['user_id'] : \Auth::user()->id;
        $access = self::check_permission($per_name, $alias, $user_id);
        return !is_null($access['permission']);
    }
    public static function check_permission($name, $alias, $user_id=null){
        $query = \DB::table('users')->select(
                'users.id AS id',
                'users.username AS username',
                'roles.user_group_id AS user_group_id',
                'user_groups.group_name AS group_name',
                'user_groups.note AS note',
                'user_group_permissions.permission_id AS permission_id',
                'permissions.name AS name',
                'permissions.alias AS alias',
                'user_group_permissions.ids AS ids',
                'users.email AS email',
                'users.address AS address',
                'users.phone AS phone',
                'users.gender AS gender',
                'users.active AS active',
                'users.fullname AS fullname'
            )
            ->join('roles', 'roles.user_id', '=', 'users.id')
            ->join('user_groups', 'roles.user_group_id', '=', 'user_groups.id')
            ->join('user_group_permissions', 'user_group_permissions.user_group_id', '=', 'user_groups.id')
            ->join('permissions', 'user_group_permissions.permission_id', '=', 'permissions.id');
        $query = $query->where([
            'users.id' => !empty($user_id) ? $user_id : \Auth::user()->id,
            'permissions.name'  => $name,
            'permissions.alias' => $alias,
            'user_group_permissions.belong_to_system' => self::$belong_to_system
        ]);
        return ['count'=>$query->count(), 'permission'=>$query->first()];
    }
    public function allowed_filemanager() {
        // check permission of current user here
        // and return the filemanager config or empty array
        $user = self::where('device_token', $_COOKIE['x-user-session'])->first();
        if (empty($user)) return [];
        $config = config('filemanager.auth.admin');
        if (!self::has_permission(['route'=>'attachment','permission_name'=>'access-all','user_id'=>$user->id]))
            $config['upload_dir'] = $config['upload_dir'].'/user-storages/'.leading_zero($user->id);
        return $config === '' ? [] : $config;
    }
}