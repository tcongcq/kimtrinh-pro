<div class="col-md-5 frm-desc">
    <div class="media">
        <div class="media-left">
            <a>
                <img class="media-object" src="{{ url('assets/images/admin/content-category.png') }}" width="128">
            </a>
        </div>
        <div class="media-body">
            <h3 class="media-heading">Thông tin hợp tác</h3>
            <p>- Quản lý các dịch vụ và thông tin về tình trạng hợp đồng trên hệ thống.</p>
            <p>- Cho phép thêm, xem, xoá các dịch vụ khách hàng sử dụng.</p>
        </div>
    </div>
</div>
<div class="col-md-7">
    <legend class="mt-15">Thông tin thanh toán</legend>
    <div class="row">
        <div class="col-md-6" data-bind="visible: toolbar.state() == 'COOPERATED'">
            <div class="form-group">
                <label for="contract_state" class="control-label">Tình trạng hợp đồng</label>
                <select class="form-control selectpicker" name="contract_state" id="contract_state">
                    <option value="REVIEWING">REVIEWING</option>
                    <option value="SIGNED">SIGNED</option>
                    <option value="COMPLETED">COMPLETED</option>
                </select>
            </div>
            <div class="form-group">
                <label for="payment_state" class="control-label">Tình trạng thanh toán</label>
                <select class="form-control selectpicker" name="payment_state" id="payment_state">
                    <option value="UNDUE">UNDUE</option>
                    <option value="1ST-PAID">1ST-PAID</option>
                    <option value="DONE">DONE</option>
                    <option value="IN-DEPT">IN-DEPT</option>
                </select>
            </div>
        </div>
        <div class="col-md-6" data-bind="visible: method() == 'update'">
            <div class="form-group">
                <label class="control-label">Tên công ty</label>
                <input type="text" class="form-control" data-bind="value: current().company_name" placeholder="Tên công ty..." disabled />
            </div>
            <div class="form-group">
                <label class="control-label">Tên khách hàng</label>
                <input type="text" class="form-control" data-bind="value: current().name" placeholder="Tên khách hàng..." disabled />
            </div>
        </div>
    </div>
</div>
<div class="col-md-12" data-bind="visible: method() == 'update'">
    <hr/>
    <legend>Danh sách dịch vụ sử dụng
        @if(\Account::has_permission(['route'=>'customer','permission_name'=>'create-item']))
        <span class="btn btn-primary btn-refresh ml-5" data-bind="click: toolbar.show_customer_products">
            <i class="fa fa-plus"></i></span>
        @endif
    </legend>
    <table class="table table-bordered table-striped table-hover">
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
        <!-- ko if: toolbar.customer_products().length > 0 -->
            <!-- ko foreach: toolbar.customer_products() -->
            <tr>
                <td class="text-center"><a href="#"
                    @if(\Account::has_permission(['route'=>'customer','permission_name'=>'update-item']))
                    data-bind="click: toolbar.show_customer_product.bind($data, $rawData)
                    @endif
                    "><span data-bind="text: $index()+1"></span></a></td>
                <td data-bind="text: $data.code"></td>
                <td data-bind="text: $data.name"></td>
                <td data-bind="html: $data.description"></td>
                <td class="center-middle">
                    @if(\Account::has_permission(['route'=>'customer','permission_name'=>'delete-item']))
                    <div class="btn btn-danger btn-sm" data-bind="click: toolbar.delete_customer_product.bind($data, $rawData)"><i class="fa fa-trash"></i> Xoá</div>
                    @endif
                </td>
            </tr>
            <!-- /ko -->
        <!-- /ko -->
        <!-- ko if: toolbar.customer_products().length == 0 -->
            <tr>
                <td colspan="8">Chưa có hạng mục nào</td>
            </tr>
        <!-- /ko -->
        </tbody>
    </table>
</div>