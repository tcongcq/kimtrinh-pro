<div class="row">
    <div class="col-md-4 frm-desc">
        <div class="media">
            <div class="media-left">
                <a>
                    <img class="media-object" src="{{ url('assets/images/admin/list.png') }}" width="128">
                </a>
            </div>
            <div class="media-body">
                <h3 class="media-heading">Data tiềm năng</h3>
                <p>- Quản lý việc định nghĩa các data khách hàng tiềm năng trên hệ thống.</p>
                <p>- Cho phép thêm, sửa, xoá thông tin các data khách hàng.</p>
            </div>
        </div>
        <hr/>
        <div class="form-group">
            <label for="note" class="control-label">Ghi chú</label>
            <textarea type="text" rows="6" class="form-control" name="note" id="note" data-bind="value: current().note" placeholder="Ghi chú..."></textarea>
        </div>
    </div>
    <div class="col-md-8">
        <legend>Thông tin dịch vụ</legend>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name" class="control-label">Tên khách hàng <sup class="text-danger">(*)</sup></label>
                    <input type="text" class="form-control" name="name" id="name" data-bind="value: current().name" placeholder="Tên khách hàng..." maxlength="50" required>
                </div>
                <div class="form-group">
                    <label for="client_name" class="control-label">Tên công ty</label>
                    <input type="text" class="form-control" name="client_name" id="client_name" data-bind="value: current().client_name" placeholder="Tên công ty...">
                </div>
                <div class="form-group">
                    <label for="brand_name" class="control-label">Thương hiệu <sup class="text-danger">(*)</sup></label>
                    <input type="text" class="form-control" name="brand_name" id="brand_name" data-bind="value: current().brand_name" placeholder="Thương hiệu..." required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="phone" class="control-label">Số điện thoại</label>
                    <input type="text" class="form-control" name="phone" id="phone" data-bind="value: current().phone, event: {change: toolbar.check_duplicate.bind($data, event)}" maxlength="30" placeholder="Số điện thoại...">
                </div>
                <div class="form-group">
                    <label for="email" class="control-label">Email</label>
                    <input type="text" class="form-control" name="email" id="email" data-bind="value: current().email" maxlength="50" placeholder="Email...">
                </div>
                <div class="form-group">
                    <label for="field" class="control-label">Ngành hàng <sup class="text-danger">(*)</sup></label>
                    <select class="form-control selectpicker" name="field" id="field" required>
                        <option value="BEAUTY">BEAUTY</option>
                        <option value="FOOD">FOOD</option>
                        <option value="FASHION">FASHION</option>
                        <option value="LIFESTYLE">LIFESTYLE</option>
                        <option value="AGENCY">AGENCY</option>
                        <option value="ORTHERS">ORTHERS</option>
                    </select>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="demand" class="control-label">Nhu cầu</label>
                    <textarea type="text" rows="6" class="form-control" name="demand" id="demand" data-bind="value: current().demand" placeholder="Nhu cầu..."></textarea>
                </div>
                <div class="form-group">
                    <label for="concern" class="control-label">Lo lắng</label>
                    <textarea type="text" rows="6" class="form-control" name="concern" id="concern" data-bind="value: current().concern" placeholder="Nhu cầu..."></textarea>
                </div>
            </div>
        </div>
    </div>
</div>
