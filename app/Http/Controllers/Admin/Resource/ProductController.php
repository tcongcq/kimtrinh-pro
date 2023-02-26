<?php

namespace App\Http\Controllers\Admin\Resource;

use InnoSoft\CMS\API;
use App\Admin\Resource\Product;

class ProductController extends API {

    protected $view = 'admin.pages.resource.products';
    protected $validator_msg = [];

    public function __construct() {
        $this->M = new Product();
    }

    protected function prepare_index(){
        return $this->M->where('id', '>', 0);
    }

}
