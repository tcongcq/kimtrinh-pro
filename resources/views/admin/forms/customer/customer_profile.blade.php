<div class="col-md-4 frm-desc">
    <div class="media">
        <div class="media-left">
            <a>
                <img class="media-object" src="{{ url('assets/images/admin/work-list.png') }}" width="128">
            </a>
        </div>
        <div class="media-body">
            <h3 class="media-heading">Khách hàng</h3>
            <p>- Quản lý thông tin đơn của khách hàng trên hệ thống.</p>
            <p>- Cho phép thêm, sửa, xoá thông tin các đơn hàng.</p>
        </div>
    </div>
    <div class="form-group mt-5 pt-2">
        <label for="state" class="control-label">Trạng thái đơn hàng</label>
        <select class="form-control selectpicker" name="state" id="state">
            @foreach(config('cms.customer_state') as $idx => $state)
                <option value="{{ $state }}" data-content="{!! state_to_text($state) !!}" >{{ $idx+1 }} {!! state_to_text($state) !!}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group pt-2">
        <label for="describe" class="control-label">Nhu cầu hợp tác</label>
        <textarea rows="8" class="form-control" name="describe" id="describe" data-bind="value: current().describe" placeholder="Nhu cầu hợp tác..."></textarea>
    </div>
</div>
<div class="col-md-8">
    <legend>Thông tin cơ bản</legend>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="company_name" class="control-label">Tên công ty</label>
                <input type="text" class="form-control" name="company_name" id="company_name" data-bind="value: current().company_name" placeholder="Tên công ty..." />
            </div>
            <div class="form-group">
                <label for="brand_name" class="control-label">Tên thương hiệu</label>
                <input type="text" class="form-control" name="brand_name" id="brand_name" data-bind="value: current().brand_name" placeholder="Tên thương hiệu..." />
            </div>
            <div class="form-group">
                <label for="company_address" class="control-label">Địa chỉ công ty</label>
                <input type="text" class="form-control" name="company_address" id="company_address" data-bind="value: current().company_address" placeholder="Địa chỉ công ty..." />
            </div>
            <div class="form-group">
                <label for="link" class="control-label">Website / Facebook</label>
                <div class="input-group">
                    <input type="text" class="form-control" name="link" id="link" data-bind="value: current().link" placeholder="Website / Facebook..." />
                    <span class="input-group-btn">
                        <div class="btn btn-default" data-bind="click: toolbar.copyToClipboard.bind($data, current().link)"><i class="fa fa-clipboard" aria-hidden="true"></i></div>
                    </span>
                </div>
            </div>
            <div class="form-group">
                <label for="branch" class="control-label">Nhánh</label>
                <select class="form-control selectpicker" name="branch" id="branch">
                    <option value="Enterprise">ENTERPRISE</option>
                    <option value="SMEs">SMEs</option>
                </select>
            </div>
            <div class="form-group">
                <label for="field" class="control-label">Ngành hàng</label>
                <select class="form-control selectpicker" name="field" id="field">
                    <option value="AGENCY">AGENCY</option>
                    <option value="FMCG">FMCG</option>
                    <option value="REAL-ESTATE">REAL ESTATE</option>
                    <option value="EDUCATION">EDUCATION</option>
                    <option value="BEAUTY">BEAUTY</option>
                    <option value="FASHION">FASHION</option>
                    <option value="RETAIL">RETAIL</option>
                    <option value="TECH">TECH</option>
                    <option value="FINANCE">FINANCE</option>
                    <option value="ORTHERS">ORTHERS</option>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="name" class="control-label">Tên khách hàng <sup class="text-danger">(*)</sup></label>
                <input type="text" class="form-control" name="name" id="name" data-bind="value: current().name" placeholder="Tên khách hàng..." required />
            </div>
            <div class="form-group">
                <label for="display_name" class="control-label">Tên gợi nhớ</label>
                <input type="text" class="form-control" name="display_name" id="display_name" data-bind="value: current().display_name" placeholder="Tên gợi nhớ..." />
            </div>
            <div class="form-group">
                <label for="position" class="control-label">Chức vụ</label>
                <input type="text" class="form-control" name="position" id="position" data-bind="value: current().position" placeholder="Chức vụ..." />
            </div>
            <div class="form-group">
                <label for="phone" class="control-label">Số điện thoại</label>
                <div class="input-group">
                    <input type="text" class="form-control" name="phone" id="phone" data-bind="value: current().phone" placeholder="Số điện thoại..." />
                    <span class="input-group-btn">
                        <div class="btn btn-default" data-bind="click: toolbar.copyToClipboard.bind($data, current().phone)"><i class="fa fa-clipboard" aria-hidden="true"></i></div>
                    </span>
                </div>
            </div>
            <div class="form-group">
                <label for="email" class="control-label">Email</label>
                <div class="input-group">
                    <input type="text" class="form-control" name="email" id="email" data-bind="value: current().email" placeholder="Email..." />
                    <span class="input-group-btn">
                        <div class="btn btn-default" data-bind="click: toolbar.copyToClipboard.bind($data, current().email)"><i class="fa fa-clipboard" aria-hidden="true"></i></div>
                    </span>
                </div>
            </div>
            <div class="form-group">
                <label for="source" class="control-label">Nguồn khai thác</label>
                <select class="form-control selectpicker" name="source" id="source">
                    <option value="PERSONAL-DATA">PERSONAL-DATA</option>
                    <option value="REFERENCED">REFERENCED</option>
                    <option value="ASSIGNED">ASSIGNED</option>
                    <option value="NEW-APPROACH">NEW-APPROACH</option>
                </select>
            </div>
        </div>
    </div>
</div>