@extends('cms::layouts.crud')

@section('assets')
@include('admin.assets')
@endsection

@section('main')
@include('admin.blocks.assets.style')
@include('admin.blocks.assets.hide_nav_menu')
<script type="text/javascript">
let loading = false;
function ToolBar() {
    var self = this;
    self.view = ko.observable('grid');
    self.grid_data = null;
    self.form_data = null;
    self.state  = ko.observable("");
    self.customer_reminder = ko.observable({});
    self.customer_messages = ko.observableArray([]);
    self.customer_reminders = ko.observableArray([]);
    self.customer_attachment  = ko.observable({});
    self.customer_attachments = ko.observableArray([]);
    self.attachment_upload_file = ko.observable('');
    self.customer_product = ko.observable({});
    self.customer_products = ko.observableArray([]);
    
    self.view.subscribe(function () {
        self.get_current_additional();
    });

    self.grid_init = function (grid) {
        self.grid_data = grid;
    };
    self.form_init = function (form) {
        self.form_data = form;
        $('.only-date').datetimepicker({
            viewMode: 'days',
            format: 'DD/MM/YYYY',
            locale: 'vi'
        });
        $('.date').datetimepicker({
            viewMode: 'days',
            format: 'DD/MM/YYYY HH:mm',
            locale: 'vi'
        });
        $('.selectpicker').selectpicker();
        $( "#state" ).change(self.trigger_change_state);
    };
    self.grid = function (attr, param) {
        return self.grid_data[attr](param);
    };
    self.active_tab = function(tab){
        $('.nav-tabs a[href="#'+tab+'"]').tab('show')
    };

    self.add = function (e) {
        self.form_data.method('add');
        self.form_data.current({
            active: 1,
            product_price: 0,
            guarantees: 0,
            first_payment: 0,
            second_payment: 0,
            listed_price: 0,
            final_price: 0
        });
        $('#state').selectpicker('val', 'APPROACHING');
        self.view('form');
        self.active_tab('profile');
    };

    self.edit = function (e) {
        self.form_data.method('update');
        self.form_data.current(e);
        $('#priority').selectpicker('val', e.priority);
        $('#state').selectpicker('val', e.state);
        self.view('form');
        self.active_tab('home');
        $('#branch').selectpicker('val', e.branch);
        $('#field').selectpicker('val', e.field);
        $('#source').selectpicker('val', e.source);
        $('#contract_state').selectpicker('val', e.contract_state);
        $('#payment_state').selectpicker('val', e.payment_state);
    };

    self.trigger_change_state = function(e){
        let state = $(e.target).val();
        self.state(state);
    };
    self.message_create = function(event){
        var message = $('#inp-text-message').val();
        if (!event || event.keyCode != 13 || message.length == 0 || loading)
            return false;
        loading = true;
        $.ajax({
            url: "{{ url(config('cms.backend_prefix').'/'.uri().'/create-message') }}",
            type: "post",
            // beforeSend: showAppLoading, complete: hideAppLoading,
            data: {
                _token: "{!! csrf_token() !!}",
                content: message,
                customer_id: self.form_data.current().id
            },
            success: function(data){
                self.get_current_additional(['message']);
                $('#inp-text-message').val('');
                loading = false;
            }
        });
    };
    self.reminder_create = function(){
        var reminder = self.customer_reminder();
        var reminder_time = $('#reminder_time').data("DateTimePicker").date();
        reminder._token      = "{!! csrf_token() !!}";
        reminder.remind_at   = reminder_time ? reminder_time.format('YYYY-MM-DD HH:mm:ss') : '';
        reminder.customer_id = self.form_data.current().id;
        reminder.reminder_user_id = self.form_data.current().user_assigned_id;
        if (!reminder.remind_at){
            toastr['warning']('Hãy chọn thời gian nhắc nhở!');
            return false;
        }
        if (!reminder.name){
            toastr['warning']('Hãy nhập nội dung nhắc nhở!');
            return false;
        }
        $.ajax({
            url: "{{ url(config('cms.backend_prefix').'/'.uri().'/create-reminder') }}",
            type: "post",
            beforeSend: showAppLoading, complete: hideAppLoading,
            data: reminder,
            success: function(data){
                self.get_current_additional(['reminder']);
                self.customer_reminder({});
                $('#reminder_time input').val('');
            }
        });
    };
    self.reminder_approve = function(reminder){
        var accept = prompt('Ghi chú xác nhận hoàn thành nhắc nhở. Chấp nhận từ 50 ký tự.');
        if (!accept)
            return false;
        if (accept.length < 50){
            alert('Chỉ chấp nhận từ 50 ký tự.')
            return false;
        }
        $.ajax({
            url: "{{ url(config('cms.backend_prefix').'/'.uri().'/accept-reminder') }}",
            type: "post",
            beforeSend: showAppLoading, complete: hideAppLoading,
            data: {
                _token: "{!! csrf_token() !!}",
                id: reminder.id,
                approved_note: accept,
                customer_id: reminder.customer_id
            },
            success: function(data){
                toastr[data.status](data.message);
                self.get_current_additional(['reminder']);
            }
        });
    };
    self.reminder_edit = function(reminder){
        self.customer_reminder(reminder);
        $('#reminder_time').data("DateTimePicker").date(new Date(reminder.remind_at));
    };
    self.reminder_delete = function(reminder){
        var accept = confirm('Xác nhận xóa nhắc nhở. Dữ liệu sẽ không thể phục hồi!');
        if (!accept) return false;
        $.ajax({
            url: "{{ url(config('cms.backend_prefix').'/'.uri().'/delete-reminder') }}",
            type: "post",
            // beforeSend: showAppLoading, complete: hideAppLoading,
            data: {
                _token: "{!! csrf_token() !!}",
                id: reminder.id
            },
            success: function(data){
                toastr[data.status](data.message);
                self.get_current_additional(['reminder']);
            }
        });
    };
    self.is_image = function(e){
        if (e.file_type && e.file_type.includes('image'))
            return true;
        return false;
    };
    self.pick_attachment_file = function(e){
        var storage_file = $(e.target).val();
        self.attachment_upload_file(storage_file);
    };
    self.create_attachment = function(){
        self.customer_attachment({
            _token: "{!! csrf_token() !!}",
            customer_id: self.form_data.current().id,
            attachment_upload_file: self.attachment_upload_file()
        });
        $.ajax({
            url: "{{ url(config('cms.backend_prefix').'/'.uri().'/create-attachment') }}",
            type: "post",
            beforeSend: showAppLoading, complete: hideAppLoading,
            data: self.customer_attachment(),
            success: function(data){
                toastr[data.status](data.message);
                if (data.status != 'success')
                    return false;
                self.get_current_additional(['attachment']);
                $("#attachment_upload_file").val("");
                self.attachment_upload_file("");
            }
        });
    };
    self.attachment_preview = function(e){
        if (['ppt','pptx','doc','docx','xls','xlsx'].includes(e.file_extension)){
            let att_url = ['https://view.officeapps.live.com/op/embed.aspx?src=','{{url("/")}}/',e.src].join('');
            window.open(att_url, "_blank");
        } else {
            window.open('{{url("/")}}/'+e.src, "_blank");
        }
    };
    self.attachment_remove = function(e){
        var accept = confirm('Xác nhận xóa File lưu trữ. Dữ liệu sẽ không thể phục hồi!');
        if (!accept) return false;
        $.ajax({
            url: "{{ url(config('cms.backend_prefix').'/'.uri().'/delete-attachment') }}",
            type: "post",
            beforeSend: showAppLoading, complete: hideAppLoading,
            data: {
                _token: "{!! csrf_token() !!}",
                id: e.id
            },
            success: function(data){
                toastr[data.status](data.message);
                self.get_current_additional(['attachment']);
            }
        });
    };
    self.attachment_ref = function(e){
        window.open("{{ url(config('cms.backend_prefix').'/attachment?id=') }}"+e.id);
    };

    self.get_current_additional = function(adds = ['message','reminder','attachment', 'product']){
        if (self.form_data.current().id == undefined)
            return false;
        $.ajax({
            url: "{{ url(config('cms.backend_prefix').'/'.uri().'/current-additional') }}",
            type: "get",
            // beforeSend: showAppLoading, complete: hideAppLoading,
            data: {
                id: self.form_data.current().id,
                params: adds
            },
            success : function(data){
                if (adds.includes('message')){
                    self.customer_messages([]);
                    self.customer_messages(data.customer_messages);
                }
                if (adds.includes('reminder')){
                    self.customer_reminders([]);
                    self.customer_reminders(data.customer_reminders);
                }
                if (adds.includes('attachment')){
                    self.customer_attachments([]);
                    self.customer_attachments(data.customer_attachments);
                }
                if (adds.includes('product')){
                    self.customer_products([]);
                    self.customer_products(data.customer_products);
                }
                // $('.list').each(function(idx, list){
                //     $(list).animate({ scrollTop: $(list).prop("scrollHeight")}, 1000);
                // })
                
            }
        });
    };
    self.date_format = function(_date){
        return moment(_date).format('HH:mm DD/MM/YYYY');
    };
    self.format_number = function(_num){
        return parseInt(_num).toLocaleString();
    };

    self.grid_filters = function(){
        var filters = [];
        $('[filter-colname]').each(function(){
            var value = null;
            if ($(this).hasClass('selectpicker'))
                value = $(this).selectpicker('val');
            if ($(this).hasClass('filter_date')){
                value = $(this).parent().data("DateTimePicker").date();
                if (!value)
                    return;
                value = value.format('YYYY-MM-DD');
                if ($(this).attr('filter-operator') == '>=')
                    value += ' 00:00:00';
                else
                    value += ' 23:59:59';
            }
            if(value !== null){                    
                var filter = {
                    key: $(this).attr('filter-colname'),
                    value: $(this).attr('filter-operator') == 'like' ? '%'+value+'%' : value
                };
                if ($(this).attr('filter-operator') != 'in')
                    filter.operator = $(this).attr('filter-operator') ? $(this).attr('filter-operator') : '='
                filters.push(filter);
            }
        });
        self.grid_data.filters(filters);
        $('.grid-filter-container:not(.print)').toggleClass('open');
    };
    self.grid_unfilters = function(){
        self.grid_data.filters([]);
        $('select[filter-colname]').selectpicker('val', '');
    };

    self.prepare_save = function(){
        self.form_data.current().state      = $('#state').selectpicker('val');
        self.form_data.current().priority   = $('#priority').selectpicker('val');
        self.form_data.current().active     = 1;

        delete self.form_data.current().user_created_info;
        delete self.form_data.current().user_assigned_info;
    };

    // self.saved = function () {
    //     self.grid_data.fetch();
    // };
    self.saved = function (data) {
        self.call_update_after_add(data);
        self.grid_data.fetch();
    };
    self.call_update_after_add = function(row){
        if (!(row && row.id))
            return false;
        $.ajax({
            url: "{{ url(config('cms.backend_prefix').'/'.uri().'/current-row') }}",
            type: "get",
            beforeSend: showAppLoading, complete: hideAppLoading,
            data: {
                id: row.id
            },
            success : function(data){
                self.edit(data);
            }
        });
    };

    self.indirect_edit = function(id){
        let row = self.grid_data.rows().filter(r=>r.id == id).shift();
        self.edit(row);
    };

    self.cellsrenderer = {
        state: function(data, idx){
            var text = null;
            var id = '<span class="badge badge-warning c_pointer" onclick="toolbar.indirect_edit('+data.id+')">#'+data.id+'</span>';
            switch (data.state){
                case 'CONTACT':
                    text = '<span class="label label-success">CONTACT</span>';
                    break;
                case 'LEAD':
                    text = '<span class="label label-warning">LEAD</span>';
                    break;
                case 'CLIENT':
                    text = '<span class="label label-danger">CLIENT</span>';
                    break;
                default:
                    text = '<span class="label label-info">DRAFT</span>';
                    break;
            }
            if (!data.active)
                text += '<br/><span class="label label-default">Không khả dụng</span>';
            return '<center>'+id+'<br/>'+text+'</center>';
        },
        name: function(data){
            var text = data.display_name ? 'Tên gợi nhớ: '+data.display_name+'<br/>' : '';
            text += '- Tên khách hàng: <strong>'+data.name+'</strong>';
            if (data.phone)
                text += '<br/>- SĐT: <strong>'+data.phone+'</strong>';
            if (data.email)
                text += '<br/>- Email: <strong>'+data.email+'</strong>';
            if (data.user_assigned_info && data.user_assigned_info.id > 1)
                text += '<br/>- Sale PIC: <strong>'+data.user_assigned_info.fullname+'</strong>';
            return text;
        },
        field: function(data){
            var text = data.company_name ? 'Công ty: <strong>'+data.company_name+'</strong><br/>' : '';
            if (data.brand_name){
                let priority = data.priority == 'NET' ? ' <i class="fa fa-star text-yellow" aria-hidden="true"></i> ' : '';
                text += '- Thương hiệu: <strong>'+priority+data.brand_name+'</strong>';
            }
            if (data.field)
                text += '<br/>- Ngành hàng: <strong>'+data.field+'</strong>';
            if (data.state_changed_at)
                text += '<br/>- Thay đổi lần cuối: <i>'+self.date_format(data.state_changed_at)+'</i>';
            return text;
        }
    };
    self.zalo_web_link = function(phone){
        return "{{config('cms.zalo_web_link')}}?phone="+phone;
    };
    self.copyToClipboard = (e)=>{
        navigator.clipboard.writeText(e).then(() => toastr['success']('Copied to the Clipboard!'));
    };
    self.importBox = function(){
        if ( !$('#import-form').valid() ){
            return false;
        }
        var file_data = $("#import-file")[0].files[0];
        var formData = new FormData();
        formData.append("file", file_data);
        formData.append("_token", '{{ csrf_token() }}');
        $.ajax({
            url: "{{ url(config('cms.backend_prefix').'/'.uri().'/import') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: showAppLoading, complete: hideAppLoading,
            success: function(data){
                // console.log(data);
                var str = '';
                if (data.status == 'errors') {
                    str += '<br>Tại sheet "'+data.sheet+'" có lỗi ở dòng thứ ';
                    $.each(data.info, function(key, val){
                        str += key+' ';
                    });
                    toastr['error'](data.msg+str);
                } else
                    toastr[data.status](data.msg);
                $('#modalImport').modal('hide');
                self.grid_data.fetch();
            }
        });
        return false;
    };

    self.show_customer_products = function(){
        $('#modal-product').modal("show");
    };
    self.add_customer_product = function(code){
        var accept = confirm('Xác nhận thêm dịch vụ vào đơn hàng!');
        if (!accept) return false;
        $.ajax({
            url: "{{ url(config('cms.backend_prefix').'/'.uri().'/create-customer-product') }}",
            type: "post",
            beforeSend: showAppLoading, complete: hideAppLoading,
            data: {
                _token: "{!! csrf_token() !!}",
                code: code,
                customer_id: self.form_data.current().id
            },
            success: function(data){
                self.get_current_additional(['product']);
                toastr[data.status](data.message);
            }
        });
    };
    self.fetch_customer_product = function(){
        self.get_current_additional(['product']);
    };
    self.show_customer_product = function(e){
        self.customer_product(e);
        $('#modal-customer-product').modal("show");
    };
    self.update_customer_product = function(){
        var updateData = self.customer_product();
        updateData['_token'] = "{!! csrf_token() !!}";
        $.ajax({
            url: "{{ url(config('cms.backend_prefix').'/'.uri().'/update-customer-product') }}",
            type: "post",
            beforeSend: showAppLoading, complete: hideAppLoading,
            data: updateData,
            success: function(data){
                self.get_current_additional(['product']);
                toastr[data.status](data.message);
            }
        });
    };
    self.delete_customer_product = function(row){
        var accept = confirm('Xác nhận xóa dịch vụ. Dữ liệu sẽ không thể phục hồi!');
        if (!accept) return false;
        $.ajax({
            url: "{{ url(config('cms.backend_prefix').'/'.uri().'/delete-customer-product') }}",
            type: "post",
            beforeSend: showAppLoading, complete: hideAppLoading,
            data: {
                _token: "{!! csrf_token() !!}",
                id: row.id
            },
            success: function(data){
                self.get_current_additional(['product']);
                toastr[data.status](data.message);
            }
        });
    };

    self.show_choose_reminder_template = function(e){
        self.form_data.current(e);
        $("#modal-reminder-template").modal("show");
    };
    self.choose_reminder_template = function(template_id){
        var customer = self.form_data.current();
        var accept = confirm('Xác nhận tạo nhắc nhở theo mẫu cho đơn hàng #'+customer.id+'. Hệ thống sẽ tự động tạo và không thể ngưng trong quá trình thao tác!');
        if (!accept) return false;
        $.ajax({
            url: "{{ url(config('cms.backend_prefix').'/'.uri().'/generate-reminder') }}",
            type: "post",
            beforeSend: showAppLoading, complete: hideAppLoading,
            data: {
                _token: "{!! csrf_token() !!}",
                id: customer.id,
                template_id: template_id
            },
            success: function(data){
                self.grid_data.fetch();
                toastr[data.status](data.message);
                $("#modal-reminder-template").modal("hide");
            }
        });
    };
    self.reset_reminders = function(e){
        var accept = confirm('Xác nhận reset các nhắc nhở cho đơn hàng #'+e.id+'. \nHệ thống sẽ tự động xoá các nhắc nhở có thời gian nhắc kể từ ngày hiện tại về sau và không thể ngưng lại trong quá trình thao tác!');
        if (!accept) return false;
        $.ajax({
            url: "{{ url(config('cms.backend_prefix').'/'.uri().'/reset-reminder') }}",
            type: "post",
            beforeSend: showAppLoading, complete: hideAppLoading,
            data: {
                _token: "{!! csrf_token() !!}",
                id: e.id
            },
            success: function(data){
                self.grid_data.fetch();
                toastr[data.status](data.message);
            }
        });
    };
    self.show_assign_user = function(e){
        self.form_data.current(e);
        $("#modal-assigned-user").modal("show");
    };
    self.assign_user = function(user_id){
        var customer = self.form_data.current();
        var accept = confirm('Xác nhận chọn người dùng cho đơn hàng!');
        if (!accept) return false;
        $.ajax({
            url: "{{ url(config('cms.backend_prefix').'/'.uri().'/assign-user') }}",
            type: "post",
            beforeSend: showAppLoading, complete: hideAppLoading,
            data: {
                _token: "{!! csrf_token() !!}",
                id: customer.id,
                user_id: user_id
            },
            success: function(data){
                self.grid_data.fetch();
                toastr[data.status](data.message);
                $("#modal-assigned-user").modal("hide");
            }
        });
    };
}
var toolbar = new ToolBar();
</script>
<?php
    $btn = "'edit'";
    if(\Account::has_permission(['route'=>'customer','permission_name'=>'delete'])){
        $btn .= $btn == "" ? "'delete'":", 'delete'";
    }
?>
<grid params="cols: {
        state: '#',
        field: 'Thông tin chung',
        name: 'Khách hàng'
    },
    sorts: ['state', 'name', 'field'],
    url: '{{ uri() }}',
    token: '{{ csrf_token() }}',
    buttons: [{{$btn}}],
    cellsrenderer: toolbar.cellsrenderer,
    leftToolbar: 'filters-toolbar',
    editToolbar: 'edit-toolbar',
    add: toolbar.add,
    edit: toolbar.edit,
    callback: toolbar.grid_init,
    filters: [
    @if(\Request::has('id'))
        {key:'id',value:[{{ \Request::get('id') }}]},
    @endif
    @if(\Request::has('state'))
        {key:'state',value:['{{ \Request::get('state') }}']},
    @endif
    @if(\Request::has('user_assigned_id'))
        {key:'user_assigned_id',value:['{{ \Request::get('user_assigned_id') }}']},
    @endif
    @if(\Request::has('user_created_id'))
        {key:'user_created_id',value:['{{ \Request::get('user_created_id') }}']},
    @endif
    ],
    trans: {
        data_empty_label: 'Không có dữ liệu',
        add: 'Thêm',
        refresh: 'Làm mới',
        delete: 'Xoá',
        pagination_of_total: 'Trong tổng số',
        search: 'Tìm kiếm',
        delete_question: 'Bạn chắc chắn muốn xoá dữ liệu này?',
        cancel: 'Huỷ'
    }" data-bind="visible: toolbar.view() === 'grid'"></grid>
<edit-form params="url: '{{ uri() }}',
    token: '{{ csrf_token() }}',
    buttons: ['add', 'edit'],
    back: function (){ toolbar.view('grid'); },
    prepare_save: toolbar.prepare_save,
    saved: toolbar.saved,
    template: 'edit-form',
    toolbar: { 
        btnSaveAndNew: false,
        @if(!\Account::has_permission(['route'=>'customer','permission_name'=>'update']))
        btnSaveAndBack: false
        @endif
    },
    callback: toolbar.form_init" data-bind="visible: toolbar.view() === 'form'"></edit-form>

<script type="text/html" id="edit-toolbar">
    <div class="btn-group edit-toolbar">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-cog" aria-hidden="true"></i> <span class="caret"></span>
        </button>
        <ul class="dropdown-menu pull-right edit-btns">
            @if(\Account::has_permission(['route'=>'customer','permission_name'=>'update']))
            <li class="edit-btn-item">
                <a href="#" data-bind="click: toolbar.edit.bind($data, $rawData)">
                    <i class="w-20 text-center fa fa-edit"></i> Chỉnh sửa
                </a>
            </li>
            @endif
            @if(\Account::has_permission(['route'=>'customer','permission_name'=>'assign-sale-pic']))
            <li class="edit-btn-item">
                <a href="#" data-bind="click: toolbar.show_assign_user.bind($data, $rawData), visible: $data.user_assigned_id == 1">
                    <i class="w-20 text-center fa fa-check-square-o"></i> Chọn Sale PIC
                </a>
            </li>
            @endif
            @if(\Account::has_permission(['route'=>'customer','permission_name'=>'generate-reminder']))
            <li class="edit-btn-item">
                <a href="#" data-bind="click: toolbar.show_choose_reminder_template.bind($data, $rawData)">
                    <i class="w-20 text-center fa fa-bolt"></i> Tạo nhắc nhở bằng mẫu
                </a>
            </li>
            @endif
            @if(\Account::has_permission(['route'=>'customer','permission_name'=>'reset-reminder']))
            <li class="edit-btn-item">
                <a href="#" data-bind="click: toolbar.reset_reminders.bind($data, $rawData)">
                    <i class="w-20 text-center fa fa-retweet"></i> Reset toàn bộ nhắc nhở
                </a>
            </li>
            @endif
            @if(\Account::has_permission(['route'=>'customer','permission_name'=>'change-sale-pic']))
            <li class="edit-btn-item">
                <a href="#" data-bind="click: toolbar.show_assign_user.bind($data, $rawData), visible: $data.user_assigned_id != 1">
                    <i class="w-20 text-center fa fa-pencil"></i> Thay đổi Sale PIC
                </a>
            </li>
            @endif
        </ul>
    </div>
</script>

@include('admin.blocks.customer.customer_filters')

@include('admin.blocks.customer.customer_modal')

<script type="text/html" id="edit-form">
    @include('admin.forms.customer.customer')
</script>
@endsection