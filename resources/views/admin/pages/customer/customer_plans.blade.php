@extends('cms::layouts.crud')

@section('assets')
@include('admin.assets')
@endsection

@section('main')
@include('admin.blocks.assets.hide_nav_menu')
<script type="text/javascript">
function ToolBar() {
    var self = this;
    self.view = ko.observable('grid');
    self.grid_data = null;
    self.form_data = null;
    self.ci_search = ko.observable('');

    self.customer_info = ko.observable({});

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
        self.form_data.current({
            active: 1,
            finished: 0
        });
        self.customer_info({})
        self.ci_search('');
        self.view('form');
    };

    self.edit = function (e) {
        self.form_data.method('update');
        self.form_data.current(e);
        self.customer_info(e.customer_info)
        self.ci_search(e.customer_id);
        self.view('form');
    };

    self.search_customer = function(data, event){
        self.ci_search($(event.target).val());
        if (event.keyCode != 13)
            return false;
        self.do_search_customer();
    };

    self.do_search_customer = function(){
        var search = parseInt(self.ci_search());
        if (!search) return false;
        $.ajax({
            url: "{{ url(config('cms.backend_prefix').'/'.uri().'/customer-info') }}",
            type: "get",
            // beforeSend: showAppLoading, complete: hideAppLoading,
            data: {
                customer_id: self.ci_search()
            },
            success: function(res){
                if (!res.data)
                    toastr['warning']('Customer not found!');
                else
                    self.customer_info(res.data);
            }
        });
    };

    self.prepare_save = function(){
        self.form_data.current().active   = self.form_data.current().active == true ? 1 : 0;
        self.form_data.current().finished = self.form_data.current().finished == true ? 1 : 0;
        delete self.form_data.current().customer_info;
    };

    self.saved = function () {
        self.grid_data.fetch();
    };

    self.cellsrenderer = {
        customer_id: function(row){
            if (!row.customer_info)
                return row.name;
            var data = row.customer_info;
            var text = (data.display_name ? 'T??n g???i nh???: '+data.display_name+'<br/>' : '');
            text += '- KH: <strong class="text-blue">'+data.name+'</strong>';
            if (data.phone)
                text += '<br/>- S??T: '+data.phone;
            return text;
        },
        finished: function (data) {
            return data.finished === 0 ? '<span class="label label-default">Ch??a ho??n th??nh</span>' : '<span class="label label-success">???? xong</span>';
        }
    };

}
var toolbar = new ToolBar();
</script>
<grid params="cols: {
        emotion: 'C???m x??c',
        name: 'C??ng vi???c',
        customer_id: 'Kh??ch h??ng',
        finished: 'Ho??n th??nh'
    },
    sorts: ['emotion', 'name', 'customer_id', 'finished'],
    url: '{{ uri() }}',
    token: '{{ csrf_token() }}',
    buttons: ['edit','add','delete'],
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
        data_empty_label: 'Kh??ng c?? d??? li???u',
        add: 'Th??m',
        refresh: 'L??m m???i',
        delete: 'Xo??',
        pagination_of_total: 'Trong t???ng s???',
        search: 'T??m ki???m',
        delete_question: 'B???n ch???c ch???n mu???n xo?? d??? li???u n??y?',
        cancel: 'Hu???'
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

<script type="text/html" id="edit-form">
    @include('admin.forms.customer.customer_plan')
</script>
@endsection