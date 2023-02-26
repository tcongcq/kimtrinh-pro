<?php
namespace App\Http\Controllers\Admin\Customer;

use Mail;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Session;
use InnoSoft\CMS\API;
use App\Admin\User\User;
use App\Admin\Customer\Customer;
use App\Admin\Customer\CustomerContact;
use App\Admin\Customer\CustomerMessage;
use App\Admin\Customer\CustomerProduct;
use App\Admin\Customer\CustomerReminder;
use App\Admin\Resource\Attachment;
use App\Admin\Resource\Product;
use App\Admin\Resource\ReminderTemplate;
use App\Admin\Resource\ReminderTemplateItem;

class CustomerController extends API
{
    public function __construct() {
        $this->M = new Customer();
        $this->view = 'admin.pages.customer.customers';
        $this->validator_msg = [
            //
        ];
    }

    // public function getTest(){
    //     $result = Mail::send('admin.mails.test', [
    //             'name' => "abc",
    //             'email' => "tcongcq@test.com",
    //             'content' => "test content"
    //         ], function($message){
    //         $message->to('tcongcq@gmail.com', 'Bla bla bla')->subject('Bla bla Feedback!');
    //     });
    //     Session::flash('flash_message', 'Send message successfully!');
    //     return ['abc'=>$result];
    // }

    private function collectUserList(){
        $user_assigned_ids = Customer::distinct('user_assigned_id')->pluck('user_assigned_id')->toArray();
        $user_created_ids  = Customer::distinct('user_created_id')->pluck('user_created_id')->toArray();
        $user_ids = array_unique(array_merge($user_assigned_ids,$user_created_ids));
        $users = collect(User::whereIn('id', $user_ids)->get())->mapWithKeys(function($user, $key) {
            return [$user->id => $user];
        });
        return $users;
    }

    public function getIndex(){
        return view($this->view, [
            "products" => Product::get_active_products(),
            "reminder_templates" => ReminderTemplate::get_reminder_templates(),
            "user_assigned_list" => Customer::get_users()
        ]);
    }
    protected function prepare_index(){
        $query = $this->M->orderBy('created_at', 'DESC');
        $filters = \Request::get('filters');
        if (!empty($filters)){
            $product_codes = Arr::where($filters, function ($value, $key) {
                return $value['key'] == 'customer_product_codes';
            });
            $product_codes = Arr::first($product_codes);
            if (!empty($product_codes)){
                $query->whereExists(function ($query1) use ($product_codes) {
                    $query1->select(\DB::raw(1))
                        ->from('customer_products')
                        ->whereIn('code', $product_codes['value'])
                        ->whereRaw('customers.id=customer_products.customer_id');
                });
                $filters = Arr::where($filters, function ($value, $key) {
                    return $value['key'] != 'customer_product_codes';
                });
                \Request::merge(['filters'=>$filters]);
            }
        }
        return $query;
    }
    protected function callback_index($data){
        $users = self::collectUserList();
        foreach ($data['rows'] as $row){
            $row->user_created_info = $row->user_created_id == 1 ? null : $users[$row->user_created_id];
            $row->user_assigned_info = $row->user_assigned_id == 1 ? null : $users[$row->user_assigned_id];
        }
        return $data;
    }
    public function getCurrentRow(){
        $row = Customer::find(\Request::get('id'));
        $row->user_created_info = User::find($row->user_created_id);
        $row->user_assigned_info = User::find($row->user_assigned_id);
        return $row;
    }

    protected function prepare_add(){
        \Request::merge([
            'user_created_id' => \Auth::user()->id,
            'user_assigned_id'=> \Auth::user()->id,
            'active'          => 1
        ]);
        return ['status'=>'success'];
    }

    protected function callback_add($data){
        try {
            $user_created_info = User::find($data->user_assigned_id);
            $this->M->where('id', $data->id)->update([
                'code' => leading_zero($data->id)
            ]);
        } catch (Exception $e) {
            
        }
        return $data;
    }

    protected function prepare_update(){
        $curRow = $this->M->find(\Request::get('id'));
        if (!empty(\Request::get('state')) && $curRow->state != \Request::get('state'))
            \Request::merge(['state_changed_at'=>date('Y-m-d H:i:s')]);
        if (\Request::get('state') == 'CANCELLED')
            Customer::reset_remidner($curRow->id);
        if (\Request::get('state') == 'COOPERATED'){
            $products = CustomerProduct::where('customer_id', $curRow->id)->first();
            if (empty($products))
                return ['status'=>'error', 'message'=>'Vui lòng thêm dịch vụ hợp tác!'];
        }
        return ['status'=>'success'];
    }

    protected function prepare_delete($ids){
        try {
            foreach (Attachment::whereIn('customer_id', $ids)->get() as $key => $att){
                Attachment::find($att->id)->delete();
            }
            CustomerContact::whereIn('customer_id', $ids)->delete();
            return ['status'=>'success'];
        } catch (Exception $e) {
            return ['status'=>'error', 'message'=>$e->getMessage()];
        }
    }

    public function getCurrentAdditional(){
        $additionals = [];
        if (in_array('message', \Request::get('params')))
            $additionals['customer_messages'] = CustomerMessage::get_message(\Request::get('id'));
        if (in_array('reminder', \Request::get('params')))
            $additionals['customer_reminders'] = CustomerReminder::get_reminder(\Request::get('id'));
        if (in_array('attachment', \Request::get('params')))
            $additionals['customer_attachments'] = Attachment::get_customer_attachment(\Request::get('id'));
        if (in_array('product', \Request::get('params')))
            $additionals['customer_products'] = CustomerProduct::get_customer_product(\Request::get('id'));
        return $additionals;
    }

    public function postCreateReminder(){
        try {
            \Request::merge([
                'reminder_user_id' => !empty(\Request::get('reminder_user_id')) ? \Request::get('reminder_user_id') : \Auth::user()->id,
                'user_created_id'  => \Auth::user()->id,
                'active'           => 1,
                'approveed'        => 0
            ]);
            return CustomerReminder::create_reminder(\Request::all());
        } catch (Exception $e) {
            return ['status'=>'error', 'message'=>$e->getMessage()];
        }
    }

    public function postAcceptReminder(){
        try {
            \Request::merge([
                'approveed'     => 1,
                'approved_time' => datetime_now()
            ]);
            return CustomerReminder::accept_reminder(\Request::all());
        } catch (Exception $e) {
            return ['status'=>'error','message'=>$e->getMessage()];
        }
    }

    public function postDeleteReminder(){
        try {
            return CustomerReminder::delete_reminder(\Request::all());
        } catch (Exception $e) {
            return ['status'=>'error','message'=>$e->getMessage()];
        }
    }

    public function postCreateMessage(){
        try {
            \Request::merge([
                'user_created_id' => \Auth::user()->id
            ]);
            return CustomerMessage::create_message(\Request::all());
        } catch (Exception $e) {
            return ['status'=>'error','message'=>$e->getMessage()];
        }
    }

    public function postCreateAttachment(){
        try {
            $upload_file= \Request::get('attachment_upload_file');
            $attachment = public_path(\Request::get('attachment_upload_file'));
            $filename   = \File::name($attachment);
            $file_ext   = \File::extension($attachment);
            $file_type  = \File::mimeType($attachment);
            $customer_dir   = Customer::get_customer_upload_dir(\Request::get('customer_id'));
            $customer_file  = $customer_dir.'/'.$filename.'.'.$file_ext;
            $copy   = \File::copy($attachment, public_path($customer_file));
            if (!$copy)
                return ['status'=>'error','message'=>'Has an Internal Error Server!'];
            switch ($file_ext) {
                case 'zip':
                    $path       = public_path( $customer_file );
                    $temp_dir   = $customer_dir.'/upload';
                    \Zipper::make($path)->extractTo( $temp_dir );
                    self::create_attachments($temp_dir, \Request::get('customer_id'));
                    if (!empty($customer_file))
                        Attachment::unlink($customer_file);
                    return ['status'=>'success', 'message'=>'Create new attachments successfully!'];
                    break;
                default:
                    $result = Attachment::create([
                        'name'              => $filename,
                        'src'               => $customer_file,
                        'file_type'         => $file_type,
                        'file_extension'    => $file_ext,
                        'customer_id'       => \Request::get('customer_id'),
                        'user_created_id'   => \Auth::user()->id,
                        'important'         => 0,
                        'description'       => '',
                        'note'              => ''
                    ]);
                    return ['status'=>'success', 'message'=>'Create new attachment successfully!'];
                    break;
            }
            return ['status'=>'error','message'=>'Has an Internal Error Server!'];
        } catch (Exception $e) {
            return ['status'=>'error','message'=>$e->getMessage()];
        }
    }

    public function postDeleteAttachment(){
        try {
            $attachment = Attachment::find(\Request::get('id'));
            if ($attachment->important)
                return ['status'=>'error', 'message'=>'Attachment is important file!'];
            $attachment->delete();
            return ['status'=>'success', 'message'=>'Delete attachment successfully!'];
        } catch (Exception $e) {
            return ['status'=>'error','message'=>$e->getMessage()];
        }
    }

    public function create_attachments($upload_dir, $customer_id){
        $image_lists = [];
        $photo_format_allowed = ['jpeg', 'jpg', 'png'];
        foreach (new \DirectoryIterator($upload_dir) as $file) {
            if (!$file->isDot()) {
                if (!$file->isDir()) {
                    $ex     = $file->getExtension();
                    $name   = $file->getBasename('.'.$ex);
                    $file_name  = $name.'.'.$ex;
                    $image_link = $upload_dir.'/'.$file_name;
                    $file_type  = \File::mimeType($image_link);
                    array_push($image_lists, [
                        'name'              => $name,
                        'src'               => $image_link,
                        'file_type'         => $file_type,
                        'file_extension'    => $ex,
                        'customer_id'       => $customer_id,
                        'user_created_id'   => \Auth::user()->id,
                        'important'         => 0,
                        'description'       => '',
                        'note'              => ''
                    ]);
                }
            }
        }
        try {
            foreach ($image_lists as $idx => $image){
                Attachment::create($image);
            }
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public static function postImport(){
        include(base_path('public/plugin/Classes/PHPExcel/PHPExcel/IOFactory.php'));
        $path = 'public/uploads';
        $dir  = base_path($path);
        if (!file_exists($dir))
            mkdir($dir, 0777, true);
        if (!file_exists(base_path($path . '/success')))
            mkdir(base_path($path . '/success'), 0777, true);
        if ( file_exists(base_path($path.'/success/'.$_FILES['file']['name'])) )
            return ['status'=>'error', 'msg'=>'Dữ liệu đã có trong hệ thống không thể thêm!'];
        $nameArray       = explode('.', $_FILES['file']['name']);
        $ext             = array_pop($nameArray);
        if (!in_array($ext, ['xls', 'xlsx']))
            return ['status' => 'error', 'msg' => 'Định dạng không hợp lệ!'];
        try {
            move_uploaded_file($_FILES['file']['tmp_name'], 'uploads/' . $_FILES['file']['name']);
        } catch (\Exception $e) {
            return ['status' => 'error', 'msg' => 'Không có file nhập dữ liệu!'];
        }
        try {
            $inputFileName = $dir . '/' . $_FILES['file']['name'];
            //Xac dinh kieu file
            $inputFileType = \PHPExcel_IOFactory::identify($inputFileName);
            //Tao doi tuong reader de doc file
            $objReader     = \PHPExcel_IOFactory::createReader($inputFileType);
            //Load noi dung file
            $objPHPExcel   = $objReader->load($inputFileName);
            //Doc tat ca cac sheet cua file
            $allSheetName  = $objPHPExcel->getSheetNames();
            $sum           = 0;
            foreach ($allSheetName as $curSheet)
            {
                $sheetData = $objPHPExcel->getSheetByName($curSheet)->toArray(null, true, true, true);
                $sum       += count($sheetData);
            }
            // giới hạn số dòng tối đa một lần thêm vào CSDL
            $limitRow = 1000;
            if ($sum > $limitRow)
                return ['status' => 'warning', 'msg' => 'Tổng số lượng dòng vượt giới hạn: '.$sum.'/'.$limitRow .' dòng!'];
            foreach ($allSheetName as $curSheet){
                $sheetData = $objPHPExcel->getSheetByName($curSheet)->toArray(null, true, true, true);
                $r         = self::testData($sheetData);
                if ($r['status'] != 'success')
                    return ['status' => $r['status'], 'msg' => 'File không đúng mẫu, vui lòng định dạng theo mẫu!!', 'info' => $r['info'], 'sheet' => $curSheet];
                $proccessData[] = $r['data'];
            }

            // return ['status'=>'warning','msg'=>$proccessData];
            
            $result = self::createData($proccessData);
            try {
                copy($inputFileName, $dir . '/success/' . $_FILES['file']['name']);
                Attachment::unlink($inputFileName);
            } catch (Exception $e) {

            }
        } catch (\Exception $e) {
            return ['status' => 'warning', 'msg' => 'Đã có lỗi xảy ra trong quá trình thêm dữ liệu!', 'info' => $e->getMessage()];
        }
        return ['status' => 'success', 'msg' => 'Nhập dữ liệu vào hệ thống thành công!', 'data' => $proccessData];
    }


    private static function testData($sheetData){
        $data         = array();
        unset($sheetData[1], $sheetData[2], $sheetData[3], $sheetData[4]);
        $errorRows    = [];
        $curNumberRow = 4;
        foreach ($sheetData as $index => $row)
        {
            $curRow = array();
            $services = array();
            $curNumberRow++;
            if (empty($row['B']) && empty($row['C']))
                continue;
            $curRow['user_created_id']  = \Auth::user()->id;
            $curRow['upload_dir']     = 'default';
            foreach ($row as $key => $element){
                switch ($key){
                    case 'A':
                        continue 2;
                    case 'B':
                        if (!empty($element))
                            $curRow['field']     = $element;
                        continue 2;
                    case 'C':
                        if (!empty($element)){
                            $curRow['company_name']     = $element;
                            $curRow['brand_name']     = $element;
                        }
                        continue 2;
                    case 'D':
                        if (!empty($element))
                            $curRow['company_address']     = $element;
                        continue 2;
                    case 'E':
                        if (!empty($element))
                            $curRow['link']     = $element;
                        continue 2;
                    case 'F':
                        if (!empty($element))
                            $curRow['branch']     = $element;
                        continue 2;
                    case 'G':
                        if (!empty($element))
                            $curRow['name']     = $element;
                        else
                            $curRow['name']     = '---';
                        continue 2;
                    case 'H':
                        if (!empty($element))
                            $curRow['position']     = $element;
                        continue 2;
                    case 'I':
                        if (!empty($element))
                            $curRow['email']     = $element;
                        continue 2;
                    case 'J':
                        if (!empty($element))
                            $curRow['phone']     = $element;
                        continue 2;
                    case 'K':
                        if (!empty($element)){
                            $date = \DateTime::createFromFormat('d/m/Y', $element);
                            $curRow['birthday'] = $date->format('Y-m-d');
                        }
                        continue 2;
                    case 'L':
                        if (!empty($element))
                            $curRow['state']     = $element;
                        continue 2;
                    case 'M':
                        if (!empty($element)){
                            $date = \DateTime::createFromFormat('m/Y', $element);
                            $curRow['sign_date'] = $date->format('Y-m-01');
                        }
                        continue 2;
                    case 'N':
                        if (!empty($element))
                            array_push($services, 'PRODUCTION');
                        continue 2;
                    case 'O':
                        if (!empty($element))
                            array_push($services, 'MARKETING');
                        continue 2;
                    case 'P':
                        if (!empty($element))
                            array_push($services, 'TRY-FREE');
                        continue 2;
                    case 'Q':
                        if (!empty($element))
                            array_push($services, 'VOUCHER');
                        continue 2;
                    case 'R':
                        if (!empty($element))
                            array_push($services, 'SHOP');
                        continue 2;
                    case 'S':
                        if (!empty($element))
                            array_push($services, 'OTHERS');
                        continue 2;
                    case 'T':
                        if (!empty($element))
                            $curRow['username'] = $element;
                        continue 2;    
                    case 'U':
                        if (!empty($element))
                            $curRow['describe'] = $element;
                        continue 2;
                    case 'V':
                        if (!empty($element))
                            $curRow['source'] = $element;
                        continue 2;
                    default:
                        continue 2;
                }
            }
            $curRow['services'] = $services;
            if (!empty($curRow['state']) && $curRow['state'] == 'COOPERATED'){
                $curRow['contract_state'] = 'COMPLETED';
                $curRow['payment_state']  = 'DONE';
            }
            if (count($curRow))
                $data[] = $curRow;
        }
        if (!empty($errorRows))
            return ['status' => 'errors', 'info' => $errorRows];
        return ['status' => 'success', 'data' => $data];
    }

    private static function createData($sheetData){
        foreach ($sheetData as $sheet)
        {
            try {
                foreach ($sheet as $key => $row){
                    if (!empty($row['username'])){
                        $user = User::where('username', $row['username'])->first();
                        $row['user_assigned_id'] = $user->id;
                    }
                    $customer = Customer::create($row);
                    if (!empty($row['services'])){
                        foreach($row['services'] as $ks => $s){
                            Customer::add_customer_product($s, $customer->id);
                        }
                    }
                }
            } catch (Exception $e) {
                return ['status' => 'error', 'info' => $e->getMessage()];
            }
        }
        return ['status' => 'success', 'msg' => 'Nhập dữ liệu thành công!'];
    }

    public function postCreateCustomerProduct(){
        try {
            $r = Customer::add_customer_product(\Request::get('code'), \Request::get('customer_id'));
            if (!$r)
                return ['status'=>'success','message'=>'Dịch vụ không khả dụng, thử reload lại trang!'];
            return ['status'=>'success','message'=>'Thêm dịch vụ thành công!'];
        } catch (Exception $e) {
            return ['status'=>'error','message'=>$e->getMessage()];
        }
    }

    public function postUpdateCustomerProduct(){
        try {
            $r = CustomerProduct::find(\Request::get('id'))->update(\Request::all());
            if (!$r)
                return ['status'=>'success','message'=>'Dịch vụ không khả dụng, thử reload lại trang!'];
            return ['status'=>'success','message'=>'Cập nhật dịch vụ thành công!'];
        } catch (Exception $e) {
            return ['status'=>'error','message'=>$e->getMessage()];
        }
    }

    public function postDeleteCustomerProduct(){
        try {
            $r = CustomerProduct::find(\Request::get('id'))->delete();
            if (!$r)
                return ['status'=>'success','message'=>'Dịch vụ không khả dụng, thử reload lại trang!'];
            return ['status'=>'success','message'=>'Xoá dịch vụ thành công!'];
        } catch (Exception $e) {
            return ['status'=>'error','message'=>$e->getMessage()];
        }
    }

    public function postGenerateReminder(){
        try {
            return Customer::generate_remidner(\Request::get('id'), \Request::get('template_id'));
        } catch (Exception $e) {
            return ['status'=>'error','message'=>$e->getMessage()];
        }
    }

    public function postAssignUser(){
        try {
            $customer = Customer::find(\Request::get('id'))->update(['user_assigned_id'=>\Request::get('user_id')]);
            return ['status'=>'success','message'=>'Chọn Sale PIC cho khách hàng thành công!'];
        } catch (Exception $e) {
            return ['status'=>'error','message'=>$e->getMessage()];
        }
    }

    public function postResetReminder(){
        try {
            return Customer::reset_remidner(\Request::get('id'));
        } catch (Exception $e) {
            return ['status'=>'error','message'=>$e->getMessage()];
        }
    }
}

