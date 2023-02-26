<?php

namespace App\Http\Controllers\Admin\Setting;

use InnoSoft\CMS\API;
use App\Admin\Setting\Permission;

class PermissionController extends API {

    protected $view = 'admin.pages.setting.permissions';
    protected $validator_msg = [];

    public function __construct() {
        $this->M = new Permission();
    }

    protected function prepare_index(){
        return $this->M->where('id', '>', 0);
    }

}
