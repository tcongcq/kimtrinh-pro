@foreach($customers as $customer)
<tr>
    <td style="text-align: center;">
        <a class="badge badge-warning" href="customer?id={{ $customer->id }}" target="_blank">#{{ $customer->id }}</a><br/>
    </td>
    <td>
        <div style="width: 130px; display: inline-block;">
            <i class="glyphicon glyphicon-bullhorn"></i> Tên thương hiệu: 
        </div>
        <strong>{{ $customer->brand_name }}</strong> <br />
        <div style="width: 130px; display: inline-block;">
            <i class="glyphicon glyphicon-phone-alt"></i> Email: 
        </div>
        <strong>{{ $customer->email }}</strong> <br />
    	<div style="width: 130px; display: inline-block;">
            <i class="glyphicon glyphicon-phone-alt"></i> SĐT: 
        </div>
        <strong>{{ $customer->phone }}</strong> <br />
        <div style="width: 130px; display: inline-block;">
            <i class="glyphicon glyphicon-comment"></i> Nhu cầu: 
        </div>
        <strong>{{ !empty($customer->demand) ? $customer->demand : '(chưa có)' }}</strong> <br />
        <strong class="text-warning text-bold hidden">{{ number_format($customer->final_price,0, ',', '.') }}đ</strong>
        <div style="width: 130px; display: inline-block;">
            - Trạng thái: 
        </div>
        <strong>{!! state_to_text($customer->state) !!}</strong> <br/>
        <div style="width: 130px; display: inline-block;">
            - Ngày tạo: 
        </div>
        <strong>{{ date('H:m:s d/m/Y', strtotime($customer->created_at)) }}</strong> <br />
    </td>
    <td class="hidden" style="text-align: left;">
        <div style="width: 130px; display: inline-block;">
            - Giá trị sản phẩm: 
        </div>
        <strong class="text-blue text-bold">{{ number_format($customer->first_payment,0, ',', '.') }}đ</strong> <br />
        <div style="width: 130px; display: inline-block;">
            - Khoản đảm bảo: 
        </div>
        <strong class="text-green text-bold">{{ number_format($customer->second_payment,0, ',', '.') }}đ</strong> <br />
        <div style="width: 130px; display: inline-block;">
            - Tiền đợt 1 đóng: 
        </div>
        <strong class="text-warning text-bold">{{ number_format($customer->listed_price,0, ',', '.') }}đ</strong> <br /><div style="width: 130px; display: inline-block;">
            - Tiền đợt 2 đóng: 
        </div>
    </td>
    <td style="text-align: center;">
    	<div class="btn btn-default" onclick="toolbar.show_chat({{ $customer->id }})"><i class="fa fa-pencil"></i></div>
    </td>
</tr>
@endforeach