<?php

namespace App\Http\Controllers\Admin\Resource;

use InnoSoft\CMS\API;
use App\Admin\Resource\ReminderTemplate;
use App\Admin\Resource\ReminderTemplateItem;

class ReminderTemplateController extends API {

    protected $view = 'admin.pages.resource.reminder_templates';
    protected $validator_msg = [];

    public function __construct() {
        $this->M = new ReminderTemplate();
    }

    protected function prepare_index(){
        return $this->M->where('id', '>', 0);
    }

    protected function callback_index($data){
        foreach ($data['rows'] as $row){
            $row->reminder_template_items = ReminderTemplateItem::get_reminder_template_items($row->id);
        }
        return $data;
    }

    public function getCurrentAdditional(){
        $additionals = [];
        if (in_array('reminder-item', \Request::get('params')))
            $additionals['reminder_template_items'] = ReminderTemplateItem::get_reminder_template_items(\Request::get('id'));
        return $additionals;
    }

    public function postCreateReminderTemplateItem(){
        try {
            $r = ReminderTemplateItem::create(\Request::all());
            return ['status'=>'success','message'=>'Thêm nhắc nhở mẫu thành công!'];
        } catch (Exception $e) {
            return ['status'=>'error','message'=>$e->getMessage()];
        }
    }

    public function postUpdateReminderTemplateItem(){
        try {
            $r = ReminderTemplateItem::find(\Request::get('id'))->update(\Request::all());
            return ['status'=>'success','message'=>'Cập nhật nhắc nhở mẫu thành công!'];
        } catch (Exception $e) {
            return ['status'=>'error','message'=>$e->getMessage()];
        }
    }

    public function postDeleteReminderTemplateItem(){
        try {
            $r = ReminderTemplateItem::find(\Request::get('id'))->delete();
            return ['status'=>'success','message'=>'Xoá nhắc nhở mẫu thành công!'];
        } catch (Exception $e) {
            return ['status'=>'error','message'=>$e->getMessage()];
        }
    }
}
