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
                                    <div class="btn btn-primary btn-sm" onclick="toolbar.assign_user('{{$user->id}}')"><i class="fa fa-plus"></i> Chọn</div>
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
