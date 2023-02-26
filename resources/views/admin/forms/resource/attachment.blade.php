<div class="row">
    <div class="col-md-4 frm-desc">
        <div class="media">
            <div class="media-left">
                <a>
                    <img
                        id="thumbnail_src"
                        width="200"
                        height="140"
                        style="background-repeat: no-repeat; background-size: cover; background-position: center; margin-top: 30px;"
                        class="media-oject"
                        data-bind="style:{'background-image': 'url({{url('/')}}/' + ((current().thumbnail_src && current().thumbnail_src != '#') ? current().thumbnail_src : 'assets/images/admin/no-image.png') + ')'}"
                    />
                </a>
            </div>
            <div class="media-body">
	            <h3 class="media-heading">File lưu trữ</h3>
	            <p>- Quản lý các file lưu trữ của khách hàng trên hệ thống.</p>
	            <p>- Cho phép thêm, xem, xoá các file của khách hàng.</p>
	        </div>
        </div>
        <legend style="margin-top: 36px;">Cài đặt tùy chọn</legend>
        <div class="form-group">
            <label for="important" class="control-label mb-0">Tệp quan trọng</label>
            <p class="help-block mt-0"><small><i class="text-danger">* Hướng dẫn: Bật tùy chọn này để giữ cho tệp không bị xóa.</i></small></p>
            <label class='toggle-label'>
                <input type="checkbox" class="form-control" name="important" id="important" data-bind="checked: current().important" />
                <span class="back">
                    <span class="toggle"></span>
                    <span class="label on">ON</span>
                    <span class="label off">OFF</span>
                </span>
            </label>
        </div>
    </div>
    <div class="col-md-8">
        <legend>Thông tin file</legend>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name" class="control-label">Tên file lưu trữ <sup class="text-danger">(*)</sup></label>
                    <input type="text" class="form-control" name="name" id="name" data-bind="value: current().name" placeholder="Tên file lưu trữ..." required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="file_type" class="control-label">Loại file</label>
                    <span class="form-control" disabled name="file_type" id="file_type"><span data-bind="text: current().file_type"></span> (<span data-bind="text: current().file_extension ? current().file_extension : 'unknown'"></span>)</span>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="description" class="control-label">Mô tả</label>
                    <textarea type="text" rows="6" class="form-control" name="description" id="description" data-bind="value: current().description" placeholder="Mô tả..."></textarea>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="note" class="control-label">Ghi chú</label>
                    <textarea type="text" rows="6" class="form-control" name="note" id="note" data-bind="value: current().note" placeholder="Ghi chú..."></textarea>
                </div>
            </div>
        </div>
    </div>
</div>
