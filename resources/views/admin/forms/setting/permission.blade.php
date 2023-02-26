<div class="row">
    <div class="col-md-4 frm-desc">
        <div class="media">
            <div class="media-left">
                <a>
                    <img class="media-object" src="{{ url('assets/images/admin/list.png') }}" width="128">
                </a>
            </div>
            <div class="media-body">
                <h3 class="media-heading">Quyền hạn</h3>
                <p>- Quản lý việc định nghĩa các quyền hạn của người dùng trên hệ thống.</p>
                <p>- Cho phép thêm, sửa, xoá các thông tin quyền hạn.</p>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <legend>Thông tin quyền hạn</legend>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name" class="control-label">Tên quyền hạn <sup class="text-danger">(*)</sup></label>
                    <input type="text" class="form-control" name="name" id="name" data-bind="value: current().name" placeholder="Tên quyền hạn..." required>
                </div>
                <div class="form-group">
                    <label for="table" class="control-label">Bảng tác động</label>
                    <input type="text" class="form-control" name="table" id="table" data-bind="value: current().table" placeholder="Bảng tác động...">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="alias" class="control-label">Alias <sup class="text-danger">(*)</sup></label>
                    <input type="text" class="form-control" name="alias" id="alias" data-bind="value: current().alias" placeholder="Alias..." required>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="description" class="control-label">Mô tả quyền</label>
                    <textarea type="text" rows="6" class="form-control" name="description" id="description" data-bind="value: current().description" placeholder="Mô tả quyền..."></textarea>
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
