<?php

namespace App\Http\Controllers\Admin\Setting;

use InnoSoft\CMS\API;
use App\Admin\Setting\System;

class SystemConfigController extends API {

    protected $view = 'admin.pages.setting.system_configs';
    protected $validator_msg = [];

    public function __construct() {
        $this->M = new System();
        $this->extensions = [
            'jpeg' ,'bmp', 'cod', 'gif', 'ief', 'jpg', 'jfif', 'tif', 'ras', 'cmx', 'ico', 'pnm', 'pbm', 'pgm', 'ppm', 'rgb', 'xbm', 'xpm', 'xwd', 'png', 'jps', 'fh',
            '7z', 'gz', 'rar', 'tar', 'zip',
            'ac3', 'aiff', 'm4a', 'mid', 'mp3', 'ogg', 'wav',
            'accdb', 'ade', 'adp', 'mdb',
            'avi', 'mov', 'mp4', 'mpeg', 'mpg', 'webm', 'wma',
            'csv', 'xls', 'xlsx',
            'doc', 'docx', 'odb', 'odf', 'odg', 'odp', 'ods', 'odt', 'otg', 'rtf', 'txt',
            'pdf',
            'ppt', 'pptx',
            'css', 'html', 'js', 'php', 'sql'
        ];
    }

    public function getIndex(){
        $data   = self::prepare_data();
        return view($this->view)->with($data)->with('supported_extensions',$this->extensions);
    }

    public function postStat(){
        $data   = self::prepare_data();
        return view('admin.forms.setting.system_config')->with($data);
    }

    public function prepare_data(){
        $data = [];
        foreach (System::where('attribs', 'CONFIG')->get() as $config){
            $var_val = snake_case($config->code);
            $data[ $var_val ] = $config->value;
        }
        return $data;
    }

    public function postSaveConfig(){
        try {
            $results = [];
            foreach (\Request::all() as $key => $cf_val){
                if ($key == '_token')
                    continue;
                $has_config = System::where(['code' => studly_case($key)])->first();
                if (!empty($has_config)){
                    System::where(['code' => studly_case($key)])
                            ->update([
                                'value'     => \Request::get($key) ? \Request::get($key) : ''
                            ]);
                } else {
                    System::create([
                        'code'    => studly_case($key),
                        'attribs' => 'CONFIG',
                        'value'   => (\Request::get($key) ? \Request::get($key) : '')
                    ]);
                }
            }
            return ['status'=>'success', 'message'=>'Update successfully!'];
        } catch (Exception $e) {
            return ['status'=>'error', 'message'=>$e->getMessage()];
        }
    }

}
