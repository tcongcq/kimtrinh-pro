<div class="row">
    <div class="col-md-4 frm-desc">
        <div class="media">
            <div class="media-left">
                <a>
                    <img class="media-object" src="{{ url('assets/images/admin/list.png') }}" width="128">
                </a>
            </div>
            <div class="media-body">
                <h3 class="media-heading">Dịch vụ cung cấp</h3>
                <p>- Quản lý việc định nghĩa các dịch vụ cung cấp cho khách hàng trên hệ thống.</p>
                <p>- Cho phép thêm, sửa, xoá thông tin các dịch vụ.</p>
            </div>
        </div>
        <hr/>
        <div class="form-group">
            <label for="code" class="control-label">Mã dịch vụ <sup class="text-danger">(*)</sup></label>
            <input type="text" class="form-control" name="code" id="code" data-bind="value: current().code" placeholder="Mã dịch vụ..." required>
        </div>
    </div>
    <div class="col-md-8">
        <legend>Thông tin dịch vụ</legend>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name" class="control-label">Tên dịch vụ <sup class="text-danger">(*)</sup></label>
                    <input type="text" class="form-control" name="name" id="name" data-bind="value: current().name" placeholder="Tên dịch vụ..." required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="active" class="control-label">Còn áp dụng</label>
                    <label class='toggle-label'>
                        <input type="checkbox" class="form-control" name="active" id="active" data-bind="checked: current().active" />
                        <span class="back">
                            <span class="toggle"></span>
                            <span class="label on">ON</span>
                            <span class="label off">OFF</span>
                        </span>
                    </label>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="description" class="control-label">Mô tả dịch vụ</label>
                    <textarea type="text" rows="6" class="form-control" name="description" id="description" data-bind="value: current().description" placeholder="Mô tả dịch vụ..."></textarea>
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
