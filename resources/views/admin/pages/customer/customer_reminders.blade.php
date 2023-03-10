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
                // Chi???u cao cho table th??ng tin
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
        var confirmation = prompt('Ghi ch?? x??c nh???n ho??n th??nh nh???c nh???. Ch???p nh???n t??? 50 k?? t???.');
        if (!confirmation)
            return false;
        if (confirmation.length < 50){
            alert('Ch??? ch???p nh???n t??? 50 k?? t???.')
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
            toastr['warning']("Ch???n th???i gian chuy???n nh???c nh??? v?? nh???p ghi ch??!");
            return false;
        }
        if (!confirm('X??c nh???n chuy???n ng??y nh???c nh??? ?????n th???i gian m???i '+self.date_format(datetime)))
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
                    <label for="from_date" class="control-label"><small>T??? ng??y</small></label>
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
                    <label for="to_date" class="control-label"><small>?????n ng??y</small></label>
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
                    <label for="approveeds" class="control-label"><small>X??c nh???n</small></label>
                    <select class="selectpicker form-control" id="approveeds" data-live-search="true" multiple>
                        <option value="1">???? x??c nh???n</option>
                        <option value="0">Ch??a x??c nh???n</option>
                    </select>
                </div>
            </div>
            <div class="col-xs-6 col-sm-4 col-md-2">
                <div class="form-group">
                    <label for="states" class="control-label"><small>Tr???ng th??i</small></label>
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
                    <label for="reminder_user_ids" class="control-label"><small>Nh??n vi??n Sale</small></label>
                    <select class="selectpicker form-control" id="reminder_user_ids" data-live-search="true" multiple data-selected-text-format="count > 3" data-actions-box="true">
                    @foreach(\App\Admin\Customer\Customer::get_users() as $sale)
                        <option value="{{ $sale->id }}">{{ $sale->fullname }} {{ $sale->phone ? " - ($sale->phone)" : '' }}</option>
                    @endforeach
                    </select>
                </div>
            </div>
            <div class="col-xs-6 col-sm-4 col-md-2">
                <div class="form-group mb-0">
                    <label for="time_access" class="control-label"><small>T??m ????n h??ng</small></label>
                    <div class="input-group">
                        <input type="text" id="search" data-bind="event: { keyup: toolbar.keyup_search.bind($data, event) }, value: toolbar.search" class="form-control" placeholder="T??m ki???m ????n h??ng...">
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
                    <i class="fa fa-search" aria-hidden="true"></i> H??m nay
                </div>
                <div class="btn btn-info mt-5" data-bind="click: toolbar.stat_in_week">
                    <i class="fa fa-search" aria-hidden="true"></i> Tu???n n??y
                </div>
                <div class="btn btn-info mt-5" data-bind="click: toolbar.stat_in_month">
                    <i class="fa fa-search" aria-hidden="true"></i> Th??ng n??y
                </div>
                <div class="btn btn-warning mt-5" data-bind="click: toolbar.stat_suspend.bind($data, '0')">
                    <i class="fa fa-search" aria-hidden="true"></i> Ch??a x??c nh???n
                </div>
            </div>
        </div>
    </div>
    <div class="inventory-body">
        <div class="overview" data-bind="visible: toolbar.statResult()==''">
            <span class="icon"><i style="padding: 15px 0px 0px 0px;" class="fa fa-list-alt" aria-hidden="true"></i></span>
            <ul>
                <li>Xem danh s??ch vi???c l??m trong ng??y c???a b???n.</li>
                <li>Xem th??ng tin chi ti???t c??c c??ng vi???c c???a b???n c?? trong h??? th???ng.</li>
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
                        <legend>C???P NH???T TH??NG TIN</legend>
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
                        <legend>Th??ng tin nh???c nh???</legend>
                        <div>
                            <strong>Nh???c nh???:</strong> <br/>
                            "<span class="text-red" data-bind="text: toolbar.customer_reminder().name"></span>"
                        </div>
                        <div>
                            - Ng?????i th???c hi???n: <strong data-bind="text: toolbar.customer_reminder().reminder_user_info ? toolbar.customer_reminder().reminder_user_info.fullname : ''">fullname</strong>
                        </div>
                        <div>
                            - Tr???ng th??i:
                            <!-- ko if: toolbar.customer_reminder().approveed == 1 -->
                            <label class="label label-success">???? x??c nh???n</label>
                            <!-- /ko -->
                            <!-- ko if: toolbar.customer_reminder().approveed == 0 -->
                            <label class="label label-default">Ch??a x??c nh???n</label>
                            <!-- /ko -->
                        </div>
                        <div>
                            - Th???i gian: <strong data-bind="text: toolbar.customer_reminder().remind_at"></strong>
                        </div>
                        <div>
                            - Ng?????i t???o: <strong class="text-purple" data-bind="text: toolbar.customer_reminder().user_created_info ? toolbar.customer_reminder().user_created_info.fullname : ''"></strong>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <legend style="margin-top: 15px;">Th??ng tin chuy???n ng??y</legend>
                        <div class="form-group">
                            <label for="modal_change_date_time" class="control-label">Th???i gian chuy???n ?????n</label>
                            <div class='input-group datetime' id='modal_change_date_time' name="modal_change_date_time">
                                <input type='text' class="form-control" />
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="modal_change_date_note" class="control-label">Ghi ch?? chuy???n ng??y <sup class="text-danger">(*)</sup></label>
                            <textarea type="text" rows="8" class="form-control" name="modal_change_date_note" id="modal_change_date_note" data-bind="value: toolbar.customer_reminder().note" placeholder="Ghi ch?? chuy???n ng??y..."></textarea>
                        </div>
                        <div class="text-right">
                            <div class="btn btn-primary" data-bind="click: toolbar.do_change_date"><i class="fa fa-save"></i> L??u l???i</div>
                            <button type="button" class="btn btn-default" data-dismiss="modal">????ng</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection