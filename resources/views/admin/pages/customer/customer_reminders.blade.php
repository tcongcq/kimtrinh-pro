@extends('cms::layouts.crud')

@section('assets')
@include('admin.assets')
<style>html, body{ background-color: #f9f9f9; }</style>
@endsection

@section('footer')
<!-- <script src="{{ url('assets/js/bootstrap-datepicker.vi.min.js') }}" charset="UTF-8"></script> -->
@endsection

@section('main')
@include('admin.blocks.assets.style')
@include('admin.blocks.assets.hide_nav_menu')
<script type="text/javascript">
const now = new Date();
function Toolbar(){
    var self = this;
    self.search 		= ko.observable('');
    self.msgResult 		= ko.observable('');
    self.statResult 	= ko.observable('');
    self.customer_reminder 	= ko.observable({});

    self.init = function(){
        $('.date').datetimepicker({
            format: 'DD/MM/YYYY',
            locale: 'vi'
        });
        $('.datetime').datetimepicker({
            format: 'HH:mm DD/MM/YYYY',
            locale: 'vi'
        });
        $('.time').datetimepicker({
            viewMode: 'days',
            format: 'HH:mm',
            locale: 'vi'
        });
        var from_date = new Date(now.getFullYear(), now.getMonth());
        // var to_date = new Date(now.getFullYear(), now.getMonth()+1, 0);
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
                approveeds: $('#approveeds').selectpicker('val'),
                states: $('#states').selectpicker('val'),
                search: self.search()
            },
            beforeSend: showAppLoading, complete: hideAppLoading,
            success: function (data) {
                self.statResult(data);
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
    }
    self.stat_all = function(){
        $('#approveeds').selectpicker('val', '');
        self.stat();
    };
    self.stat_suspend = function(val){
        $('#approveeds').selectpicker('val', val);
        self.stat();
    };
    self.keyup_search = function(e){
        if (e.key == 'Enter' && $('#search').val())
            self.stat();
    };
    self.date_format = function(_date){
        return moment(_date).format('HH:mm DD/MM/YYYY');
    };

    self.confirmation_reminder = function(id){
        var confirmation = prompt('Ghi chú xác nhận hoàn thành nhắc nhở. Chấp nhận từ 50 ký tự.');
        if (!confirmation)
            return false;
        if (confirmation.length < 50){
            alert('Chỉ chấp nhận từ 50 ký tự.')
            return false;
        }
        $.ajax({
            url: "{{ url(config('cms.backend_prefix').'/'.uri().'/confirmation-reminder') }}",
            type: "post",
            beforeSend: showAppLoading, complete: hideAppLoading,
            data: {
                _token: "{!! csrf_token() !!}",
                id: id,
                message: confirmation
            },
            success: function(data){
                toastr[data.status](data.message);
                if (data.status == 'success')
                    self.stat();
            }
        });
    };

    self.change_date = function(id){
        self.customer_reminder({});
        $.ajax({
            url: "{{ url(config('cms.backend_prefix').'/'.uri().'/reminder') }}",
            type: "get",
            beforeSend: showAppLoading, complete: hideAppLoading,
            data: {
                id: id
            },
            success: function(data){
                self.customer_reminder(data);
                $('#modal_change_date_time').data("DateTimePicker").date(new Date(data.remind_at));
                $('#modal-change-date').modal('show');
            }
        });
    };
    self.do_change_date = function(){
        var note     = self.customer_reminder().note;
        var datetime = $('#modal_change_date_time input').val() != '' ? $('#modal_change_date_time').data("DateTimePicker").date().format('YYYY-MM-DD HH:mm:00'):'';
        if (!note || !datetime){
            toastr['warning']("Chọn thời gian chuyển nhắc nhở và nhập ghi chú!");
            return false;
        }
        if (!confirm('Xác nhận chuyển ngày nhắc nhở đến thời gian mới '+self.date_format(datetime)))
            return false;
        $.ajax({
            url: "{{ url(config('cms.backend_prefix').'/'.uri().'/reminder-change-date') }}",
            type: "post",
            beforeSend: showAppLoading, complete: hideAppLoading,
            data: {
                _token: "{!! csrf_token() !!}",
                id: self.customer_reminder().id,
                note: note,
                new_date: datetime
            },
            success: function(data){
                toastr[data.status](data.message);
                if (data.status == 'success'){
                    $('#modal-change-date').modal('hide');
                    self.stat();
                }
            }
        });
    };
    self.show_chat = function(customer_id){
        $.ajax({
            url: "{{ url(config('cms.backend_prefix')) }}/dashboard/message-list",
            type: "get",
            data: {
                customer_id: customer_id
            },
            beforeSend: showAppLoading, complete: hideAppLoading,
            success: function (data) {
                self.msgResult(data);
                $('#chat-message').modal('show');
            }
        });
    };
    self.message_create = function(event, customer_id){
        var message = $('#inp-text-message').val();
        if (!event || event.keyCode != 13 || message.length == 0)
            return false;
        $.ajax({
            url: "{{ url(config('cms.backend_prefix').'/customer/create-message') }}",
            type: "post",
            // beforeSend: showAppLoading, complete: hideAppLoading,
            data: {
                _token: "{!! csrf_token() !!}",
                content: message,
                customer_id: customer_id
            },
            success: function(data){
                self.show_chat(customer_id);
                $('#inp-text-message').val('');
            }
        });
    };
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
                    <label for="approveeds" class="control-label"><small>Xác nhận</small></label>
                    <select class="selectpicker form-control" id="approveeds" data-live-search="true" multiple>
                        <option value="1">Đã xác nhận</option>
                        <option value="0">Chưa xác nhận</option>
                    </select>
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
            <div class="col-xs-6 col-sm-4 col-md-2
                @if(!\Account::has_permission(['route'=>'customer-reminder','permission_name'=>'access-all']))
                hidden
                @endif
                ">
                <div class="form-group">
                    <label for="reminder_user_ids" class="control-label"><small>Nhân viên Sale</small></label>
                    <select class="selectpicker form-control" id="reminder_user_ids" data-live-search="true" multiple data-selected-text-format="count > 3" data-actions-box="true">
                    @foreach(\App\Admin\Customer\Customer::get_users() as $sale)
                        <option value="{{ $sale->id }}">{{ $sale->fullname }} {{ $sale->phone ? " - ($sale->phone)" : '' }}</option>
                    @endforeach
                    </select>
                </div>
            </div>
            <div class="col-xs-6 col-sm-4 col-md-2">
                <div class="form-group mb-0">
                    <label for="time_access" class="control-label"><small>Tìm đơn hàng</small></label>
                    <div class="input-group">
                        <input type="text" id="search" data-bind="event: { keyup: toolbar.keyup_search.bind($data, event) }, value: toolbar.search" class="form-control" placeholder="Tìm kiếm đơn hàng...">
                        <span class="input-group-btn" data-bind="click: toolbar.stat">
                            <button class="btn btn-default" type="button"><i class="fa fa-search"></i></button>
                        </span>
                    </div>
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
                <div class="btn btn-warning mt-5" data-bind="click: toolbar.stat_suspend.bind($data, '0')">
                    <i class="fa fa-search" aria-hidden="true"></i> Chưa xác nhận
                </div>
            </div>
        </div>
    </div>
    <div class="inventory-body">
        <div class="overview" data-bind="visible: toolbar.statResult()==''">
            <span class="icon"><i style="padding: 15px 0px 0px 0px;" class="fa fa-list-alt" aria-hidden="true"></i></span>
            <ul>
                <li>Xem danh sách việc làm trong ngày của bạn.</li>
                <li>Xem thông tin chi tiết các công việc của bạn có trong hệ thống.</li>
            </ul>
        </div>
        <div data-bind="visible: toolbar.statResult() != '', html: toolbar.statResult" style="display: none;"></div>
    </div>
</script>
<div class="modal" id="chat-message" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <legend>CẬP NHẬT THÔNG TIN</legend>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group" data-bind="html: toolbar.msgResult"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="modal-change-date" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <legend>Thông tin nhắc nhở</legend>
                        <div>
                            <strong>Nhắc nhở:</strong> <br/>
                            "<span class="text-red" data-bind="text: toolbar.customer_reminder().name"></span>"
                        </div>
                        <div>
                            - Người thực hiện: <strong data-bind="text: toolbar.customer_reminder().reminder_user_info ? toolbar.customer_reminder().reminder_user_info.fullname : ''">fullname</strong>
                        </div>
                        <div>
                            - Trạng thái:
                            <!-- ko if: toolbar.customer_reminder().approveed == 1 -->
                            <label class="label label-success">Đã xác nhận</label>
                            <!-- /ko -->
                            <!-- ko if: toolbar.customer_reminder().approveed == 0 -->
                            <label class="label label-default">Chưa xác nhận</label>
                            <!-- /ko -->
                        </div>
                        <div>
                            - Thời gian: <strong data-bind="text: toolbar.customer_reminder().remind_at"></strong>
                        </div>
                        <div>
                            - Người tạo: <strong class="text-purple" data-bind="text: toolbar.customer_reminder().user_created_info ? toolbar.customer_reminder().user_created_info.fullname : ''"></strong>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <legend style="margin-top: 15px;">Thông tin chuyển ngày</legend>
                        <div class="form-group">
                            <label for="modal_change_date_time" class="control-label">Thời gian chuyển đến</label>
                            <div class='input-group datetime' id='modal_change_date_time' name="modal_change_date_time">
                                <input type='text' class="form-control" />
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="modal_change_date_note" class="control-label">Ghi chú chuyển ngày <sup class="text-danger">(*)</sup></label>
                            <textarea type="text" rows="8" class="form-control" name="modal_change_date_note" id="modal_change_date_note" data-bind="value: toolbar.customer_reminder().note" placeholder="Ghi chú chuyển ngày..."></textarea>
                        </div>
                        <div class="text-right">
                            <div class="btn btn-primary" data-bind="click: toolbar.do_change_date"><i class="fa fa-save"></i> Lưu lại</div>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection