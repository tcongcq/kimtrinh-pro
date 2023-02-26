<div class="row">
    <div class="col-md-4 frm-desc">
        <div class="media">
            <div class="media-left">
                <a>
                    <img class="media-object" src="{{ url('assets/images/admin/list.png') }}" width="128">
                </a>
            </div>
            <div class="media-body">
                <h3 class="media-heading">Bản mẫu nhắc nhở</h3>
                <p>- Quản lý việc định nghĩa các bản mẫy nhắc nhở trên hệ thống.</p>
                <p>- Cho phép thêm, sửa, xoá thông tin các bản mẫu.</p>
            </div>
        </div>
        <hr/>
        <div class="form-group">
            <label for="priority" class="control-label">Nhắc nhở ưu tiên</label>
            <label class='toggle-label'>
                <input type="checkbox" class="form-control" name="priority" id="priority" data-bind="checked: current().priority" />
                <span class="back">
                    <span class="toggle"></span>
                    <span class="label on">ON</span>
                    <span class="label off">OFF</span>
                </span>
            </label>
        </div>
    </div>
    <div class="col-md-8">
        <legend>Thông tin bản mẫu</legend>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="name" class="control-label">Tên bản mẫu <sup class="text-danger">(*)</sup></label>
                    <input type="text" class="form-control" name="name" id="name" data-bind="value: current().name" placeholder="Tên bản mẫu..." required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="code" class="control-label">Mã bản mẫu <sup class="text-danger">(*)</sup></label>
                    <input type="text" class="form-control" name="code" id="code" data-bind="value: current().code" placeholder="Mã bản mẫu..." required>
                </div>
            </div>
            <div class="col-md-4">
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
                    <label for="description" class="control-label">Mô tả bản mẫu</label>
                    <textarea type="text" rows="6" class="form-control" name="description" id="description" data-bind="value: current().description" placeholder="Mô tả bản mẫu..."></textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12" data-bind="visible: method()=='update'">
        <hr/>
        <legend>Danh sách các nhắc nhở
            <span class="btn btn-primary btn-refresh ml-5" data-bind="click: toolbar.show_reminder_template_item.bind($data, {active: 1})">
                <i class="fa fa-plus"></i></span>
        </legend>
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th class="text-center">STT</th>
                    <th class="text-center">Thời gian nhắc nhở</th>
                    <th class="text-center">Nội dung nhắc nhở</th>
                    <th class="text-center">Khả dụng</th>
                    <th class="text-center">#</th>
                </tr>
            </thead>
            <tbody>
            <!-- ko if: toolbar.reminder_template_items().length > 0 -->
                <!-- ko foreach: toolbar.reminder_template_items() -->
                <tr>
                    <td class="text-center"><a href="#" data-bind="click: toolbar.show_reminder_template_item.bind($data, $rawData)"><span data-bind="text: $index()+1"></span></a></td>
                    <td class="text-center"><span data-bind="text: $data.remind_at"></span> ngày</td>
                    <td data-bind="text: $data.name"></td>
                    <td class="text-center">
                        <span data-bind="visible: !$data.active" class="label label-default">Không khả dụng</span>
                        <span data-bind="visible: $data.active" class="label label-success">Khả dụng</span>
                    </td>
                    <td class="center-middle">
                        <div class="btn btn-danger btn-sm" data-bind="click: toolbar.delete_reminder_template_item.bind($data, $rawData)"><i class="fa fa-trash"></i> Xoá</div>
                    </td>
                </tr>
                <!-- /ko -->
            <!-- /ko -->
            <!-- ko if: toolbar.reminder_template_items().length == 0 -->
                <tr>
                    <td colspan="8">Chưa có hạng mục nào</td>
                </tr>
            <!-- /ko -->
            </tbody>
        </table>
    </div>
</div>
