@extends('cms::layouts.crud')

@section('assets')
@include('admin.assets')
@endsection

@section('main')
@include('admin.blocks.assets.style')
@include('admin.blocks.assets.hide_nav_menu')

<script type="text/javascript">
function Toolbar(){
    var self = this;
    self.activeTab = ko.observable('dashboard');
    self.supported_extensions = ko.observableArray(JSON.parse('{!! json_encode($supported_extensions) !!}'));
    self.allowed_extension    = ko.observableArray(JSON.parse('{!! !empty($allowed_extension) ? $allowed_extension : "[]" !!}'));
    self.init = function(){
        // 
    };

    self.switch_tab = function(tab){
        self.activeTab(tab);
    };

    self.get_config_data = function(){
        var config_inputs = $("#configs").find("input");
        var configs = {};
        $.each(config_inputs, function(idx, cfg){
            let val = '';
            switch($(cfg).attr('type')) {
                case 'checkbox':
                    val = $(cfg).prop('checked') ? 1:0;
                    break;
                case 'text':
                    val = $(cfg).val();
                    break;
                default:
                    val = $(cfg).val();
                    break;
                    
            }
            configs[$(cfg).attr('name')] = val;
        });
        return configs;
    };
    self.save_config = function(){
        var configs = self.get_config_data();
        configs['_token'] = "{!! csrf_token() !!}",
        configs['allowed_extension'] = JSON.stringify(self.allowed_extension());
        $.ajax({
            url: "{{ uri() }}/save-config",
            type: "post",
            data: configs,
            beforeSend: showAppLoading, complete: hideAppLoading,
            success: function (data) {
                toastr[data.status](data.message);
            }
        });
    };
    self.toggle_extention = function(ext){
        var exts = self.jsonCopy(self.allowed_extension());
        if (!exts.includes(ext)){
            exts.push(ext);
        } else {
            exts = exts.filter(function(item) {
                return item !== ext
            });
        }
        self.allowed_extension(exts);
    };
    self.toggle_all_extention = function(type='on'){
        if (type == 'off')
            self.allowed_extension([]);
        else
            self.allowed_extension(self.jsonCopy(self.supported_extensions()))
    };
    self.jsonCopy = function(_d){
        return JSON.parse(JSON.stringify(_d));
    };
}
var toolbar = new Toolbar();
</script>
<div id="block-manager" style="padding: 15px 15px 0px; user-select: none;">
    @include('admin.forms.setting.system_config')
</div>

@endsection