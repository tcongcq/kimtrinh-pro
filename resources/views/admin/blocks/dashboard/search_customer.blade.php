@foreach($customers as $customer)
<tr>
    <td style="text-align: center; vertical-align: middle;">
        <a class="badge badge-warning" href="customer?id={{ $customer->id }}" target="_blank">{{ leading_zero($customer->id) }}</a><br/>
    </td>
    <td>
        @if(!empty($customer->display_name))
        <div style="width: 135px; display: inline-block;">
            <i class="glyphicon glyphicon-user"></i> Tên gợi nhớ: 
        </div>
        <strong>{{ $customer->display_name }}</strong> <br />
        @endif
        <div style="width: 135px; display: inline-block;">
            <i class="glyphicon glyphicon-bullhorn"></i> Tên thương hiệu: 
        </div>
        <strong>{{ $customer->brand_name }}</strong> <br />
        <div style="width: 135px; display: inline-block;">
            <i class="glyphicon glyphicon-user"></i> Khách hàng: 
        </div>
        <strong>{{ $customer->name }}</strong> <br />
    	<div style="width: 135px; display: inline-block;">
            <i class="glyphicon glyphicon-phone-alt"></i> SĐT: 
        </div>
        <strong>{{ $customer->phone }}</strong> <br />
        <div style="width: 135px; display: inline-block;">
            <i class="glyphicon glyphicon-envelope"></i> Email: 
        </div>
        <strong>{{ !empty($customer->email) ? $customer->email : '---' }}</strong> <br />
        <div style="width: 135px; display: inline-block;">
            <i class="glyphicon glyphicon-comment"></i> Nhu cầu: 
        </div>
        <strong>{{ $customer->demand }}</strong> <br />
        <div style="width: 130px; display: inline-block;">
            Trạng thái: 
        </div>
        <strong>{!! state_to_text($customer->state) !!}</strong> <br />
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
        <strong class="text-warning text-bold">{{ number_format($customer->final_price,0, ',', '.') }}đ</strong> <br />
    </td>
</tr>
@endforeach
@if(count($customers) == 0)
<tr>
    <td colspan="3" class="text-center"><i>(Không tìm thấy dữ liệu)</i></td>
</tr>
@endif