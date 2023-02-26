<link rel="stylesheet" href="{{ url('assets/css/style.min.css') }}">
<!-- <link rel="stylesheet" href="{{ url('assets/css/style.css') }}"> -->
<script src="{{ url('assets/js/js.cookie.js') }}"></script>
<script type="text/javascript" src="{{ url('assets/js/locale.vi.js') }}"></script>
<script type="text/javascript">moment.locale('vi');</script>
@section('quick-start-menu')
<li style="padding: 8px;">
    <div data-bind="click: ()=>window.location.replace('{{ url(config('cms.backend_prefix')) }}/customer-reminder')" class="btn btn-default btn-notif btn-circle" style="width: 38px;">
        <i class="glyphicon glyphicon-calendar"></i>
        <sup data-bind="text: reminder.reminder_count, visible: reminder.show_reminder_count" style="display: none;"></sup>
    </div>
</li>
<li style="padding: 8px;">
    <div class="btn btn-default btn-notif btn-circle" data-bind="click: notification.toggle_notif">
        <i class="glyphicon glyphicon-bell"></i>
        <sup data-bind="text: notification.notif_count, visible: notification.show_notif_count" style="display: none;"></sup>
    </div>
    <div class="nano" style="height: 450px; position: absolute; background: #fff; width: 768px; right: 8px; top: 42px; border: 1px solid #f0ad4e; border-radius: 4px; display: none;" data-bind="visible: notification.show_notif">
        <div class="nano-content">  
            <table class="table table-bordered">
                <tbody>
                <!-- ko foreach: notification.notifications -->
                    <tr>
                        <td data-bind="style: {'background-color': !$data.seen ? '#eee' : ''}">
                            <a data-bind="click: notification.seen_and_open">
                                <img class="media-object btn-circle pull-left mr-10" data-bind="attr: {'src': '{{url('/')}}/'+$data.uc_avatar}" width="40" height="40" />
                                <span data-bind="html: $data.title"></span>
                            </a>
                        </td>
                    </tr>
                <!-- /ko -->
                </tbody>
            </table>
        </div>
    </div>
</li>
<li style="padding: 8px;">
    <input class="form-control" name="quick_search_customer" id="quick_search_customer" data-bind="event: { keyup: search.quick_search_customer.bind($data, event) }" style="min-width: 282px; width: 100%;" placeholder="Tìm nhanh khách hàng..." />
    <div class="nano" data-bind="visible: search.searchInp().length != 0" style="height: 450px; position: absolute; background: #fff; width: 768px; right: 8px; top: 42px; border: 1px solid #f0ad4e; border-radius: 4px; display: none;">
        <div class="nano-content">  
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th style="text-align: center;">MSKH</th>
                        <th>Đơn hàng</th>
                        <th class="hidden">Thông tin</th>
                    </tr>
                </thead>
                <tbody data-bind="html: search.searchResult"></tbody>
            </table>
        </div>
    </div>
</li>
<script type="text/javascript">
let screenWidth = (window.innerWidth > 0) ? window.innerWidth : screen.width;
if (screenWidth <= 414)
    $(".navbar-brand.app-title").html('{{ trans("app.short-title") }}');
    // <img class="media-object pull-left mr-5" src="http://localhost:8000/assets/images/logo-small.png" alt="..." width="30" style="margin-top: -5px; "/>
@if(!empty(\Auth::user()->avatar))
setTimeout(function(){
    let avatar = $('<img/>').attr('class', 'media-object btn-circle pull-left mr-10').attr('src', '{{ url(\Auth::user()->avatar) }}').attr('width', 40).attr('height', 40).css('margin-top', '-10px');
    let icon_user = $("#navbar-menu .glyphicon-user");
    $(avatar).insertBefore(icon_user);
    icon_user.addClass('hidden');
}, 500);
@endif
function Search(){
    var self = this;
    self.searchInp = ko.observable('');
    self.customers = ko.observableArray([]);
    self.searchResult = ko.observable('<tr><td colspan="3" class="text-center"><i>(Chưa có dữ liệu)</i></td></tr>');
    self.quick_search_customer = function(e){
        var search = $(e.target).val();
        if(e.code == 'Escape'){
            self.searchInp('');
            return false;
        }
        self.searchInp(search);
        $.ajax({
            url: "{{ url(config('cms.backend_prefix').'/dashboard/search-customer') }}",
            type: "get",
            data: {
                search: search,
                search_by: 'search-bar'
            },
            // beforeSend: showAppLoading, complete: hideAppLoading,
            success: function (data) {
                self.searchResult(data);
            }
        });
    };
};
var search = new Search();
function Reminder(){
    var self = this;
    self.reminder_count = ko.observable(0);
    self.show_reminder_count = ko.observable(false);
    self.init = function(){
        self.get_unfinished_reminder();
    };
    self.get_unfinished_reminder = function(){
        $.ajax({
            url: "{{ url(config('cms.backend_prefix')) }}/dashboard/unfinished-reminder",
            type: "get",
            data: {
                type_get: 'JSON'
            },
            // beforeSend: showAppLoading, complete: hideAppLoading,
            success: function (res) {
                var data = res.data;
                self.reminder_count(res ? res.length : 0);
                self.show_reminder_count(res && res.length > 0 ? true : false);
            }
        });
    };
}
var reminder = new Reminder();
reminder.init();

function Notification(){
    var self = this;
    self.notif_count = ko.observable(0);
    self.show_notif_count = ko.observable(false);
    self.show_notif = ko.observable(false);
    self.notifications= ko.observableArray([]);
    self.init = function(){
        self.get_unseen_notif();
    };
    self.toggle_notif = function(){
        if (!self.show_notif())
            self.get_unseen_notif();
        self.show_notif(!self.show_notif());
    };
    self.get_unseen_notif = function(show){
        $.ajax({
            url: "{{ url(config('cms.backend_prefix')) }}/dashboard/unseen-notif",
            type: "get",
            data: {
                type_get: 'JSON'
            },
            // beforeSend: showAppLoading, complete: hideAppLoading,
            success: function (res) {
                self.notifications(res);
                let unseen = res.filter(e=>e.seen == 0);
                self.notif_count(unseen ? unseen.length : 0);
                self.show_notif_count(res && res.length > 0 ? true : false);
                if (show != undefined)
                    self.show_notif(show);
            }
        });
    };
    self.seen_and_open = function(e){
        $.ajax({
            url: "{{ url(config('cms.backend_prefix')) }}/dashboard/seen-notif",
            type: "get",
            data: {
                id: e.id
            },
            // beforeSend: showAppLoading, complete: hideAppLoading,
            success: function (res) {
                if (e.ref_url)
                    window.location.replace("{{ url('/') }}/"+e.ref_url);
                self.show_notif(!self.show_notif());
            }
        });
    };
}
var notification = new Notification();
notification.init();
</script>
@endsection
@section('profile-menu')
<li>
    <a href="{{ url(config('cms.backend_prefix').'/profile') }}">
        <span class="glyphicon glyphicon-edit"></span> Thông tin cá nhân
    </a>
</li>
@endsection