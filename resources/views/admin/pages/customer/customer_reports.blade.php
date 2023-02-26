@extends('cms::layouts.crud')

@section('assets')
@include('admin.assets')
@endsection

@section('main')
@include('admin.blocks.assets.style')
@include('admin.blocks.assets.hide_nav_menu')
<script type="text/javascript">
const now = new Date();
function Toolbar(){
    var self = this;
    self.statResult = ko.observable({});
    self.rInputData = ko.observable({});
    self.detailRows = ko.observableArray([]);
    self.detailType = ko.observable('');

    self.init = function(){
        $('.date').datetimepicker({
            format: 'DD/MM/YYYY',
            locale: 'vi'
        });
        var from_date = new Date(now.getFullYear(), now.getMonth());
        $('#from_date').data('DateTimePicker').defaultDate(from_date);
        $('#to_date').data('DateTimePicker').defaultDate(now);
        $('.selectpicker').selectpicker();
        $('[data-toggle="tooltip"]').tooltip();
        self.stat();
    };
    
    self.stat = function(){
    	var from_date = !$('#from_date input').val() ? '':$('#from_date').data("DateTimePicker").date().format('YYYY-MM-DD');
    	var to_date = !$('#to_date input').val() ? '':$('#to_date').data("DateTimePicker").date().format('YYYY-MM-DD');
        $.ajax({
            url: "{{ uri() }}/stat",
            type: "post",
            data: {
                _token: "{{ csrf_token() }}",
                from_date: from_date,
                to_date: to_date,
                reminder_user_ids: $('#reminder_user_ids').selectpicker('val'),
                states: $('#states').selectpicker('val')
            },
            beforeSend: showAppLoading, complete: hideAppLoading,
            success: function (data) {
                self.statResult(data);
                self.rInputData(data.input_data)
                // Chiều cao cho table thông tin
                let max_height = window.innerHeight - $('.inventory-toolbar').height() - $('#navbar-menu').height() - 40;
                $('.stat-main').css('max-height', (max_height >= 460 ? max_height : 1500));
            }
        });
    };
    self.stat_in_month = function(){
        var from_date = new Date(now.getFullYear(), now.getMonth());
        var to_date = new Date(now.getFullYear(), now.getMonth()+1, 0);
        $('#from_date').data('DateTimePicker').date(from_date);
        $('#to_date').data('DateTimePicker').date(to_date);
        self.stat();
    };
    self.stat_in_week = function(){
        var from_date = moment().startOf('week').toDate();
        var to_date   = moment().endOf('week').toDate();
        $('#from_date').data('DateTimePicker').date(from_date);
        $('#to_date').data('DateTimePicker').date(to_date);
        self.stat();
    };
    self.stat_in_day = function(){
        $('#from_date').data('DateTimePicker').date(now);
        $('#to_date').data('DateTimePicker').date(now);
        self.stat();
    };
    self.datetime_format = function(_date){
        return moment(_date).format('HH:mm DD/MM/YYYY');
    };
    self.date_format = function(_date){
        return moment(_date).format('DD/MM/YYYY');
    };
    self.show_detail = function(ids, type){
        self.detailRows([]);
    	self.detailType(typeof type == 'string' ? type : 'contact');
    	$.ajax({
            url: "{{ uri() }}/show-detail",
            type: "post",
            data: {
                _token: "{{ csrf_token() }}",
                type: self.detailType(),
                ids: ids
            },
            beforeSend: showAppLoading, complete: hideAppLoading,
            success: function (data) {
                self.detailRows(data);
                $('#modal-show-detail').modal('show');
            }
        });
    };
    self.get_state = function(state){
    	let text = '';
    	switch (state){
            case 'POTENTIAL':
                text = '<span class="label label-success">POTENTIAL</span>';
                break;
            case 'COOPERATED':
                text = '<span class="label label-danger">COOPERATED</span>';
                break;
            case 'APPROACHING':
                text = '<span class="label label-primary">APPROACHING</span>';
                break;
            case 'CANCELLED':
                text = '<span class="label label-default">CANCELLED</span>';
                break;
            default:
                text = '<span class="label label-info">DRAFT</span>';
                break;
        }
        return text;
    }
}
var toolbar = new Toolbar();
</script>
<div data-bind="template: {name: 'inventory-template', afterRender: toolbar.init}"></div>
<script type="text/html" id="inventory-template">
    <div class="inventory-toolbar">
        <div class="row">
            <div class="col-xs-6 col-sm-4 col-md-2">
                <div class="form-group">
                    <label for="from_date" class="control-label"><small>Từ ngày</small></label>
                    <div class='input-group date' id='from_date'>
                        <input type='text' class="form-control" />
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-xs-6 col-sm-4 col-md-2">
                <div class="form-group">
                    <label for="to_date" class="control-label"><small>Đến ngày</small></label>
                    <div class='input-group date' id='to_date'>
                        <input type='text' class="form-control" />
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-xs-6 col-sm-4 col-md-2">
                <div class="form-group">
                    <label for="states" class="control-label"><small>Trạng thái</small></label>
                    <select class="selectpicker form-control" id="states" data-live-search="true" multiple data-selected-text-format="count > 3" data-actions-box="true">
                    @foreach(config('cms.customer_state') as $idx => $state)
                        <option value="{{ $state }}" data-content="{!! state_to_text($state) !!}" >{{ $idx+1 }} {!! state_to_text($state) !!}</option>
                    @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label for="reminder_user_ids" class="control-label"><small>Nhân viên Sale</small></label>
                    <select class="selectpicker form-control" id="reminder_user_ids" data-live-search="true" multiple data-selected-text-format="count > 3" data-actions-box="true">
                    @foreach(\App\Admin\Customer\Customer::get_users() as $sale)
                        <option value="{{ $sale->id }}">{{ $sale->fullname }} {{ $sale->phone ? " - ($sale->phone)" : '' }}</option>
                    @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="btn btn-default mt-5" data-bind="click: toolbar.stat">
                    <i class="fa fa-search" aria-hidden="true"></i> Xem
                </div>
                <div class="btn btn-info mt-5" data-bind="click: toolbar.stat_in_day">
                    <i class="fa fa-search" aria-hidden="true"></i> Hôm nay
                </div>
                <div class="btn btn-info mt-5" data-bind="click: toolbar.stat_in_week">
                    <i class="fa fa-search" aria-hidden="true"></i> Tuần này
                </div>
                <div class="btn btn-info mt-5" data-bind="click: toolbar.stat_in_month">
                    <i class="fa fa-search" aria-hidden="true"></i> Tháng này
                </div>
            </div>
        </div>
    </div>
    <div class="inventory-body">
        <div class="overview" data-bind="visible: toolbar.statResult().input_data == undefined">
            <span class="icon"><i style="padding: 15px 0px 0px 0px;" class="fa fa-list-alt" aria-hidden="true"></i></span>
            <ul>
                <li>Xem danh sách các thông tin báo cáo của bạn.</li>
                <li>Xem thông tin chi tiết các báo cáo khách hàng của bạn có trong hệ thống.</li>
            </ul>
        </div>
        <div data-bind="visible: toolbar.statResult().input_data != undefined, with: toolbar">
        	@include('admin.forms.customer.customer_report')
        </div>
    </div>
</script>

<div class="modal" id="modal-show-detail" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body" data-bind="if: toolbar.detailType()=='contact'">
                <legend>Thông tin khách hàng</legend>
                <table class="table table-bordered table-hover" style="margin-bottom: 0px;">
                	<thead>
	                    <tr style="background-color: #f0ad4e; color: #fff;">
	                        <th style="padding: 3px 5px; text-align: center; vertical-align: middle;">STT</th>
	                        <th style="padding: 3px 5px; text-align: center; vertical-align: middle;">Khách hàng</th>
	                        <th style="padding: 3px 5px; text-align: center; vertical-align: middle;">Sale PIC</th>
	                    </tr>
	                </thead>
	                <tbody>
	                	<!-- ko foreach: toolbar.detailRows() -->
	                	<tr>
	                		<td class="center-middle">
	                			<span class="badge badge-warning" data-bind="text: customer_id"></span><br/>
	                			<span data-bind="html: toolbar.get_state($data.c_state)"></span>
	                		</td>
	                		<td>
                                <span data-bind="visible: c_brand_name">- Thương hiệu: <strong data-bind="text: c_brand_name"></strong></span> (<strong data-bind="text: field"></strong>)<br/>
	                			<span>- Tên khách hàng: </span> <strong data-bind="text: name"></strong>
	                		</td>
	                		<td>
                                <span data-bind="if: user_sale_id != 1">
    	                			<strong data-bind="text: cu_fullname"></strong>
                                </span>
                                <span data-bind="if: user_sale_id == 1">
                                    <span>(chưa có)</span>
                                </span>
	                		</td>
	                	</tr>
	                	<!-- /ko -->
	                </tbody>
                </table>
            </div>
            <div class="modal-body" data-bind="if: toolbar.detailType()=='customer'">
                <legend>Thông tin khách hàng</legend>
                <table class="table table-bordered table-hover" style="margin-bottom: 0px;">
                    <thead>
                        <tr style="background-color: #f0ad4e; color: #fff;">
                            <th style="padding: 3px 5px; text-align: center; vertical-align: middle;">STT</th>
                            <th style="padding: 3px 5px; text-align: center; vertical-align: middle;">Khách hàng</th>
                            <th style="padding: 3px 5px; text-align: center; vertical-align: middle;">Sale PIC</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- ko foreach: toolbar.detailRows() -->
                        <tr>
                            <td class="center-middle">
                                <span class="badge badge-warning" data-bind="text: id"></span><br/>
                                <span data-bind="html: toolbar.get_state($data.state)"></span>
                            </td>
                            <td>
                                <span data-bind="visible: brand_name">Thương hiệu: <strong data-bind="text: brand_name"></strong> (<strong data-bind="text: field"></strong>)<br/></span>
                                <span>Tên khách hàng: </span> <strong data-bind="text: name"></strong>
                            </td>
                            <td>
                                <strong data-bind="text: cu_fullname"></strong>
                            </td>
                        </tr>
                        <!-- /ko -->
                    </tbody>
                </table>
            </div>
            <div class="modal-body" data-bind="if: toolbar.detailType()=='reminder'">
                <legend>Danh sách nhắc nhở</legend>
                <table class="table table-bordered table-hover" style="margin-bottom: 0px;">
                    <thead>
                        <tr style="background-color: #f0ad4e; color: #fff;">
                            <th style="padding: 3px 5px; text-align: center; vertical-align: middle;">MSHĐ</th>
                            <th style="padding: 3px 5px; text-align: center; vertical-align: middle;">Đơn hàng</th>
                            <th style="padding: 3px 5px; text-align: center; vertical-align: middle;">Nhắc nhở</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- ko foreach: toolbar.detailRows() -->
                        <tr>
                            <td class="center-middle">
                                <span class="badge" data-bind="text: $index()+1"></span><br/>
                                <span class="badge badge-warning" data-bind="text: customer_id"></span>
                            </td>
                            <td>
                                <span data-bind="if: customer_id != 1">
                                    <div>
                                        <i class="glyphicon glyphicon-bullhorn"></i> <strong data-bind="text: $data.brand_name"></strong>
                                    </div>
                                    <div>
                                        <i class="glyphicon glyphicon-phone-alt"></i> <span  data-bind="text: $data.phone"></span> <br />
                                    </div>
                                </span>
                                <div>
                                    <strong class="pull-right" data-bind="html: toolbar.get_state($data.state)"></strong>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <strong>Nhắc nhở:</strong> <span data-bind="html: $data.name"></span>
                                </div>
                                <div>
                                    - Người thực hiện: <strong data-bind="text: $data.ru_fullname"></strong>
                                </div>
                                <div>
                                    - Thời gian: <strong data-bind="text: toolbar.datetime_format($data.remind_at)"></strong> || 
                                    <!-- ko if: $data.approveed == 1 -->
                                    <label class="label label-success">Đã xác nhận</label>
                                    <!-- /ko -->
                                    <!-- ko if: $data.approveed != 1 -->
                                    <label class="label label-default">Chưa xác nhận</label>
                                    <!-- /ko -->
                                </div>
                                <!-- ko if: $data.approved_note != 1 -->
                                <div>
                                    - Nội dung xác nhận: <strong data-bind="html: $data.approved_note"></strong>
                                </div>
                                <!-- /ko -->
                                <!-- ko if: $data.note && $data.customer_id != 1 -->
                                <div>
                                    - Nội dung chuyển ngày: <strong data-bind="html: $data.note"></strong>
                                </div>
                                <!-- /ko -->
                                <!-- ko if: $data.note && $data.customer_id == 1 -->
                                <div>
                                    - Ghi chú: <strong data-bind="html: $data.note"></strong>
                                </div>
                                <!-- /ko -->
                            </td>
                        </tr>
                        <!-- /ko -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection