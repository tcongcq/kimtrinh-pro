<script type="text/html" id="filters-toolbar">
    <div class="grid-filter-container filter pull-left">
        <div class="btn-group">
            <button data-toggle="tooltip" title="Lọc dữ liệu" class="btn btn-default grid-filter-btn" style="margin-left: 5px;" onclick="$('.grid-filter-container.filter').toggleClass('open');">
                <span class="glyphicon glyphicon-filter"></span> Lọc
            </button>
        </div>
        <div class="grid-filter-body">
            <div class="row">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Trạng thái khách hàng</label>
                                <select class="selectpicker show-tick form-control" filter-colname="state" filter-operator="in" data-live-search="true" multiple data-selected-text-format="count > 3" data-actions-box="true">
                                    <option value="POTENTIAL" data-content="<span class='label label-success'>1. POTENTIAL</span>">POTENTIAL</option>
                                    <option value="COOPERATED" data-content="<span class='label label-danger'>2. COOPERATED</span>">COOPERATED</option>
                                    <option value="APPROACHING" data-content="<span class='label label-primary'>3. APPROACHING</span>">APPROACHING</option>
                                    <option value="CANCELLED" data-content="<span class='label label-default'>4. CANCELLED</span>">CANCELLED</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Nhánh</label>
                                <select class="selectpicker show-tick form-control" filter-colname="branch" filter-operator="in" data-live-search="true" multiple data-selected-text-format="count > 3" data-actions-box="true">
                                    <option value="Business Household">BUSINESS HOUSEHOLD</option>
                                    <option value="Enterprise">ENTERPRISE</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Ngành hàng</label>
                                <select class="selectpicker show-tick form-control" filter-colname="field" filter-operator="in" data-live-search="true" multiple data-selected-text-format="count > 3" data-actions-box="true">
                                    <option value="BEAUTY">BEAUTY</option>
                                    <option value="FOOD">FOOD</option>
                                    <option value="FASHION">FASHION</option>
                                    <option value="LIFESTYLE">LIFESTYLE</option>
                                    <option value="AGENCY">AGENCY</option>
                                    <option value="ORTHERS">ORTHERS</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Nguồn khai thác</label>
                                <select class="selectpicker show-tick form-control" filter-colname="source" filter-operator="in" data-live-search="true" multiple data-selected-text-format="count > 3" data-actions-box="true">
                                    <option value="PERSONAL-DATA">PERSONAL-DATA</option>
                                    <option value="REFERENCED">REFERENCED</option>
                                    <option value="ASSIGNED">ASSIGNED</option>
                                    <option value="NEW-APPROACH">NEW-APPROACH</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Tình trạng hợp đồng</label>
                                <select class="selectpicker show-tick form-control" filter-colname="contract_state" filter-operator="in" data-live-search="true" multiple data-selected-text-format="count > 3" data-actions-box="true">
                                    <option value="REVIEWING">REVIEWING</option>
                                    <option value="SIGNED">SIGNED</option>
                                    <option value="COMPLETED">COMPLETED</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Tình trạng thanh toán</label>
                                <select class="selectpicker show-tick form-control" filter-colname="payment_state" filter-operator="in" data-live-search="true" multiple data-selected-text-format="count > 3" data-actions-box="true">
                                    <option value="UNDUE">UNDUE</option>
                                    <option value="1ST-PAID">1ST-PAID</option>
                                    <option value="DONE">DONE</option>
                                    <option value="IN-DEPT">IN-DEPT</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="from_date" class="control-label"><small>Từ ngày (chuyển trạng thái)</small></label>
                                <div class="input-group only-date" id="from_date">
                                    <input type="text" class="form-control filter_date" filter-colname="state_changed_at" filter-operator=">=" />
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="to_date" class="control-label"><small>Đến ngày (chuyển trạng thái)</small></label>
                                <div class="input-group only-date" id="to_date">
                                    <input type="text" class="form-control filter_date" filter-colname="state_changed_at" filter-operator="<=" />
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Khách hàng ưu tiên</label>
                                <select class="selectpicker show-tick form-control" filter-colname="priority" filter-operator="in" multiple data-actions-box="true">
                                    <option value="NET" data-content="<span class='label label-warning'>Khách ưu tiên</span>">Khách ưu tiên</option>
                                    <option value="NONE" data-content="<span class='label label-default'>Khách bình thường</span>">Khách bình thường</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Sale PIC</label>
                                <select class="selectpicker show-tick form-control" filter-colname="user_assigned_id" filter-operator="in" data-live-search="true" multiple data-selected-text-format="count > 3" data-actions-box="true">
                                @foreach(\App\Admin\Customer\Customer::get_users() as $k => $user)
                                    <option value="{{ $user->id }}">{{ $user->fullname }} ({{ $user->phone }})</option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Dịch vụ sử dụng</label>
                                <select class="selectpicker show-tick form-control" filter-colname="customer_product_codes" filter-operator="in" data-live-search="true" multiple data-selected-text-format="count > 3" data-actions-box="true">
                                @foreach(\App\Admin\Resource\Product::get() as $k => $cp)
                                    <option value="{{ $cp->code }}">{{$k+1}}. {{ $cp->name }}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div style="margin-top: 25px;">
                                <div class="btn btn-primary" data-bind="click: toolbar.grid_filters">
                                    <span class="glyphicon glyphicon-filter"></span> Lọc
                                </div>
                                <div class="btn btn-default" style="margin-left: 5px;" data-bind="click: toolbar.grid_unfilters">
                                    <span class="glyphicon glyphicon-remove"></span> Bỏ lọc tất cả
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="bs-callout bs-callout-info">
                        <h4>Lọc dữ liệu</h4>
                        <p>Bạn có thể dễ dàng lọc thông tin các tiệc theo nhiều tiêu chí khác nhau:</p>
                        <ul style="padding-left: 15px;">
                            <li>Trạng thái khách hàng</li>
                            <li>Nhánh / Ngành hàng / Nguồn khai thác</li>
                            <li>Tình trạng hợp đồng / Tình trạng thanh toán</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="btn-group ml-5">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        @if(\Request::has('state'))
            Trạng thái: {!! state_to_text(\Request::get('state')) !!}
        @else
            Lọc trạng thái
        @endif
             <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li><a href="?{{ \Request::has('user_assigned_id') ? '&user_assigned_id='.\Request::get('user_assigned_id') : '' }}">-- Bỏ Lọc --</a></li>
            <li><a href="?state=POTENTIAL{{ \Request::has('user_assigned_id') ? '&user_assigned_id='.\Request::get('user_assigned_id') : '' }}"><span class="label label-success">POTENTIAL</span></a></li>
            <li><a href="?state=COOPERATED{{ \Request::has('user_assigned_id') ? '&user_assigned_id='.\Request::get('user_assigned_id') : '' }}"><span class="label label-danger">COOPERATED</span></a></li>
            <li><a href="?state=APPROACHING{{ \Request::has('user_assigned_id') ? '&user_assigned_id='.\Request::get('user_assigned_id') : '' }}"><span class="label label-primary">APPROACHING</span></a></li>
            <li><a href="?state=CANCELLED{{ \Request::has('user_assigned_id') ? '&user_assigned_id='.\Request::get('user_assigned_id') : '' }}"><span class="label label-default">CANCELLED</span></a></li>
        </ul>
    </div>
    @if(\Account::has_permission(['route'=>'customer','permission_name'=>'import']))
    <div class="btn btn-default ml-5" onclick="$('#modalImport').modal('show')">
        <i class="fa fa-upload" aria-hidden="true"></i> Import file
    </div>
    @endif
</script>