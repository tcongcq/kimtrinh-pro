<?php

namespace App\Http\Controllers\Admin\Resource;

use InnoSoft\CMS\API;
use App\Admin\Customer\Customer;
use App\Admin\Resource\Attachment;

class AttachmentController extends API
{   
    public function __construct() {
        $this->M = new Attachment();
        $this->view = 'admin.pages.resource.attachments';
        $this->validator_msg = [];
    }

    protected function callback_index($data){
        foreach ($data['rows'] as $row){
            $row->customer_info = Customer::find($row->customer_id);
        }
        return $data;
    }
}

