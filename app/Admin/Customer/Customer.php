<?php

namespace App\Admin\Customer;

use App\Admin\General\Model;
use App\Admin\User\User;
use App\Admin\Resource\Product;
use App\Admin\Resource\Attachment;
use App\Admin\Customer\CustomerMessage;
use App\Admin\Customer\CustomerProduct;
use App\Admin\Customer\CustomerReminder;
use App\Admin\Resource\ReminderTemplate;
use App\Admin\Resource\ReminderTemplateItem;

class Customer extends Model
{
    protected $fillable = [
        'code',
        'name',
        'display_name',
        'phone',
        'email',
        'birthday',
        'position',
        'company_name',
        'brand_name',
        'company_address',
        'link',
        'field',
        'branch',
        'active',
        'priority',
        'state',
        'contract_state',
        'payment_state',
        'sign_date',
        'describe',
        'source',
        'demand',
        'concern',
        'user_assigned_id',
        'user_created_id',
        'upload_dir',
        'note',
        'state_changed_at',
        'deleted_at'
    ];
    public $rules       = [
        'code'              => '',
        'name'              => 'required',
        'display_name'      => '',
        'phone'             => '',
        'email'             => '',
        'birthday'          => '',
        'position'          => '',
        'company_name'      => '',
        'brand_name'        => '',
        'company_address'   => '',
        'link'              => '',
        'field'             => '',
        'branch'            => '',
        'active'            => '',
        'priority'          => '',
        'state'             => '',
        'contract_state'    => '',
        'payment_state'     => '',
        'sign_date'         => '',
        'describe'          => '',
        'source'            => '',
        'demand'            => '',
        'concern'           => '',
        'user_created_id'   => 'required',
        'user_assigned_id'  => 'required',
        'upload_dir'        => '',
        'note'              => '',
        'state_changed_at'  => '',
        'deleted_at'        => '',
    ];
    protected $hidden = [
        'deleted_at'
    ];
    public static function boot(){
        parent::boot();

        self::created(function($model){
            // CustomerContact::sync_from_customer($model->id);
            $dir = implode('/', [config('cms.customer_dir'), leading_zero($model->id).'-'.\Str::random()]);
            self::find($model->id)->update([
                'code'       => leading_zero($model->id),
                'upload_dir' => $dir
            ]);
            if(!file_exists($dir)) mkdir($dir, 0777, true);
            return $model;
        });

        // self::updated(function($model){
        //     // ... code here
        // });

        self::deleted(function($model){
            if (!empty($model->upload_dir) && $model->upload_dir != '/')
                Attachment::unlink(public_path($model->upload_dir));
            return $model;
        });
    }
    public function newQuery($excludeDeleted = true) {
        $query = parent::newQuery($excludeDeleted);
        if (\Account::has_permission(['route'=>'customer','permission_name'=>'access-all']))
            return $query;
        return $query->where(function($query){
            $query->orWhere('user_assigned_id', \Auth::user()->id);
            $query->orWhere('user_created_id', \Auth::user()->id);
        });
    }
    public static function get_customer_upload_dir($customer_id){
        try {
            $customer = self::find($customer_id);
            if (!empty($customer->upload_dir))
                $dir = $customer->upload_dir;
            else{
                $dir = implode('/', [config('cms.customer_dir'), leading_zero($customer->id).'-'.\Str::random()]);
                self::find($customer_id)->update(['upload_dir'=>$dir]);
            }
            if(!file_exists($dir)) mkdir($dir, 0777, true);
            return $dir;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
    public static function add_customer_product($code, $customer_id){
        $product = Product::where("code", $code)->first();
        if (!empty($product)){
            $product = $product->toArray();
            $product['id'] = null;
            $product['customer_id'] = $customer_id;
            $product['user_created_id'] = \Auth::user()->id;
            return CustomerProduct::create($product);
        }
        return null;
    }

    public static function get_users(){
        return User::where("anonymous", 0)->where("active", 1)->get();
    }

    public static function generate_remidner($customer_id, $template_id){
        try {
            $customer = self::find($customer_id);
            if (empty($customer) || $customer->user_assigned_id == 1)
                return ['status'=>'error','message'=>'Đơn hàng chưa được phân cho Sale!'];
            $template = ReminderTemplate::find($template_id);
            $reminder_items = ReminderTemplateItem::get_reminder_template_items($template_id, 1);
            $currentDate = now();
            $reminders = [];
            foreach ($reminder_items as $idx => $item){
                $newObject = [
                    'name'              => $item->name,
                    'description'       => $item->description,
                    'type'              => $item->type,
                    'remind_at'         => date('Y-m-d 09:00:00', strtotime($currentDate.$item->remind_at.'weekday')),
                    'customer_id'       => $customer->id,
                    'reminder_user_id'  => $customer->user_assigned_id,
                    'user_created_id'   => \Auth::user()->id,
                    'approveed'         => $item->approveed,
                    'approved_note'     => $item->approved_note,
                    'approved_time'     => $item->approved_time,
                    'priority'          => $template->priority,
                    'active'            => $item->active,
                    'note'              => null,
                ];
                array_push($reminders, $newObject);
            }
            CustomerReminder::insert($reminders);
            return ['status'=>'success','message'=>'Tạo nhắc nhở theo mẫu thành công!'];
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public static function reset_remidner($customer_id){
        try {
            CustomerReminder::where('customer_id', $customer_id)
                        ->where('remind_at', '>', date('Y-m-d'))
                        ->delete();
            return ['status'=>'success','message'=>'Reset nhắc nhở thành công!'];
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
