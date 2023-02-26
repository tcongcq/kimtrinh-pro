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

    self.grid_init = function (grid) {
        self.grid_data = grid;
    };
    self.form_init = function (form) {
        self.form_data = form;
    };
    self.grid = function (attr, param) {
        return self.grid_data[attr](param);
    };

    self.add = function (e) {
        self.form_data.method('add');
        self.form_data.current({});
        self.view('form');
    };

    self.edit = function (e) {
        self.form_data.method('update');
        self.form_data.current(e);
        self.view('form');
    };

    self.prepare_save = function(){
        self.form_data.current().important = self.form_data.current().important == true ? 1 : 0;
    };

    self.saved = function () {
        self.grid_data.fetch();
    };

    self.cellsrenderer = {
        thumbnail_src: function (data) {
            var thumbnail_src = 'assets/images/admin/no-image.png';
            if (data.thumbnail_src)
                thumbnail_src = data.thumbnail_src;
            return '<div style="background-image: url({{ url("/") }}/'+thumbnail_src+'); width: 90px; height: 60px;background-position: center; background-size: cover; background-color: #eee; border: 1px solid #ddd;"></div>';
        },
        customer_id: function(row){
            if (!row.customer_info)
                return row.name;
            var data = row.customer_info;
            var text = (data.display_name ? 'Tên gợi nhớ: '+data.display_name+'<br/>' : '');
            text += '- KH: <strong class="text-blue">'+data.name+'</strong>';
            if (data.phone)
                text += '<br/>- SĐT: '+data.phone;
            return text;
        },
        name: function(data){
        	return [data.name, data.file_extension].join(".");
        }
    };

}
var toolbar = new ToolBar();
</script>
<grid params="cols: {
        thumbnail_src: 'Thumbnail',
        name: 'Tên',
        customer_id: 'Khách hàng',
        file_type: 'Loại file'
    },
    sorts: ['name', 'file_extension', 'customer_id', 'file_type'],
    url: '{{ uri() }}',
    token: '{{ csrf_token() }}',
    buttons: ['edit','delete'],
    cellsrenderer: toolbar.cellsrenderer,
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
    buttons: ['add', 'edit'],
    back: function (){ toolbar.view('grid'); },
    prepare_save: toolbar.prepare_save,
    saved: toolbar.saved,
    template: 'edit-form',
    toolbar: { btnSaveAndNew: false },
    callback: toolbar.form_init" data-bind="visible: toolbar.view() === 'form'"></edit-form>

<script type="text/html" id="edit-form">
    @include('admin.forms.resource.attachment')
</script>
@endsection