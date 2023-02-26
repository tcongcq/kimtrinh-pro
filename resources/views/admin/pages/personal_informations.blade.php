@extends('cms::layouts.crud')

@section('assets')
@include('admin.assets')
@endsection

@section('main')
@include('admin.blocks.assets.style')
@include('admin.blocks.assets.hide_nav_menu')

<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="app-toolbar">
            <div class="pull-left">
                <legend style="line-height: 1.42857143; padding: 6px 12px;">
                    Thông tin cá nhân
                </legend>
            </div>
            <div class="pull-right">
                <button type="button" class="btn btn-default" onclick="window.location.href ='{{ url("admin/profile") }}'">
                    <span class="glyphicon glyphicon-refresh"></span> Huỷ
                </button>
                <button type="button" class="btn btn-primary" onclick="$('#edit-form').submit()">
                    <span class="glyphicon glyphicon-floppy-disk"></span> Lưu
                </button>
            </div>
        </div>
    </div>
</nav>
<form id="edit-form" method="post" action="{{ url('profile') }}">
    <div style="padding: 15px;">
        <div class="col-md-3 frm-desc">
            <div class="media">
                <a class="thumnail" onclick="open_popup('{{ url('admin/filemanager?secret='.bcrypt(env('APP_KEY')).'&imageID=img' ) }}')">
                    <img id="img" src="{{ url('assets/images/admin/user.png') }}" width="250">
                </a>
                <input class="hidden" name="avatar" id="image-input" value="">
                <label for="avatar" id ="avatar" class="control-label">Ảnh đại diện</label>
            </div>
        </div>
        <div class="col-md-5">
            <legend>Thông tin cá nhân</legend>
            <div class="form-group">
                <label for="fullname" class="control-label">Họ tên</label>
                <input type="text" class="form-control" name="fullname" id="fullname" value="{{ $profile->fullname }}">
            </div>
            <div class="form-group">
                <label for="birthday" class="control-label">Ngày sinh</label>
                <div class='input-group date' id='birthday'>
                    <input type='text' class="form-control" required />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
            <div class="form-group">
                <label for="gender" class="control-label">Giới tính</label>
                <select type="text" class="form-control" name="gender" id="gender" value="{{ $profile->gender }}">
                    <option value="1">Nam</option>
                    <option value="0">Nữ</option>
                </select>
            </div>
            <div class="form-group">
                <label for="email" class="control-label">Email</label>
                <input type="email" class="form-control" name="email" id="email" value="{{ $profile->email }}">
            </div>
            <div class="form-group">
                <label for="address" class="control-label">Địa chỉ</label>
                <input type="text" class="form-control" name="address" id="address" value="{{ $profile->address }}">
            </div>
            <div class="form-group">
                <label for="phone" class="control-label">Điện thoại</label>
                <input type="text" class="form-control" name="phone" id="phone" onkeydown="return (event.ctrlKey || event.altKey
                            || (47 < event.keyCode && event.keyCode < 58 && event.shiftKey == false)
                            || (95 < event.keyCode && event.keyCode < 106)
                            || (event.keyCode == 8) || (event.keyCode == 9)
                            || (event.keyCode > 34 && event.keyCode < 40)
                            || (event.keyCode == 46))"
                       maxlength="11" value="{{ $profile->phone }}">
            </div>
        </div>
        <div class="col-md-4">
            <legend>thông tin tài khoản</legend>
            <div class="form-group">
                <label for="username" class="control-label">Tên đăng nhập</label>
                <span class="form-control" disabled>{{ $profile->username }}</span>
            </div>
            <div class="form-group">
                <label for="password" class="control-label">Mật khẩu mới</label>
                <input type="password" class="form-control" name="password" id="password" value="" placeholder="Nhập nếu thay đổi mật khẩu">
            </div>
            <div class="form-group">
                <label for="r_password" class="control-label">Nhập lại mật khẩu</label>
                <input type="password" class="form-control" name="r_password" id="r_password" value="" placeholder="Nhập lại mật khẩu mới">
            </div>
        </div>
    </div>
</form>

<script type="text/javascript">
function ymdFormat(dateStr){
    var day = dateStr['0'] + dateStr['1'];
    var month = dateStr['3'] + dateStr['4'];
    var year = dateStr['6'] + dateStr['7'] + dateStr['8'] + dateStr['9'];
    return month + '/' + day + '/' + year;
}
$('.date').datetimepicker({
    viewMode: 'days',
    format: 'DD/MM/YYYY',
    locale: 'vi'
});
$('#birthday input').val(String ('{{ $profile->birthday }}').strtotime('d/m/Y'));
// $('#id_card_at input').val(String ('{{ $profile->id_card_at }}').strtotime('d/m/Y'));
$('#gender').val('{{ $profile->gender }}');
$('#img').attr('src', '{{ $profile->avatar }}' == '' ? '{{ url("assets/images/admin/user.png") }}': '{{ url("/") }}/' + '{{ $profile->avatar }}');
$('#img').attr('data-src', '{{ $profile->avatar }}' ? '{{ $profile->avatar }}' : '');
$('#edit-form').on('submit', function() {
    if ($('#password').val() != '' || $('#r_password').val() != ''){
    if ($('#password').val() != $('#r_password').val()){
        toastr['error']('Mật khẩu nhập lại không khớp!');
        return false;
        }
    }
    $('#image-input').val($('#img').attr('data-src'));
    var formData = $(this).serializeArray();
    $.ajax({
    url: "{{ url('admin/profile') }}",
        type: "post",
        dateType: "text",
        beforeSend: showAppLoading, complete: hideAppLoading,
        data: {
            _token: '{{ csrf_token() }}',
            data: formData,
            dayOfBirth: ymdFormat($('#birthday input').val()).strtotime('Y-m-d H:i:s')
        },
        success : function (result){
            toastr[result.status](result.message);
            setTimeout(function(){ window.location.href = "{!! url('admin/dashboard') !!}" }, 2000);
        }
    });
    return false;
});
</script>


@endsection