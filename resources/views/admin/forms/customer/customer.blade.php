<div>
    <ul class="nav nav-tabs nav-justified" role="tablist">
        <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab"><i class="fa fa-tachometer" aria-hidden="true"></i> Tổng quan</a></li>
        <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab"><i class="fa fa-info-circle" aria-hidden="true"></i> Thông tin khách hàng</a></li>
        <li role="presentation"><a href="#products" aria-controls="products" role="tab" data-toggle="tab"><i class="fa fa-product-hunt" aria-hidden="true"></i> Thông tin hợp tác</a></li>
        <li role="presentation" data-bind="visible: method()=='update'"><a href="#attachments" aria-controls="attachments" role="tab" data-toggle="tab"><i class="fa fa-file-text-o" aria-hidden="true"></i> File lưu trữ</a></li>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane py-10 row active" id="home">
            @include("admin.forms.customer.customer_dashboard")
        </div>
        <div role="tabpanel" class="tab-pane py-10 row" id="profile">
            @include("admin.forms.customer.customer_profile")
        </div>
        <div role="tabpanel" class="tab-pane py-10 row" id="products">
            @include("admin.forms.customer.customer_product")
        </div>
        <div role="tabpanel" class="tab-pane py-10 row" id="attachments" data-bind="visible: method()=='update'">
            @include("admin.forms.customer.customer_attachment")
        </div>
    </div>
</div>
