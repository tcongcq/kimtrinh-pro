<div class="clearfix blocks">
    <div class="pull-left">
        <div class="item-head">Cấu hình</div>
        <div data-bind="css: toolbar.activeTab() == 'dashboard' ? 'item active' : 'item', click: toolbar.switch_tab.bind($data, 'dashboard')" class="item">Trang tổng quan</div>
        <div data-bind="css: toolbar.activeTab() == 'filemanager' ? 'item active' : 'item', click: toolbar.switch_tab.bind($data, 'filemanager')" class="item">
            Quản lý File
            <p class="help-block my-0"><small><i>* Áp dụng cho thành viên</i></small></p>
        </div>
        <div class="item-head">Cài đặt hệ thống</div>
        <div data-bind="css: toolbar.activeTab() == 'setting' ? 'item active' : 'item', click: toolbar.switch_tab.bind($data, 'setting')" class="item">Cài đặt chung</div>
        <div style="margin-top: 10px; text-align: center;">
            <button type="button" onclick="toolbar.save_config()" class="btn btn-primary" style="border-radius: 0px;"><i aria-hidden="true" class="fa fa-floppy-o fa-lg"></i> Lưu thông tin</button>
        </div>
    </div>
    <div class="pull-right" id="configs">
        <div data-bind="visible: toolbar.activeTab() == 'dashboard'" style="display: none;">
            <div class="col-md-7 m-b-t-5">
                <legend>Cấu hình trang tổng quan</legend>
                <div class="form-group">
                    <label for="show_hyperlink_image" class="control-label">1. Hiển thị danh sách liên kết đầu trang</label>
                    <label class='toggle-label'>
                        <input type="checkbox" class="form-control" name="show_hyperlink_image" id="show_hyperlink_image" {{ !empty($show_hyperlink_image) ? 'checked="checked"' : '' }} />
                        <span class="back">
                            <span class="toggle"></span>
                            <span class="label on">ON</span>
                            <span class="label off">OFF</span>
                        </span>
                    </label>
                    <p class="help-block"><i>* Diễn giải: hiển thị/ẩn các liên kết đến các trang khác, tắt hiển thị sẽ giúp tăng không gian cho trang tổng quan.</i></p>
                </div>
                <div class="form-group">
                    <label for="show_quick_search_panel" class="control-label">2. Hiển thị Panel tìm nhanh khách hàng</label>
                    <label class='toggle-label'>
                        <input type="checkbox" class="form-control" name="show_quick_search_panel" id="show_quick_search_panel" {{ !empty($show_quick_search_panel) ? 'checked="checked"' : '' }} />
                        <span class="back">
                            <span class="toggle"></span>
                            <span class="label on">ON</span>
                            <span class="label off">OFF</span>
                        </span>
                    </label>
                    <p class="help-block"><i>* Diễn giải: hiển thị/ẩn Panel tìm nhanh khách hàng.</i></p>
                </div>
                <div class="form-group">
                    <label for="show_customer_plan_panel" class="control-label">3. Hiển thị Panel lên kế hoạch</label>
                    <label class='toggle-label'>
                        <input type="checkbox" class="form-control" name="show_customer_plan_panel" id="show_customer_plan_panel" {{ !empty($show_customer_plan_panel) ? 'checked="checked"' : '' }} />
                        <span class="back">
                            <span class="toggle"></span>
                            <span class="label on">ON</span>
                            <span class="label off">OFF</span>
                        </span>
                    </label>
                    <p class="help-block"><i>* Diễn giải: Hiển thị/ẩn Panel lên kế hoạch của người dùng</i></p>
                </div>
                <div class="form-group">
                    <label for="show_zalo_timeline_note" class="control-label">4. Hiển thị Panel lộ trình gửi Zalo</label>
                    <label class='toggle-label'>
                        <input type="checkbox" class="form-control" name="show_zalo_timeline_note" id="show_zalo_timeline_note" {{ !empty($show_zalo_timeline_note) ? 'checked="checked"' : '' }} />
                        <span class="back">
                            <span class="toggle"></span>
                            <span class="label on">ON</span>
                            <span class="label off">OFF</span>
                        </span>
                    </label>
                    <p class="help-block"><i>* Diễn giải: Hiển thị/ẩn Panel lộ trình gửi Zalo của người dùng</i></p>
                </div>
            </div>
            <div class="col-md-5 m-b-t-5">
                <legend>Xác định tỉ lệ hiển thị</legend>
                <div class="form-group">
                    <label for="scale_value_quick_search_panel" class="control-label">5. Tỉ lệ Panel tìm nhanh khách hàng</label>
                    <input class="form-control" id="scale_value_quick_search_panel" name="scale_value_quick_search_panel" value="{{ !empty($scale_value_quick_search_panel) ? $scale_value_quick_search_panel : '' }}" placeholder="Tỉ lệ Panel tìm nhanh khách hàng..." />
                    <p class="help-block"><i>* Diễn giải: Xác định tỉ lệ Panel tìm nhanh khách hàng. Mặc định màn hình sẽ chia làm 12 phần bằng nhau.</i></p>
                </div>
                <div class="form-group">
                    <label for="scale_value_customer_plan_panel" class="control-label">6. Tỉ lệ Panel lên kế hoạch</label>
                    <input class="form-control" id="scale_value_customer_plan_panel" name="scale_value_customer_plan_panel" value="{{ !empty($scale_value_customer_plan_panel) ? $scale_value_customer_plan_panel : '' }}" placeholder="Tỉ lệ Panel lên kế hoạch..." />
                    <p class="help-block"><i>* Diễn giải: Xác định tỉ lệ Panel lên kế hoạch. Mặc định màn hình sẽ chia làm 12 phần bằng nhau.</i></p>
                </div>
                <div class="form-group">
                    <label for="scale_value_zalo_timeline_note_panel" class="control-label">7. Tỉ lệ Panel Lộ trình gửi Zalo</label>
                    <input class="form-control" id="scale_value_zalo_timeline_note_panel" name="scale_value_zalo_timeline_note_panel" value="{{ !empty($scale_value_zalo_timeline_note_panel) ? $scale_value_zalo_timeline_note_panel : '' }}" placeholder="Tỉ lệ Panel Lộ trình gửi Zalo..." />
                    <p class="help-block"><i>* Diễn giải: Xác định tỉ lệ Panel Lộ trình gửi Zalo. Mặc định màn hình sẽ chia làm 12 phần bằng nhau.</i></p>
                </div>
            </div>
        </div>
        <div data-bind="visible: toolbar.activeTab() == 'filemanager'" style="display: none;">
            <div class="col-md-7 m-b-t-5">
                <legend>Cấu hình quyền quản lý File</legend>
                <div class="form-group">
                    <label for="permission_create_folder" class="control-label">1. Cho phép tạo thư mục</label>
                    <label class='toggle-label'>
                        <input type="checkbox" class="form-control" name="permission_create_folder" id="permission_create_folder" {{ !empty($permission_create_folder) ? 'checked="checked"' : '' }} />
                        <span class="back">
                            <span class="toggle"></span>
                            <span class="label on">ON</span>
                            <span class="label off">OFF</span>
                        </span>
                    </label>
                    <p class="help-block"><i>* Diễn giải: bật/tắt tùy chọn này cho phép người dùng có thể/chặn tạo thư mục.</i></p>
                </div>
                <div class="form-group">
                    <label for="permission_delete_folder" class="control-label">2. Cho phép xóa thư mục</label>
                    <label class='toggle-label'>
                        <input type="checkbox" class="form-control" name="permission_delete_folder" id="permission_delete_folder" {{ !empty($permission_delete_folder) ? 'checked="checked"' : '' }} />
                        <span class="back">
                            <span class="toggle"></span>
                            <span class="label on">ON</span>
                            <span class="label off">OFF</span>
                        </span>
                    </label>
                    <p class="help-block"><i>* Diễn giải: bật/tắt tùy chọn này cho phép người dùng có thể/chặn xóa thư mục.</i></p>
                </div>
                <div class="form-group">
                    <label for="permission_rename_folder" class="control-label">3. Cho phép đổi tên thư mục</label>
                    <label class='toggle-label'>
                        <input type="checkbox" class="form-control" name="permission_rename_folder" id="permission_rename_folder" {{ !empty($permission_rename_folder) ? 'checked="checked"' : '' }} />
                        <span class="back">
                            <span class="toggle"></span>
                            <span class="label on">ON</span>
                            <span class="label off">OFF</span>
                        </span>
                    </label>
                    <p class="help-block"><i>* Diễn giải: bật/tắt tùy chọn này cho phép người dùng có thể/chặn đổi tên thư mục.</i></p>
                </div>
                <div class="form-group">
                    <label for="permission_upload_file" class="control-label">4. Cho phép tải file lên thư mục</label>
                    <label class='toggle-label'>
                        <input type="checkbox" class="form-control" name="permission_upload_file" id="permission_upload_file" {{ !empty($permission_upload_file) ? 'checked="checked"' : '' }} />
                        <span class="back">
                            <span class="toggle"></span>
                            <span class="label on">ON</span>
                            <span class="label off">OFF</span>
                        </span>
                    </label>
                    <p class="help-block"><i>* Diễn giải: bật/tắt tùy chọn này cho phép người dùng có thể/chặn tải file lên thư mục.</i></p>
                </div>
                <div class="form-group">
                    <label for="permission_delete_file" class="control-label">5. Cho phép xóa file trong thư mục</label>
                    <label class='toggle-label'>
                        <input type="checkbox" class="form-control" name="permission_delete_file" id="permission_delete_file" {{ !empty($permission_delete_file) ? 'checked="checked"' : '' }} />
                        <span class="back">
                            <span class="toggle"></span>
                            <span class="label on">ON</span>
                            <span class="label off">OFF</span>
                        </span>
                    </label>
                    <p class="help-block"><i>* Diễn giải: bật/tắt tùy chọn này cho phép người dùng có thể/chặn xóa file trong thư mục.</i></p>
                </div>
                <div class="form-group">
                    <label for="permission_rename_file" class="control-label">6. Cho phép sửa tên file trong thư mục</label>
                    <label class='toggle-label'>
                        <input type="checkbox" class="form-control" name="permission_rename_file" id="permission_rename_file" {{ !empty($permission_rename_file) ? 'checked="checked"' : '' }} />
                        <span class="back">
                            <span class="toggle"></span>
                            <span class="label on">ON</span>
                            <span class="label off">OFF</span>
                        </span>
                    </label>
                    <p class="help-block"><i>* Diễn giải: bật/tắt tùy chọn này cho phép người dùng có thể/chặn sửa tên file trong thư mục.</i></p>
                </div>
                <div class="form-group">
                    <label for="permission_extract_and_compress" class="control-label">7. Cho phép nén hoặc giải nén trong thư mục</label>
                    <label class='toggle-label'>
                        <input type="checkbox" class="form-control" name="permission_extract_and_compress" id="permission_extract_and_compress" {{ !empty($permission_extract_and_compress) ? 'checked="checked"' : '' }} />
                        <span class="back">
                            <span class="toggle"></span>
                            <span class="label on">ON</span>
                            <span class="label off">OFF</span>
                        </span>
                    </label>
                    <p class="help-block"><i>* Diễn giải: bật/tắt tùy chọn này cho phép người dùng có thể/chặn nén hoặc giải nén trong thư mục.</i></p>
                </div>
            </div>
            <div class="col-md-5 m-b-t-5">
                <legend>Cấu hình quản lý File</legend>
                <div class="form-group">
                    <label for="upload_dir" class="control-label">1. Thư mục upload</label>
                    <input class="form-control" id="upload_dir" name="upload_dir" value="{{ !empty($upload_dir) ? $upload_dir : '' }}" placeholder="Dung lượng lưu trữ tối đa..." />
                    <p class="help-block"><i>* Diễn giải: cấu hình thư mục làm nơi chứa file trong hệ thống.</i></p>
                </div>
                <div class="form-group">
                    <label for="max_size_dir" class="control-label">2. Dung lượng lưu trữ tối đa</label>
                    <div class="input-group">
                        <input class="form-control" id="max_size_dir" name="max_size_dir" value="{{ !empty($max_size_dir) ? $max_size_dir : '' }}" placeholder="Dung lượng lưu trữ tối đa..." />
                        <span class="input-group-addon">MB</span>
                    </div>
                    <p class="help-block"><i>* Diễn giải: cấu hình dung lượng tối đa người dùng được phép sử dụng trong hệ thống.</i></p>
                </div>
                <div class="form-group">
                    <label for="max_size_upload" class="control-label">3. Dung lượng tải lên tối đa mỗi file</label>
                    <div class="input-group">
                        <input class="form-control" id="max_size_upload" name="max_size_upload" value="{{ !empty($max_size_upload) ? $max_size_upload : '' }}" placeholder="Dung lượng tải lên tối đa mỗi file..." />
                        <span class="input-group-addon">MB</span>
                    </div>
                    <p class="help-block"><i>* Diễn giải: cấu hình dung lượng file tối đa cho phép tải lên mỗi lần.</i></p>
                </div>
                <div class="form-group">
                    <label for="allowed_extension" class="control-label">3. Extensions được phép tải lên</label>
                    <!-- <input class="form-control" id="allowed_extension" name="allowed_extension" value="{{ !empty($allowed_extension) ? $allowed_extension : '' }}" placeholder="Extensions được phép tải lên..." /> -->
                    <div class="well mt-5 mb-0">
                        @foreach($supported_extensions as $idx => $ext)
                        <span class="badge badge-default c_pointer hover-bg-green" data-bind="click: toolbar.toggle_extention.bind($data, '{{$ext}}'), css : {'badge-default' : !toolbar.allowed_extension().includes('{{$ext}}'), 'badge-success': toolbar.allowed_extension().includes('{{$ext}}')}">{{$ext}}</span>
                        @endforeach
                    </div>
                    <div class="text-right py-5">
                        <div class="btn btn-sm btn-success" data-bind="click: toolbar.toggle_all_extention.bind($data, 'on')"><i class="fa fa-check"></i> Bật tất cả</div>
                        <div class="btn btn-sm btn-default" data-bind="click: toolbar.toggle_all_extention.bind($data, 'off')"><i class="fa fa-times"></i> Tắt tất cả</div>
                    </div>
                    <p class="help-block"><i>* Diễn giải: cấu hình các loại file dựa theo phần mở rộng file được phép tải lên.</i></p>
                </div>
            </div>
        </div>
    </div>
</div>