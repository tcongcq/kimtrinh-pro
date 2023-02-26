<?php
namespace App\Http\Controllers\Admin\Customer;

use InnoSoft\CMS\API;
use App\Admin\Customer\Customer;
use App\Admin\Customer\CustomerContact;

class CustomerContactController extends API
{   
    public function __construct() {
        $this->M = new CustomerContact();
        $this->view = 'admin.pages.customer.customer_contacts';
        $this->validator_msg = [];
    }

    public function postStat(){
        $data   = self::prepare_data();
        $from_date  = \Request::get('from_date');
        $to_date    = \Request::get('to_date');
        return array_merge($data, [
            'states'       => !empty(\Request::get('states')) ? \Request::get('states') : ['DRAFT', 'RUNNING' ,'SUCCESS', 'CANCEL'],
            'from_date'    => date('d/m/Y', strtotime($from_date)),
            'to_date'      => date('d/m/Y', strtotime($to_date))
        ]);
        return view('admin.forms.customer_contact')->with([
            'data'         => $data,
            'states'       => !empty(\Request::get('states')) ? \Request::get('states') : ['DRAFT', 'RUNNING' ,'SUCCESS', 'CANCEL'],
            'from_date'    => date('d/m/Y', strtotime($from_date)),
            'to_date'      => date('d/m/Y', strtotime($to_date))
       	]);
    }

    public function prepare_data(){
    	$results = CustomerContact::where('id', '>', 0);
        $from_date = !empty(\Request::get('from_date') )
                    ? date('Y-m-d 00:00:00', strtotime(\Request::get('from_date')))
                    : date('Y-m-01 00:00:00');
        $to_date = !empty(\Request::get('to_date'))
                    ? date('Y-m-d 23:59:59', strtotime(\Request::get('to_date')))
                    : date('Y-m-t 23:59:59');
        $results = $results->where('created_at', '>=', $from_date)
                        ->where('created_at', '<=', $to_date);
        if (!empty(\Request::get('search'))){
            $results = $results->where(function($query){
                $search_list = ['name', 'phone', 'email', 'client_name', 'customer_id', 'demand', 'concern', 'field'];
                foreach ($search_list as $field){
                    $query = $query->orWhere($field, 'like', '%'.\Request::get('search').'%');
                }
            });
        }
        // if (!empty(\Request::get('states')))
        //     $results = $results->whereIn('state', \Request::get('states'));
        // else
        //     $results = $results->whereIn('state', ['DRAFT', 'RUNNING']);
       	// return ['xxx'];
        $sale_chosen = null;
        // if (!User::has_permission(['route'=>'event-contact','permission_name'=>'access-all']))
        //     $results = $results->where('user_sale_id', \Auth::user()->id);
        if (!empty(\Request::get('user_sale_id'))){
            $sale_chosen = \Account::where('id', \Request::get('user_sale_id'))->first();
            $results = $results->whereIn('user_created_id', [1, \Request::get('user_sale_id')]);
        }
        $query_clone = clone $results;
        $results = $results->orderBy('user_created_id', 'ASC')->get();
        $count_free_row = $query_clone->where('user_created_id', 0)->count();
        // foreach ($results as $idx => $row){
        //     $results[$idx] = self::get_current_row_info($row);
        // }
    	return [
            'rows'          => $results,
            'sale_chosen'   => $sale_chosen,
            'count_free_row'=> $count_free_row
        ];
    }
}
