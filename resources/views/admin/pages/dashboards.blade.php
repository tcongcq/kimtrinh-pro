@extends('cms::layouts.crud')

@section('assets')
@include('admin.assets')
@endsection

@section('main')
@include('admin.blocks.assets.style')
@include('admin.blocks.assets.hide_nav_menu')
<script type="text/javascript">
let loading = false;
Cookies.set("x-user-session", "{{ $device_token }}");
var Toolbar = function(){
    var self = this;
    self.statResult = ko.observable('<tr><td colspan="4" class="text-center"><i>(Chưa có dữ liệu)</i></td></tr>');
    self.reminderResult = ko.observable('<tr><td colspan="2" class="text-center"><i>(Chưa có dữ liệu)</i></td></tr>');
    self.show_zalo  = ko.observable(false);
    self.msgResult  = ko.observable('');
    self.eventTract = ko.observable('');
    self.customer_reminder = ko.observable({});

    self.init = function(){
        setTimeout(()=>{
            self.get_unfinished_reminder();
        }, 1000);
    };

    self.search_keypress = function(event, el){
        var search = $(el).val();
        if (!event || event.keyCode != 13 || search.length == 0)
            return false;
        self.do_search(search);
    };
    self.do_search = function(search){
        $.ajax({
            url: "{{ uri() }}/search-customer",
            type: "get",
            data: {
                search: search
            },
            beforeSend: showAppLoading,
            complete: hideAppLoading,
            success: function (data) {
                self.statResult(data);
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
        if (!event || event.keyCode != 13 || message.length == 0 || loading)
            return false;
        loading = true;
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
                loading = false;
            }
        });
    };
    self.get_unfinished_reminder = function(){
        $.ajax({
            url: "{{ uri() }}/unfinished-reminder",
            type: "get",
            data: {},
            // beforeSend: showAppLoading, complete: hideAppLoading,
            success: function (data) {
                self.reminderResult(data);
            }
        });
    };
    self.show_reminder_form = function(customer_id){
        self.customer_reminder({customer_id: customer_id});
        $('#reminder_time').datetimepicker({
            viewMode: 'days',
            format: 'DD/MM/YYYY HH:mm',
            locale: 'vi'
        });
        $("#reminder_form").modal("show");

    };
    self.reminder_create = function(){
        var reminder = self.customer_reminder();
        var reminder_time = $('#reminder_time').data("DateTimePicker").date();
        reminder._token      = "{!! csrf_token() !!}";
        reminder.remind_at   = reminder_time ? reminder_time.format('YYYY-MM-DD HH:mm:ss') : '';
        if (!reminder.remind_at){
            toastr['warning']('Hãy chọn thời gian nhắc nhở!');
            return false;
        }
        if (!reminder.name){
            toastr['warning']('Hãy nhập nội dung nhắc nhở!');
            return false;
        }
        $.ajax({
            url: "{{ url(config('cms.backend_prefix').'/customer/create-reminder') }}",
            type: "post",
            beforeSend: showAppLoading, complete: hideAppLoading,
            data: reminder,
            success: function(data){
                self.customer_reminder({});
                $("#reminder_form").modal("hide");
                $('#reminder_time input').val('');
                self.get_unfinished_reminder();
            }
        });
    };
    self.confirmation_reminder = function(id, customer_id){
        var confirmation = prompt('Ghi chú xác nhận hoàn thành nhắc nhở. Chấp nhận từ 50 ký tự.');
        if (!confirmation)
            return false;
        if (confirmation.length < 50){
            alert('Chỉ chấp nhận từ 50 ký tự.')
            return false;
        }
        $.ajax({
            url: "{{ url(config('cms.backend_prefix').'/customer-reminder/confirmation-reminder') }}",
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
                    self.get_unfinished_reminder();
                notification.init();
                self.show_reminder_form(customer_id);
            }
        });
    };
}
var toolbar = new Toolbar();
toolbar.init()
</script>
<div class="container-fluid" style="padding-bottom: 30px;">
    <div class="row">
        <div class="block-header">
            <h3><i class="fa fa-area-chart"></i> Quản trị hệ thống</h3>
        </div>
        <div class="col-md-7">
            <legend>Xem nhanh khách hàng và ghi chú trao đổi thông tin</legend>
            <div class="panel panel-warning">
                <div class="panel-heading" style="color: #fff; background-color: #f0ad4e;">
                    <div class="input-group">
                        <input class="form-control" name="code" id="code" onkeyup="toolbar.search_keypress(event, this)" placeholder="Tìm nhanh khách hàng..." />
                        <span class="input-group-btn">
                            <div class="btn btn-default" data-bind="click: toolbar.do_search"><i class="fa fa-search" aria-hidden="true"></i> Tìm nhanh</div>
                        </span>
                    </div>
                </div>
                <div id="about" class="nano" style="height:522px;">
                    <div class="nano-content">  
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="text-align: center;">MSKH</th>
                                    <th>Đơn hàng</th>
                                    <th class="hidden">Thông tin</th>
                                    <th class="text-center" style="text-align: center;">#</th>
                                </tr>
                            </thead>
                            <tbody data-bind="html: toolbar.statResult"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <legend>Công việc trong ngày</legend>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    Danh sách nhắc nhở
                </div>
                  <div id="about" class="nano" style="height:536px;">
                    <div class="nano-content">  
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Thông tin</th>
                                    <th class="text-center">#</th>
                                </tr>
                            </thead>
                            <tbody data-bind="html: toolbar.reminderResult"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
<div class="modal" id="reminder_form" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <legend>Thêm nhắc nhở mới</legend>
                        <div class="form-group">
                            <label for="reminder_time" class="control-label"><small>Thời gian nhắc nhở</small></label>
                            <div class="input-group date" id="reminder_time">
                                <input type="text" class="form-control" placeholder="Chọn thời gian..." />
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="name" class="control-label">Nội dung nhắc nhở</label>
                            <textarea class="form-control" name="name" id="name" data-bind="value: toolbar.customer_reminder().name" rows="7" placeholder="Nội dung nhắc nhở" ></textarea>
                        </div>
                        <div class="text-right">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                            <div data-bind="click: toolbar.reminder_create" class="btn btn-primary">Tạo mới</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection