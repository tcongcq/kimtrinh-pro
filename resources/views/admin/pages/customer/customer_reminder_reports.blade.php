@extends('cms::layouts.crud')

@section('assets')
@include('admin.assets')
<style>html, body{ background-color: #f9f9f9; }</style>
<link href='{{ url("assets/lib/fullcalendar/main.css") }}' rel='stylesheet' />
<script src='{{ url("assets/lib/fullcalendar/main.js") }}'></script>
@endsection

@section('main')
@include('admin.blocks.assets.style')
@include('admin.blocks.assets.hide_nav_menu')
<style type="text/css">
.fc-direction-ltr .fc-daygrid-event .fc-event-time{display: none;}
.btn-add-bottom-right{ position: fixed; bottom: 30px; right: 20px; z-index: 999; }
.btn-add-bottom-right .dropdown-toggle{ box-shadow: none !important; }
.btn-add-bottom-right .css_dropdown_add_btn{ right: 35px; bottom: -14px; border-radius: 8px; }

</style>
<script type="text/javascript">
const now = new Date();
let ref_now = now;
var calendar = null;
function Toolbar(){
    var self = this;
    self.events = ko.observableArray([]);
    self.customer_reminder = ko.observable({});
    self.init  	= function(){
        $('.date').datetimepicker({format: 'DD/MM/YYYY',locale: 'vi'});
    	$('.datetime').datetimepicker({format: 'HH:mm DD/MM/YYYY',locale: 'vi'});
    	var from_date = new Date(now.getFullYear(), now.getMonth());
        var to_date = new Date(now.getFullYear(), now.getMonth()+1, 0);
        $('#from_date').data('DateTimePicker').defaultDate(from_date);
        $('#to_date').data('DateTimePicker').defaultDate(to_date);
        $('.selectpicker').selectpicker();
    	self.show_report()
    	self.stat();
    };
    self.datetime_format = function(_date){
        return moment(_date).format('YYYY-MM-DD HH:mm:ss');
    };
    self.date_format = function(_date){
        return moment(_date).format('HH:mm DD/MM/YYYY');
    };
    self.get_event_approve_icon = function(e){
        var approved_icon = '<i class="fa fa-check" style="font-size: 15px; padding: 2px; color: #7df128"></i>';
        return e.approveed ? approved_icon : '';
    };
    self.get_event_color = function(data){
        let state = data.customer_id == 1 ? data.type : data.state;
    	var matchColor = {success: '#5cb85c',warning: '#f0ad4e',danger: '#d9534f',primary: '#337ab7',default: '#777',info: '#5bc0de'};
    	switch (state){
            case 'POTENTIAL':
                return matchColor.success;
            case 'COOPERATED':
                return matchColor.danger;
            case 'APPROACHING':
                return matchColor.primary;
            case 'CANCELLED':
                return matchColor.default;
            // case 'NET':
            //     return matchColor.warning;
            default:
                return matchColor.info;
        }
    };
    self.leading_zero = (num, places) => String(num).padStart(places, '0');
    self.prepare_calendar_event = function(sourceEvents, callback){
    	var events = [];
    	sourceEvents.forEach(function(row, idx){
    		let event = Object.assign(row, {
    			id: row.id,
				title: row.name,
				start: row.remind_at,
				backgroundColor: self.get_event_color(row),
				borderColor: self.get_event_color(row),
		    });
		    events.push(event);
    	});
    	self.events(events);
    	callback(self.events());
    };
    self.stat = function(){
    	var from_date = !$('#from_date input').val() ? '':$('#from_date').data("DateTimePicker").date().format('YYYY-MM-DD');
    	var to_date = !$('#to_date input').val() ? '':$('#to_date').data("DateTimePicker").date().format('YYYY-MM-DD');
        $.ajax({
            url: "{{ uri() }}/stat",
            type: "post",
            data: {
            	_token: "{{ csrf_token() }}",
                from_date: self.datetime_format(from_date),
                to_date: self.datetime_format(to_date),
                reminder_user_ids: $('#reminder_user_ids').selectpicker('val')
            },
            beforeSend: showAppLoading, complete: hideAppLoading,
            success: function (res) {
            	self.prepare_calendar_event(res.data, (e)=>{
            		calendar.setOption("events", e);
            	});
            }
        });
    };
    self.prev_stat = function(e){
        var from_date = new Date(ref_now.getFullYear(), ref_now.getMonth()-1);
        var to_date = new Date(ref_now.getFullYear(), ref_now.getMonth(), 0);
        $('#from_date').data('DateTimePicker').date(from_date);
        $('#to_date').data('DateTimePicker').date(to_date);
        calendar.prev();
        ref_now = from_date;
        self.stat()
    }
    self.next_stat = function(e){
        var from_date = new Date(ref_now.getFullYear(), ref_now.getMonth()+1);
        var to_date = new Date(ref_now.getFullYear(), ref_now.getMonth()+2, 0);
        $('#from_date').data('DateTimePicker').date(from_date);
        $('#to_date').data('DateTimePicker').date(to_date);
        calendar.next();
        ref_now = from_date;
        self.stat()
    }
    self.show_report = function(){
    	var calendarEl = document.getElementById('calendar');
        calendar = new FullCalendar.Calendar(calendarEl, {
        	droppable: false,
        	headerToolbar: {
		        left: 'prev,next today',
		        center: 'title',
		        right: ''
			},
			navLinks: true,
			nowIndicator: true,

            locale: 'vi',

			weekNumbers: true,
			weekNumberCalculation: 'ISO',

			selectable: true,
			dayMaxEvents: true,
			editable: false,
			eventDisplay: 'block',

			events: [],
            eventDidMount: function(info) {
                var title = $(info.el).find('.fc-event-main-frame');
                title.prepend(self.get_event_approve_icon(info.event.extendedProps));
            },
			eventClick: self.eventClick,
            customButtons: {
                prev: {
                    text: 'Prev',
                    click: self.prev_stat
                },
                next: {
                    text: 'Next',
                    click: self.next_stat
                },
            }
        });
        calendar.render();
    };
    self.eventClick = function(e){
        var {event} = e;
        var reminder = Object.assign(JSON.parse(JSON.stringify(event.extendedProps)), {id: parseInt(event.id)});
        self.customer_reminder(reminder);
        $("#modal-customer-reminder").modal("show");
    };

    self.keyup_search = function(e){
    	if (e.key == 'Enter' && $('#search').val())
    		self.stat();
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
            url: "{{ url(config('cms.backend_prefix').'/'.uri().'/accept-reminder') }}",
            type: "post",
            beforeSend: showAppLoading, complete: hideAppLoading,
            data: {
                _token: "{!! csrf_token() !!}",
                id: id,
                message: confirmation,
                reminder_type: (self.customer_reminder().customer_id == 1 ? 'CUSTOM' : '')
            },
            success: function(data){
                toastr[data.status](data.message);
                if (data.status == 'success'){
                    $("#modal-customer-reminder").modal("hide");
                    self.stat();
                }
            }
        });
    };
    self.get_customer_state = function(state){
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
    };

    self.current = ko.observable({});
    self.add = function(){
        $('#reminder_user_id').selectpicker('val', '');
        $('#reminder-form').modal('show');
        self.current({active: 1})
    };
    self.save =function (){
        self.current().type = $('#type').selectpicker('val');
        self.current().remind_at = !$('#remind_at input').val() ? '':$('#remind_at').data("DateTimePicker").date().format('YYYY-MM-DD HH:mm:ss');
        let reminder = self.current();
        if (!reminder.name || !reminder.remind_at){
            toastr['warning']('Nhắc nhở và thời gian là bắt buộc!');
            return false;
        }
        let reminder_user_id = $('#reminder_user_id').selectpicker('val');
        if (reminder_user_id)
            reminder.reminder_user_id = reminder_user_id;
        reminder.public = $('#public').selectpicker('val');
        reminder._token = "{!! csrf_token() !!}";
        $.ajax({
            url: "{{ url(config('cms.backend_prefix').'/'.uri().'/create-reminder') }}",
            type: "post",
            beforeSend: showAppLoading, complete: hideAppLoading,
            data: reminder,
            success: function(data){
                toastr[data.status](data.message);
                if (data.status == 'success'){
                    $("#reminder-form").modal("hide");
                    self.stat();
                }
            }
        });
    };
    self.remove = function(id){
        var accept = confirm('Xác nhận xóa nhắc nhở. Dữ liệu sẽ không thể phục hồi!');
        if (!accept) return false;
        $.ajax({
            url: "{{ url(config('cms.backend_prefix').'/'.uri().'/delete-reminder') }}",
            type: "post",
            beforeSend: showAppLoading, complete: hideAppLoading,
            data: {
                _token: "{!! csrf_token() !!}",
                id: id,
                reminder_type: (self.customer_reminder().customer_id == 1 ? 'CUSTOM' : '')
            },
            success: function(data){
                toastr[data.status](data.message);
                $("#modal-customer-reminder").modal("hide");
                self.stat();
            }
        });
    };
}
var toolbar = new Toolbar();
document.addEventListener('DOMContentLoaded', ()=>toolbar.init());
</script>
<div class="inventory-toolbar">
    <div class="row">
        <div class="col-xs-4 col-sm-4 col-md-3">
            <div class="form-group">
                <label for="from_date" class="control-label"><small>Từ ngày</small></label>
                <div class="input-group date" id="from_date">
                    <input type="text" class="form-control" />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-xs-4 col-sm-4 col-md-3">
            <div class="form-group">
                <label for="to_date" class="control-label"><small>Đến ngày</small></label>
                <div class="input-group date" id="to_date">
                    <input type="text" class="form-control" />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
        </div>
        
        <div class="col-xs-4 col-sm-4 col-md-3 
            @if(!\Account::has_permission(['route'=>'customer-reminder','permission_name'=>'access-all']))
            hidden
            @endif
        ">
            <div class="form-group">
                <label for="reminder_user_ids" class="control-label"><small>Sale PIC</small></label>
                <select class="selectpicker show-tick form-control" name="reminder_user_ids" id="reminder_user_ids" data-live-search="true" multiple data-selected-text-format="count > 3" data-actions-box="true">
                @foreach(\App\Admin\Customer\Customer::get_users() as $k => $user)
                    <option value="{{ $user->id }}">{{ $user->fullname }} ({{ $user->phone }})</option>
                @endforeach
                </select>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3">
            <div class="form-group">
                <label for="time_access" class="control-label"><small>Tìm công việc theo đơn hàng</small></label>
                <div class="input-group">
                    <input type="text" id="search" data-bind="event: { keyup: toolbar.keyup_search.bind($data, event) }, value: toolbar.search" class="form-control" placeholder="Nhập mã khách hàng...">
                    <span class="input-group-btn" data-bind="click: toolbar.stat">
                        <button class="btn btn-info" type="button"><i class="fa fa-search"></i> Xem</button>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="inventory-body">
    <div class="col-md-12 pt-10">
		<div id="calendar" class="p-20" style="background-color: #fff;"></div>
    </div>
</div>
<div class="btn btn-success btn-circle btn-lg btn-add-bottom-right ml-5" data-bind="click: toolbar.add">
    <i class="fa fa-plus"></i>
</div>
<div class="modal" id="reminder-form" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <legend>Thông tin nhắc nhở</legend>
                <div class="form-group">
                    <label for="name" class="control-label">Nhắc nhở <sup class="text-danger">(*)</sup></label>
                    <input type="text" class="form-control" name="name" id="name" data-bind="value: toolbar.current().name" placeholder="Nhắc nhở..." required>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="remind_at" class="control-label"><small>Thời gian</small></label>
                            <div class="input-group datetime" id="remind_at">
                                <input type="text" class="form-control" />
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="type" class="control-label">Màu hiển thị</label>
                            <select class="form-control selectpicker" name="type" id="type">
                                @foreach(config('cms.customer_state') as $idx => $state)
                                    <option value="{{ $state }}" data-content="{!! state_to_label($state) !!}" >{{ $idx+1 }} {!! state_to_label($state) !!}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="reminder_user_id" class="control-label">Người nhận</label>
                            <select class="form-control selectpicker" name="reminder_user_id" id="reminder_user_id">
                                @foreach(\App\Admin\Customer\Customer::get_users() as $k => $user)
                                    <option value="{{ $user->id }}">{{ $user->fullname }} ({{ $user->phone }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="public" class="control-label">Loại nhắc nhở</label>
                            <select class="form-control selectpicker" name="public" id="public">
                                <option value="0">Riêng tư</option>
                                <option value="1">Công khai</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="note" class="control-label">Ghi chú nội dung</label>
                    <textarea type="text" rows="15" class="form-control" name="note" id="note" data-bind="value: toolbar.current().note" placeholder="Ghi chú nội dung..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <div class="btn btn-primary" data-bind="click: toolbar.save"><i class="fa fa-save"></i> Lưu lại</div>
                <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="modal-customer-reminder" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <!-- ko if: toolbar.customer_reminder().customer_id != 1 -->
                    <div class="col-md-12">
                        <legend>Thông tin đơn hàng</legend>
                        <div>
                            <a target="_blank" class="badge badge-warning" data-bind="attr: {'href': '{{ url(config('cms.backend_prefix').'/customer?id=') }}'+toolbar.customer_reminder().customer_id}, text: toolbar.customer_reminder().code"></a> || <span data-bind="html: toolbar.get_customer_state(toolbar.customer_reminder().state)"></span>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                - Công ty: <strong data-bind="text: toolbar.customer_reminder().company_name">company_name</strong> <br/>
                                - Thương hiệu: <strong data-bind="text: toolbar.customer_reminder().brand_name">brand_name</strong> <br/>
                                - Ngành hàng: <strong data-bind="text: toolbar.customer_reminder().field">field</strong>
                            </div>
                            <div class="col-md-6">
                                - Tên khách hàng: <strong data-bind="text: toolbar.customer_reminder().customer_name">customer_name</strong> <br/>
                                - SĐT: <strong data-bind="text: toolbar.customer_reminder().phone">phone</strong>  <br/>
                                - Email: <strong data-bind="text: toolbar.customer_reminder().email">email</strong>
                            </div>
                        </div>
                        <hr />
                    </div>
                    <!-- /ko -->
                    <div class="col-md-12">
                        <legend>Thông tin nhắc nhở</legend>
                        <div>
                            <strong>Nhắc nhở:</strong> <br/>
                            "<span class="text-red" data-bind="text: toolbar.customer_reminder().name"></span>"
                            <label data-bind="if: toolbar.customer_reminder().public" class="label label-primary pull-right">Nhắc nhở công khai</label>
                            <label data-bind="if: !toolbar.customer_reminder().public" class="label label-default pull-right">Nhắc nhở riêng tư</label>
                        </div>
                        <div>
                            - Người nhận: <strong data-bind="text: toolbar.customer_reminder().ru_fullname">fullname</strong>
                            <span data-bind="if: toolbar.customer_reminder().ru_fullname != toolbar.customer_reminder().uc_fullname">
                                (tạo bởi: <span data-bind="text: toolbar.customer_reminder().uc_fullname">fullname</span>)
                            </span>
                        </div>
                        <div>
                            - Trạng thái xác nhận:
                            <!-- ko if: toolbar.customer_reminder().approveed == 1 -->
                            <label class="label label-success">Đã xác nhận</label>
                            <!-- /ko -->
                            <!-- ko if: toolbar.customer_reminder().approveed == 0 -->
                            <label class="label label-default">Chưa xác nhận</label>
                            <!-- /ko -->
                        </div>
                        <div>
                            - Thời gian: <strong data-bind="text: toolbar.date_format(toolbar.customer_reminder().remind_at)"></strong>
                        </div>
                        <div data-bind="if: toolbar.customer_reminder().approveed">
                            - Thông tin xác nhận: <strong class="text-success" data-bind="text: toolbar.customer_reminder().approved_note"></strong>
                        </div>
                        <div data-bind="if: toolbar.customer_reminder().approveed">
                            - Xác nhận lúc: <strong data-bind="text: toolbar.date_format(toolbar.customer_reminder().approved_time)"></strong>
                        </div>
                        <div data-bind="if: toolbar.customer_reminder().note">
                            - Ghi chú nội dung: <strong class="text-primary" data-bind="text: toolbar.customer_reminder().note"></strong>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                @if(\Account::has_permission(['route'=>'customer-reminder-report','permission_name'=>'delete']))
                <div class="btn btn-danger" data-bind="click: toolbar.remove.bind($data, toolbar.customer_reminder().id)"><i class="fa fa-trash"></i> Xóa</div>
                @else
                <!-- ko if: toolbar.customer_reminder().customer_id == 1 -->
                <div class="btn btn-danger" data-bind="click: toolbar.remove.bind($data, toolbar.customer_reminder().id)"><i class="fa fa-trash"></i> Xóa</div>
                <!-- /ko -->
                @endif
                <!-- ko if: toolbar.customer_reminder().approveed != 1 -->
                <div class="btn btn-success" data-bind="click: toolbar.confirmation_reminder.bind($data, toolbar.customer_reminder().id)"><i class="fa fa-check"></i> Hoàn thành</div>
                <!-- /ko -->
                <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>
@endsection