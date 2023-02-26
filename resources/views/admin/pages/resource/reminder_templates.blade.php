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
    self.reminder_template_item = ko.observable({});
    self.reminder_template_items = ko.observableArray([]);

    self.grid_init = function (grid) {
        self.grid_data = grid;
    };
    self.form_init = function (form) {
        self.form_data = form;
    };
    self.view.subscribe(function () {
        self.get_current_additional();
    });
    self.grid = function (attr, param) {
        return self.grid_data[attr](param);
    };

    self.add = function (e) {
        self.form_data.method('add');
        self.form_data.current({active: 1});
        self.reminder_template_item({});
        self.view('form');
    };

    self.edit = function (e) {
        self.form_data.method('update');
        self.form_data.current(e);
        self.reminder_template_item({});
        self.view('form');
    };

    self.get_current_additional = function(adds = ['reminder-item']){
        if (self.form_data.current().id == undefined)
            return false;
        $.ajax({
            url: "{{ url(config('cms.backend_prefix').'/'.uri().'/current-additional') }}",
            type: "get",
            data: {
                id: self.form_data.current().id,
                params: adds
            },
            success : function(data){
                if (adds.includes('reminder-item')){
                    self.reminder_template_items([]);
                    self.reminder_template_items(data.reminder_template_items);
                }
            }
        });
    };

    self.prepare_save = function(){
        self.form_data.current().active   = self.form_data.current().active == true ? 1 : 0;
        self.form_data.current().priority = self.form_data.current().priority == true ? 1 : 0;
    };

    self.saved = function () {
        self.grid_data.fetch();
    };

    self.cellsrenderer = {
        active: function (data) {
            return data.active === 0 ? '<span class="label label-default">Hết áp dụng</span>' : '<span class="label label-success">Còn áp dụng</span>';
        }
    };

    self.show_reminder_template_item = function(e){
        self.reminder_template_item(e);
        $('#modal-reminder-template-item').modal('show');
    };
    self.save_reminder_template_item = function(){
        var saveData = self.reminder_template_item();
        saveData['_token'] = "{!! csrf_token() !!}";
        saveData['reminder_template_id'] = self.form_data.current().id;
        saveData['active'] = self.reminder_template_item().active == true ? 1 : 0;
        if (!saveData['remind_at'])
            saveData['remind_at'] = '+0';
        if (!saveData.name){
            toastr['warning']('Nội dung nhắc nhở không được trống!');
            return false;
        }
        let uri = saveData.id ? 'update-reminder-template-item' : 'create-reminder-template-item';
        $.ajax({
            url: "{{ url(config('cms.backend_prefix').'/'.uri()) }}/"+uri,
            type: "post",
            beforeSend: showAppLoading, complete: hideAppLoading,
            data: saveData,
            success: function(data){
                self.get_current_additional(['reminder-item']);
                toastr[data.status](data.message);
                $('#modal-reminder-template-item').modal('hide');
            }
        });
    };
	self.delete_reminder_template_item = function(row){
        var accept = confirm('Xác nhận xóa nhắc nhở. Dữ liệu sẽ không thể phục hồi!');
        if (!accept) return false;
        $.ajax({
            url: "{{ url(config('cms.backend_prefix').'/'.uri().'/delete-reminder-template-item') }}",
            type: "post",
            beforeSend: showAppLoading, complete: hideAppLoading,
            data: {
                _token: "{!! csrf_token() !!}",
                id: row.id
            },
            success: function(data){
                self.get_current_additional(['reminder-item']);
                toastr[data.status](data.message);
            }
        });
    };
}
var toolbar = new ToolBar();
</script>
<grid params="cols: {
        name: 'Tên bản mẫu',
        active: 'Trạng thái',
        description: 'Mô tả'
    },
    sorts: ['name', 'description', 'active'],
    url: '{{ uri() }}',
    token: '{{ csrf_token() }}',
    buttons: ['edit','add','delete'],
    cellsrenderer: toolbar.cellsrenderer,
    add: toolbar.add,
    edit: toolbar.edit,
    callback: toolbar.grid_init,
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
    toolbar: { btnSaveAndNew: false },
    callback: toolbar.form_init" data-bind="visible: toolbar.view() === 'form'"></edit-form>

<!-- Modal customer product -->
<div class="modal fade" id="modal-reminder-template-item" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <legend class="mb-0">Chi Tiết Nhắc Nhở Mẫu</legend>
            </div>
            <div class="modal-body row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="control-label" for="cp_name">Nội dung nhắc nhở <sup class="text-danger">(*)</sup></label>
                        <input type="text" id="cp_name" name="cp_name" class="form-control" data-bind="value: toolbar.reminder_template_item().name" placeholder="Nội dung nhắc nhở..." />
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="cp_remind_at">Thời gian nhắc nhở</label>
                                <input type="text" id="cp_remind_at" name="cp_remind_at" class="form-control" data-bind="value: toolbar.reminder_template_item().remind_at" placeholder="+0" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="cp_active" class="control-label">Khả dụng</label>
                                <label class='toggle-label'>
                                    <input type="checkbox" class="form-control" name="cp_active" id="cp_active" data-bind="checked: toolbar.reminder_template_item().active" />
                                    <span class="back">
                                        <span class="toggle"></span>
                                        <span class="label on">ON</span>
                                        <span class="label off">OFF</span>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <p class="help-block">
                                <small class="text-danger">*Lưu ý: Thời gian nhắc nhở mẫu được quy ước theo đơn vị ngày tính từ ngày nhắc nhở được tạo ra. Số ngày được quy định bằng số phía sau dấu (+).</small> <br/>
                                <small class="text-danger">Ví dụ: hôm nay là ngày 02.02.2020. Giá trị 0 sẽ tạo ra nhắc nhở trong ngày, +1 sẽ tạo ra nhắc nhở vào 1 ngày sau đó là 03.02.2020, +3, +4, +5... sẽ tạo ra sau bao nhiêu ngày so với ngày gốc lần lượt là 05.02.2020, 06.02.2020, 07.02.2020...</small>
                            </p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="cp_description">Mô tả</label>
                        <textarea type="text" id="cp_note" rows="4" name="cp_note" class="form-control" data-bind="value: toolbar.reminder_template_item().note" placeholder="Mô tả..."></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="pull-right">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary" data-bind="click: toolbar.save_reminder_template_item"><span class="glyphicon glyphicon-floppy-disk"></span> Lưu lại</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/html" id="edit-form">
    @include('admin.forms.resource.reminder_template')
</script>
@endsection