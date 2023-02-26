<?php

namespace App\Admin\General;

use Illuminate\Database\Eloquent\Model as EModel;

class Model extends EModel
{
    public $timestamps = true;
    
    public function newQuery($excludeDeleted = true) {
        return parent::newQuery($excludeDeleted)->where('id', '>', 1);
    }
}






