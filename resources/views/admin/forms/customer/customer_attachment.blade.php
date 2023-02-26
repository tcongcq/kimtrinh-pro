<div class="col-md-5 frm-desc">
    <div class="media">
        <div class="media-left">
            <a>
                <img class="media-object" src="{{ url('assets/images/admin/content-category.png') }}" width="128">
            </a>
        </div>
        <div class="media-body">
            <h3 class="media-heading">File lưu trữ</h3>
            <p>- Quản lý các file lưu trữ của khách hàng trên hệ thống.</p>
            <p>- Cho phép thêm, xem, xoá các file của khách hàng.</p>
        </div>
    </div>
</div>
@if(\Account::has_permission(['route'=>'customer','permission_name'=>'upload-attachment']))
<div class="col-md-7">
	<legend class="mt-15">Upload storage file</legend>
    <div class="input-group">
      	<input class="form-control" id="attachment_upload_file" name="attachment_upload_file" onchange="toolbar.pick_attachment_file(event)" placeholder="Tải lên hoặc chọn file định dạng .zip cho đơn hàng" disabled />
        <span class="input-group-btn">
            <!-- ko if: toolbar.attachment_upload_file() -->
            <div class="btn btn-primary" data-bind="click: toolbar.create_attachment">
                <i class="fa fa-upload"></i> 
                <span>Tải lên</span>
            </div>
            <!-- /ko -->
            <div class="btn btn-default" onclick="open_popup('{{ url('admin/filemanager?secret='.bcrypt(env('APP_KEY')).'&fieldID=attachment_upload_file' ) }}')">
                <i class="fa fa-check-square-o"></i> 
                <span>Chọn file</span>
            </div>
      	</span>
    </div>
</div>
@endif
<div class="col-md-12 mt-15">
    <div class="row">
	<!-- ko if: toolbar.customer_attachments().length > 0 -->
        <!-- ko foreach: toolbar.customer_attachments() -->
        <div class="col-sm-4 col-md-3">
		    <div class="thumbnail">
                <!-- ko if: !toolbar.is_image($data) -->
                <div class="holder-img" data-bind="text: $data.file_extension"></div>
                <!-- /ko -->
                <!-- ko if: toolbar.is_image($data) -->
		    	<div class="holder-img c_pointer" data-bind="style: {'background-image': 'url({{url('/')}}/'+$data.thumbnail_src+')'}, click: toolbar.attachment_preview.bind($data)"></div>
                <!-- /ko -->
		    	<p class="text-center mb-0 filename-limit" data-bind="text: $data.name"></p>
		        <div class="pt-5 text-center">
		            @if(\Account::has_permission(['route'=>'customer','permission_name'=>'download-attachment']))
                    <a class="btn btn-primary btn-sm mb-2" data-bind="attr: {'href': '/'+$data.src}" download><i class="fa fa-download"></i> Tải xuống</a>
                    @endif
		            <div class="btn btn-default btn-sm mb-2" data-bind="click: toolbar.attachment_preview.bind($data)"><i class="fa fa-eye"></i> Xem</div>
                    @if(\Account::has_permission(['route'=>'customer','permission_name'=>'remove-attachment']))
                    <div class="btn btn-danger btn-sm mb-2" data-bind="click: toolbar.attachment_remove.bind($data)"><i class="fa fa-trash"></i> Xóa</div>
                    @endif
                    <div class="btn btn-info btn-sm mb-2" data-bind="click: toolbar.attachment_ref.bind($data)"><i class="fa fa-share"></i></div>
		        </div>
		    </div>
		</div>
        <!-- /ko -->
    <!-- /ko -->
	</div>
    <!-- ko if: toolbar.customer_attachments().length == 0 -->
        <div class="well well-sm text-center">Không tìm thấy file.</div>
    <!-- /ko -->
</div>