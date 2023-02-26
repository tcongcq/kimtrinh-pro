<?php
namespace App\Admin\Customer;

use App\Admin\General\Model;

class CustomerProduct extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'customer_id',
        'user_created_id',
        'note',
        'active'
    ];
    public $rules       = [
        'code'          => '',
        'name'          => 'required',
        'description'   => '',
        'customer_id'   => 'required',
        'user_created_id' => 'required',
        'note'          => '',
        'active'        => 'required'
    ];

    public static function get_customer_product($customer_id = null){
        return self::where("customer_id", $customer_id)
                ->orderBy("created_at", "ASC")
                ->get();
    }
}
