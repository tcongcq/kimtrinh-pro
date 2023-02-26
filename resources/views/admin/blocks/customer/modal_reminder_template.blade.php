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
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Đóng</button>
                <button type="button" class="btn btn-primary" onclick="toolbar.choose_reminder_template()"><i class="fa fa-forward"></i> Bỏ qua</button>
            </div>
        </div>
    </div>
</div>
