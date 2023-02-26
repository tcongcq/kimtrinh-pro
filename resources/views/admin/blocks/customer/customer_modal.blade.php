<!-- Modal import form -->
<form id="import-form">
    <div class="modal fade" id="modalImport" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Nhập dữ liệu từ file</h4>
                    <p class="help-block">(Quá trình nhập dữ liệu từ file sẽ mất vài phút...)</p>
                </div>
                <div class="modal-body row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label" for="import-file">Chọn file</label>
                            <input type="file" id="import-file" name="import-file" class="form-control" value="" required>
                            <p class="help-block">
                                Chọn file .xls hoặc .xlsx để nhập dữ liệu vào hệ thống. <br/>
                                Các dữ liệu khách sẽ được duyệt tuần tự và tự động chọn Sale như quy trình đăng ký từng khách hàng!
                            </p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="pull-right">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary" data-bind="click: toolbar.importBox"><span class="glyphicon glyphicon-import"></span> Nhập</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Modal product -->
<div class="modal fade" id="modal-product" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <legend class="mb-0">Danh Sách Dịch Vụ Khả Dụng</legend>
            </div>
            <div class="modal-body row">
                <div class="col-md-12">
                    <table class="table table-bordered table-striped table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="text-center">STT</th>
                                <th class="text-center">Code</th>
                                <th class="text-center">Tên dịch vụ</th>
                                <th class="text-center">Mô tả chi tiết</th>
                                <th class="text-center">#</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if(count($products) != 0)
                            @foreach($products as $idx => $prd)
                            <tr>
                                <td class="text-center">{{ $idx+1 }}</td>
                                <td>{{ $prd->code }}</td>
                                <td>{{ $prd->name }}</td>
                                <td>{{ $prd->description }}</td>
                                <td class="center-middle">
                                    <div class="btn btn-primary btn-sm" onclick="toolbar.add_customer_product('{{$prd->code}}')"><i class="fa fa-plus"></i> Chọn</div>
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5">Chưa có dữ liệu</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal customer product -->
<div class="modal fade" id="modal-customer-product" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <legend class="mb-0">Chi Tiết Dịch Vụ Sử Dụng</legend>
            </div>
            <div class="modal-body row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="control-label" for="cp_code">Mã dịch vụ</label>
                        <input type="text" id="cp_code" name="cp_code" class="form-control" data-bind="value: toolbar.customer_product().code" placeholder="Mã dịch vụ..." disabled />
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="cp_name">Tên dịch vụ</label>
                        <input type="text" id="cp_name" name="cp_name" class="form-control" data-bind="value: toolbar.customer_product().name" placeholder="Tên dịch vụ..." />
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="cp_description">Mô tả</label>
                        <textarea type="text" id="cp_description" name="cp_description" class="form-control" data-bind="value: toolbar.customer_product().description" placeholder="Mô tả..."></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="pull-right">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary" data-bind="click: toolbar.update_customer_product"><span class="glyphicon glyphicon-floppy-disk"></span> Cập nhật</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal reminder template -->
<div class="modal fade" id="modal-reminder-template" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <legend class="mb-0">Danh Sách Bản Mẫu Nhắc Nhở</legend>
            </div>
            <div class="modal-body row">
                <div class="col-md-12">
                    <table class="table table-bordered table-striped table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="text-center">STT</th>
                                <th class="text-center">Code</th>
                                <th class="text-center">Tên bản mẫu</th>
                                <th class="text-center">Mô tả chi tiết</th>
                                <th class="text-center">#</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if(count($reminder_templates) != 0)
                            @foreach($reminder_templates as $idx => $pt)
                            <tr>
                                <td class="text-center">{{ $idx+1 }}</td>
                                <td>{{ $pt->code }}</td>
                                <td>{{ $pt->name }}</td>
                                <td>{{ $pt->description }}</td>
                                <td class="center-middle">
                                    <div class="btn btn-primary btn-sm" onclick="toolbar.choose_reminder_template('{{$pt->id}}')"><i class="fa fa-plus"></i> Chọn bản mẫu</div>
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="text-center" colspan="5">Chưa có dữ liệu</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal assigned user -->
<div class="modal fade" id="modal-assigned-user" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <legend class="mb-0">Danh Sách Người Dùng Khả Dụng</legend>
            </div>
            <div class="modal-body row">
                <div class="col-md-12">
                    <table class="table table-bordered table-striped table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="text-center">STT</th>
                                <th class="text-center">Tên người dùng</th>
                                <th class="text-center">Số điện thoại</th>
                                <th class="text-center">Email</th>
                                <th class="text-center">#</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if(count($user_assigned_list) != 0)
                            @foreach($user_assigned_list as $idx => $user)
                            <tr>
                                <td class="text-center">{{ $idx+1 }}</td>
                                <td>{{ $user->fullname }}</td>
                                <td>{{ $user->phone }}</td>
                                <td>{{ $user->email }}</td>
                                <td class="center-middle">
                                    <div class="btn btn-primary btn-sm" onclick="toolbar.assign_user('{{$user->id}}')"><i class="fa fa-plus"></i> Chọn Người Này</div>
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="text-center" colspan="5">Chưa có dữ liệu</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
