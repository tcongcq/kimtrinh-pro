<?php
namespace App\Admin\Customer;

use App\Admin\General\Model;
use App\Admin\Customer\Customer;

class CustomerMessage extends Model
{
    protected $fillable = [
        'type',
        'title',
        'content',
        'note',
        'customer_id',
        'user_created_id',
    ];
    public $rules       = [
        'type' => '',
        'title' => '',
        'content' => 'required',
        'note' => '',
        'customer_id' => 'required',
        'user_created_id' => 'required',
    ];
    // public function newQuery($excludeDeleted = true) {
    //     $query = parent::newQuery($excludeDeleted);
    //     // if (User::has_permission(['route'=>'customer-message','permission_name'=>'access-all']))
    //     //     return $query;
    //     return $query->where('user_created_id', \Auth::user()->id);
    // }

    public static function get_message($customer_id = null){
        return \DB::table('v_customer_messages')
                ->where("customer_id", $customer_id)
                ->orderBy("created_at", "DESC")
                ->take(100)
                ->get();
    }

    public static function create_message($request){
        try {
            self::create($request);
            return ['status'=>'success', 'message'=>'Create successfully!'];
        } catch (Exception $e) {
            return ['status'=>'error', 'message'=>$e->getMessage()];
        }
    }
}