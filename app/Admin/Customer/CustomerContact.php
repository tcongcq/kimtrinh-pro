<?php
namespace App\Admin\Customer;

use App\Admin\General\Model;
use App\Admin\Customer\Customer;
use App\Admin\Resource\Notification;

class CustomerContact extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'email',
        'client_name',
        'brand_name',
        'field',
        'demand',
        'concern',
        'note',
        'customer_id',
        'user_created_id',
        'user_sale_id',
        'active',
        'assigned_at'
    ];
    public $rules       = [
        'name'              => 'required',
        'phone'             => '',
        'email'             => '',
        'client_name'       => '',
        'brand_name'        => '',
        'field'             => '',
        'demand'            => '',
        'concern'           => '',
        'note'              => '',
        'customer_id'       => 'required',
        'user_created_id'   => 'required',
        'user_sale_id'      => '',
        'active'            => '',
        'assigned_at'       => ''
    ];
    public function newQuery($excludeDeleted = true) {
        $query = parent::newQuery($excludeDeleted);
        if (\Account::has_permission(['route'=>'customer-contact','permission_name'=>'access-all']))
            return $query;
        return $query->where('user_created_id', \Auth::user()->id);
    }

    public static function create_customer($id, $user_id, $template_id){
        try {
            $contact  = self::find($id);
            $customer = Customer::create([
                'name'              => $contact->name,
                'display_name'      => $contact->name,
                'phone'             => $contact->phone,
                'email'             => $contact->email,
                'company_name'      => $contact->client_name,
                'brand_name'        => $contact->brand_name,
                'field'             => $contact->field,
                'active'            => 1,
                'state'             => 'APPROACHING',
                'demand'            => $contact->demand,
                'concern'           => $contact->concern,
                'user_created_id'   => \Auth::user()->id,
                'user_assigned_id'  => $user_id,
                'upload_dir'        => '',
                'note'              => $contact->note,
            ]);
            $title = [
                '<strong>'.\Auth::user()->fullname.'</strong>', 'đã phân khách hàng',
                '<strong>'.$customer->brand_name.'</strong>',
                'cho bạn.'
            ];
            Notification::create([
                'title'             => implode(" ", $title),
                'user_notified_id'  => $customer->user_assigned_id,
                'user_created_id'   => $customer->user_created_id,
                'ref_url'           => 'admin/customer?id='.$customer->id,
                'seen'              => 0
            ]);
            if (!empty($template_id))
                Customer::generate_remidner($customer->id, $template_id);
            $contact->update(['customer_id'=>$customer->id, 'user_sale_id'=>$user_id, 'assigned_at'=>date('Y-m-d H:i:s')]);
            return ['status'=>'success', 'message'=>'Create successfully!'];
        } catch (Exception $e) {
            return ['status'=>'error', 'message'=>$e->getMessage()];
        }
    }

    public static function sync_from_customer($customer_id){
        try {
            $customer = Customer::find($customer_id);
            self::create([
                'name'              => $customer->name,
                'phone'             => $customer->phone,
                'email'             => $customer->email,
                'client_name'       => $customer->company_name,
                'brand_name'        => $customer->brand_name,
                'field'             => $customer->field,
                'demand'            => $customer->demand,
                'concern'           => $customer->concern,
                'note'              => $customer->note,
                'customer_id'       => $customer_id,
                'user_created_id'   => $customer->user_created_id,
                'user_sale_id'      => $customer->user_assigned_id,
                'active'            => $customer->active,
                'assigned_at'       => $customer->created_at
            ]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

}