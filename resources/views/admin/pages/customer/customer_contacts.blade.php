@extends('cms::layouts.crud')

@section('assets')
@include('admin.assets')
@endsection

@section('main')
@include('admin.blocks.assets.style')
@include('admin.blocks.assets.hide_nav_menu')
<script type="text/javascript">
function ToolBar() {
    var self = this;
    self.view = ko.observable('grid');
    self.grid_data = null;
    self.form_data = null;
    self.reminder_template = ko.observable(null);

    self.grid_init = function (grid) {
        self.grid_data = grid;
    };
    self.form_init = function (form) {
        self.form_data = form;
        $('.selectpicker').selectpicker();
    };
    self.grid = function (attr, param) {
        return self.grid_data[attr](param);
    };

    self.add = function (e) {
        self.form_data.method('add');
        self.form_data.current({
            active: 1,
        });
        $('#field').selectpicker('val', '');
        self.view('form');
    };

    self.edit = function (e) {
        self.form_data.method('update');
        self.form_data.current(e);
        $('#field').selectpicker('val', e.field);
        self.view('form');
    };

    self.prepare_save = function(){
        self.form_data.current().active   = self.form_data.current().active == true ? 1 : 0;
        self.form_data.current().field    = $('#field').selectpicker('val');
    };

    self.saved = function () {
        self.grid_data.fetch();
    };

    self.indirect_edit = function(id){
        let row = self.grid_data.rows().filter(r=>r.id == id).shift();
        self.edit(row);
    };

    self.cellsrenderer = {
        id: function(data){
            var text = '<span class="badge badge-warning c_pointer" onclick="toolbar.indirect_edit('+data.id+')">#'+data.id+'</span>';
            if (data.customer_id > 1)
                text += '<br/><a class="badge badge-warning" href="{{ url(config("cms.backend_prefix")."/customer?id=") }}'+data.customer_id+'" target="_blank">'+data.customer_id+'</a>';
            return '<center>'+text+'</center>';
        },
        name: function(data){
            var text = data.client_name ? 'Tên công ty: '+data.client_name+'<br/>' : '';
            text += '- Thương hiệu: <strong>'+data.brand_name+'</strong>';
            text += '<br/>- Tên khách hàng: <strong>'+data.name+'</strong>';
            if (data.phone)
                text += '<br/>- SĐT: <strong>'+data.phone+'</strong>';
            if (data.email)
                text += '<br/>- Email: <strong>'+data.email+'</strong>';
            return text;
        },
        demand: function(data){
            var text = '';
            if (data.field)
                text += 'Ngành hàng: <strong>'+data.field+'</strong>';
            if (data.demaid)
                text += '<br/>Nhu cầu: '+data.demaid;
            return text;
        }
    };

    self.show_assign_user = function(){
        if (!self.check_before_sale_pic())
            return false;
        $("#modal-reminder-template").modal("show");
    };
    self.choose_reminder_template = function(template_id){
        self.reminder_template(template_id)
        $("#modal-assigned-user").modal("show");
    };
    self.assign_user = function(user_id){
        var customer = self.form_data.current();
        var accept = confirm('Xác nhận chọn Sale PIC cho các khách hàng được chọn!');
        if (!accept) return false;
        $.ajax({
            url: "{{ url(config('cms.backend_prefix').'/'.uri().'/assign-user') }}",
            type: "post",
            beforeSend: showAppLoading, complete: hideAppLoading,
            data: {
                _token: "{!! csrf_token() !!}",
                ids: self.grid_data.ids(),
                user_id: user_id,
                template_id: self.reminder_template()
            },
            success: function(data){
                self.grid_data.ids([]);
                self.grid_data.fetch();
                toastr[data.status](data.message);
                $(".modal").modal("hide");
            }
        });
    };
    self.check_before_sale_pic = function(){
        if (self.grid_data.ids().length == 0)
            return false;
        let ids = self.grid_data.ids();
        let rows= self.grid_data.rows();
        let ineligibleIds = rows.filter(r=>ids.includes(r.id)).filter(r=>r.customer_id > 1).map(r=>r.id);
        if (ineligibleIds.length){
            toastr['warning']('Khách hàng đã tồn tại Sale PIC. <br/>Bao gồm các ID: '+ineligibleIds.join(', '));
            return false;
        }
        return true;
    };

    self.check_duplicate = function(e){
        let strCheck = e.target.value;
        if (!strCheck || strCheck.length < 3) return false;
        $.ajax({
            url: "{{ url(config('cms.backend_prefix').'/'.uri().'/check-duplicate') }}",
            type: "get",
            data: {
                input_data: strCheck
            },
            success: function(data){
                if (data.length)
                    toastr['warning']('Có vẻ như thông tin khách hàng đã có trên hệ thống!');
            }
        });
    };
}
var toolbar = new ToolBar();
</script>
<?php
    $btn = "'add'";
    if(\Account::has_permission(['route'=>'customer-contact','permission_name'=>'update'])){
        $btn .= $btn == "" ? "'edit'":", 'edit'";
    }
    if(\Account::has_permission(['route'=>'customer-contact','permission_name'=>'delete'])){
        $btn .= $btn == "" ? "'delete'":", 'delete'";
    }
?>
<grid params="cols: {
        id: '#',
        name: 'Khách hàng',
        demand: 'Thông tin chung'
    },
    sorts: ['id', 'name', 'demand'],
    url: '{{ uri() }}',
    token: '{{ csrf_token() }}',
    buttons: [{{$btn}}],
    cellsrenderer: toolbar.cellsrenderer,
    leftToolbar: 'filters-toolbar',
    add: toolbar.add,
    edit: toolbar.edit,
    callback: toolbar.grid_init,
    filters: [
    @if(\Request::has('id'))
        {key:'id',value:[{{ \Request::get('id') }}]},
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
    buttons: ['add', 'edit','delete'],
    back: function (){ toolbar.view('grid'); },
    prepare_save: toolbar.prepare_save,
    saved: toolbar.saved,
    template: 'edit-form',
    toolbar: { btnSaveAndNew: false },
    callback: toolbar.form_init" data-bind="visible: toolbar.view() === 'form'"></edit-form>

<script type="text/html" id="filters-toolbar">
    <div class="grid-filter-container filter pull-left">
        @if(\Account::has_permission(['route'=>'customer-contact','permission_name'=>'assign-sale-pic']))
        <div class="btn btn-primary ml-5" data-bind="attr: {'disabled': ids().length==0}, click: toolbar.show_assign_user">
            <i class="fa fa-check-square-o" aria-hidden="true"></i> Chọn Sale PIC
        </div>
        @endif
    </div>
</script>

@include('admin.blocks.customer.modal_reminder_template', ['user_assigned_list'=>$user_assigned_list])
@include('admin.blocks.customer.modal_assigned_user', ['user_assigned_list'=>$user_assigned_list])

<script type="text/html" id="edit-form">
    @include('admin.forms.customer.customer_contact')
</script>
@endsection