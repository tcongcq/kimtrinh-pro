<div class="col-md-6">
    <legend>Thông tin đơn hàng</legend>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="code" class="control-label">Mã đơn hàng</label>
                <div class="input-group">
                    <input type="text" class="form-control" name="code" id="code" data-bind="value: current().code, attr: {'disabled': method()=='update' ? true:false}" placeholder="Mã đơn hàng..." />
                    <span class="input-group-btn">
                        <div class="btn btn-default" data-bind="click: toolbar.copyToClipboard.bind($data, current().code)"><i class="fa fa-clipboard" aria-hidden="true"></i></div>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group" data-bind="attr: {'disabled': method()=='update' ? true:false}">
                <label for="user_assigned_id" class="control-label">Người phụ trách</label>
                <!-- ko if: current().user_assigned_info != null -->
                <span class="form-control" disabled>
                    <span data-bind="text: current().user_assigned_info.fullname"></span>
                    (<strong data-bind="text: current().user_assigned_info.phone"></strong>)
                </span>
                <!-- /ko -->
                <!-- ko if: current().user_assigned_info == null -->
                <span class="form-control" disabled>
                    (Chưa có)
                </span>
                <!-- /ko -->
            </div>
        </div>
    </div>
    <span data-bind="visible: method()=='update'">
        <legend>Nhật ký chăm sóc khách hàng</legend>
        <div class="form-group">
            <div class="msg">
                <div class="list">
                    <ul class="message-list customer-message">
                    <!-- ko if: toolbar.customer_messages().length > 0 -->
                        <!-- ko foreach: toolbar.customer_messages() -->
                        <li>
                            <div class="title">
                                <strong data-bind="text: $data.fullname"></strong>
                                <span class="date" data-bind="text: $data.created_at"></span>
                            </div>
                            <div class="content" data-bind="html: $data.content"></div>
                        </li>
                        <!-- /ko -->
                    <!-- /ko -->
                    <!-- ko if: toolbar.customer_messages().length == 0 -->
                        <li>Trống</li>
                    <!-- /ko -->
                    </ul>
                </div>
                <div class="grp-inp">
                    <input class="inp-text" id="inp-text-message" onkeypress="toolbar.message_create(event)" placeholder="Nhập gì đó ở đây..." />
                    <i class="fa fa-send"></i>
                </div>
            </div>
        </div>
    </span>
    <div class="form-group">
        <label for="demand" class="control-label">Mục tiêu và kế hoạch</label>
        <textarea type="text" rows="5" class="form-control" name="demand" id="demand" data-bind="value: current().demand" placeholder="Mục tiêu và kế hoạch..."></textarea>
    </div>
    <div class="form-group">
        <label for="concern" class="control-label">Ghi chú khác</label>
        <textarea type="text" rows="5" class="form-control" name="concern" id="concern" data-bind="value: current().concern" placeholder="Ghi chú khác..."></textarea>
    </div>
</div>
<div class="col-md-6" data-bind="visible: method()=='update'">
    <span class="hidden">
        <legend>Khách hàng ưu tiên</legend>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="priority" class="control-label">Khách NET</label>
                    <select class="form-control selectpicker" name="priority" id="priority">
                        <option value="NET" data-content="<span class='label label-warning'>Khách ưu tiên</span>">Khách ưu tiên</option>
                        <option value="NONE" data-content="<span class='label label-default'>Khách bình thường</span>">Khách bình thường</option>
                    </select>
                </div>
            </div>
        </div>
    </span>
    <legend>Nhắc nhở CSKH</legend>
    <div class="reminder mt-10">
        <div class="list" style="height: 464px">
            <ul class="message-list">
            <!-- ko if: toolbar.customer_reminders().length > 0 -->
                <!-- ko foreach: toolbar.customer_reminders() -->
                <li>
                    <span class="text-bold text-purple" data-bind="text: $data.ru_fullname"></span>
                    <label data-bind="visible: $data.approveed == 1" class="label label-success">Đã xác nhận</label>
                    <label data-bind="visible: $data.approveed == 0" class="label label-default">Chưa xác nhận</label>
                    <div class="title">
                        <span>- Nội dung: <strong data-bind="html: $data.name"></strong></span>
                        <span class="date" data-bind="text: toolbar.date_format($data.remind_at)"></span>
                    </div>
                    <!-- ko if: $data.approveed == 1 -->
                    <div class="content hidden">- Nội dung xác nhận: <span data-bind="text: $data.approved_note"></span></div>
                    <!-- /ko -->
                    <!-- ko if: $data.note -->
                    <div class="content">- Nội dung chuyển ngày: <span data-bind="text: note"></span></div>
                    <!-- /ko -->
                    <div class="three-dot" onclick="$(this).find('+ul.message-control').toggleClass('show')">...</div>
                    <ul class="message-control">
                        <li data-bind="click: toolbar.reminder_approve.bind($data, $rawData), visible: approveed == 0">Xác nhận <i class="fa fa-check"></i></li>
                        @if(\Account::has_permission(['route'=>'customer','permission_name'=>'change-reminder']))
                        <li data-bind="click: toolbar.reminder_edit.bind($data, $rawData)">Chỉnh sửa <i class="fa fa-pencil"></i></li>
                        @endif
                        @if(\Account::has_permission(['route'=>'customer','permission_name'=>'delete-reminder']))
                        <li data-bind="click: toolbar.reminder_delete.bind($data, $rawData)" class="del">Xoá <i class="fa fa-trash"></i></li>
                        @endif
                    </ul>
                </li>
                <!-- /ko -->
            <!-- /ko -->
            <!-- ko if: toolbar.customer_reminders().length == 0 -->
                <li style="padding-bottom: 10px;">Trống</li>
            <!-- /ko -->
            </ul>
        </div>
        <div class="add-reminder">
            <div class="form-group mb-0">
                <div class="input-group date" id="reminder_time" name="reminder_time">
                    <input type="text" class="form-control" style="border-radius: 0;" placeholder="Chọn thời gian (*)..." />
                    <span class="input-group-addon" style="border-radius: 0;">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
            <div class="grp-inp">
                <div class="input-group">
                    <textarea type="text" class="form-control" id="reminder-text-message" name="reminder-text-message" style="border-radius: 0;" data-bind="value: toolbar.customer_reminder().name" placeholder="Nội dung nhắc nhở (*)..."></textarea>
                    <span class="input-group-addon" style="border-radius: 0;" data-bind="click: toolbar.reminder_create">
                        <span class="fa fa-plus"></span> Thêm nhắc nhở
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
