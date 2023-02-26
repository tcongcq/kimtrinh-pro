<?php

namespace App\Admin\Setting;

use App\Admin\General\Model;
use Illuminate\Support\Str;

class System extends Model
{
    protected $fillable = [
        'code',
        'attribs',
        'value',
    ];
    public $rules       = [
        'code'          => 'required',
        'attribs'   	=> '',
        'value'         => 'required',
    ];
    public static function get_attrib($code=''){
        return self::where('id', '>', 0)->where('code', $code)->first();
    }
    public static function set_attrib($code='', $value=''){
        try {
            if (empty(self::get_attrib($code)))
                self::create([
                    'code'      => $code,
                    'attribs'   => '',
                    'value'     => $value
                ]);
            else
                self::where('code', $code)->update(['value'=>$value]);
        } catch (Exception $e) {
            
        }
    }

    public static function get_user_agent(){
        return $_SERVER['HTTP_USER_AGENT'];
    }

    public static function get_client_ip(){
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
           $ipaddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    // public static function get_user_info($id=0){
    //     if (empty($id)) $id = \Auth::user()->id;
    //     $user_info = UserInfo::find_or_create_info($id);
    //     return $user_info;
    // }
    // public static function get_user_upload_dir($id=0){
    //     return self::get_user_info($id)->upload_dir;
    // }
}






