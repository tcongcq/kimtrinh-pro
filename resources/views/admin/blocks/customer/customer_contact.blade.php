<td>{{ $nor+1 }}</td>
<td>
    <strong class="text-blue">{{ $row->customer_name }}</strong>
    <br/>- SĐT: 
        <strong>{{ $row->customer_phone }}</strong>
    <br/>
    - Người giới thiệu: {{ $row->present_name ? $row->present_name : '(không)' }}
    @if(!empty($row->present_phone))
    <br/>- SĐT: <strong>{{ $row->present_phone }}</strong>
    @endif
    <br/>
    - Ngày nhập: {{ date('H:i d/m/Y', strtotime($row->created_at)) }} <br/>
    @if(!empty($row->event_info))
    <hr style="margin: 5px 0;"/>
    <div class="form-group">
        <label for="needed_work_by_sale" class="control-label">Ghi chú công việc của Sale</label>
        <textarea type="text" class="form-control" name="needed_work_by_sale_{{ $row->id }}" id="needed_work_by_sale_{{ $row->id }}" placeholder="Ghi chú công việc của Sale...">{{ $row->event_info->needed_work_by_sale }}</textarea>
        @if($row->time_input_work_indate && date('Y-m-d', strtotime($row->time_input_work_indate)) == date('Y-m-d'))
        <div class="btn btn-primary btn-sm br-0" onclick="toolbar.update_needed_work_by_sale('needed_work_by_sale_{{ $row->id }}', {{ $row->event_id }})">Cập nhật ghi chú</div>
        @endif
    </div>
    @endif

    <legend>Lưu đơn hàng</legend>
    <div class="text-center">
        @if(!empty($row->user_sale_id))
        <a class="badge" target="_blank" style="background-color: purple;" href="{{ url('admin/event') }}?event_id={{ $row->event_id }}">#{{ $row->event_id }}</a> <br/>
        {!! state_to_text($row->event_state) !!} <br/>
        <strong class="text-success">{{ $row->user_sale->fullname }}</strong>
        @elseif(!empty($sale_chosen) && \App\Admin\User::has_permission(['route'=>'event-contact','permission_name'=>'row-to-sale']))
        <a href="#" onclick="toolbar.row_to_sale({{ $row->id }}, {{ $sale_chosen->id }}, 'OPEN')">Chuyển sale</a>
        @else
        <strong class="text-success">Liên hệ mới</strong>
        @endif
        <br/>
        @if($row->event_state == 'DRAFT' || $row->event_state == 'RUNNING')
        <div class="btn btn-warning btn-sm mb-2" onclick="toolbar.change_state({{ $row->event_id }}, 'CANCEL')">Huỷ đơn hàng</div><br/>
        <div class="btn btn-success btn-sm mb-2" onclick="toolbar.change_state({{ $row->event_id }}, 'SUCCESS')">Hợp đồng thành công</div>
        @endif
        
        @if(!empty($sale_chosen) && !$row->user_sale_id && \App\Admin\User::has_permission(['route'=>'event-contact','permission_name'=>'row-to-sale']))
        <div class="btn btn-primary btn-sm mb-2" onclick="toolbar.row_to_sale({{ $row->id }}, {{ $sale_chosen->id }})">Chuyển ngay</div> <br/>
        @endif
        @if(\App\Admin\User::has_permission(['route'=>'event-contact','permission_name'=>'delete']))
        <div class="btn btn-danger btn-sm mb-2" onclick="toolbar.delete_row({{ $row->id }})">Xoá</div>
        @endif
    </div>
</td>
<td>
    @if($row->time_input_work_indate && date('Y-m-d', strtotime($row->time_input_work_indate)) == date('Y-m-d'))
    <div class="btn btn-sm btn-primary mb-2" onclick="toolbar.note_to_row({{ $row->id }})"><i class="fa fa-plus"></i> Thêm ghi chú</div>
    @endif
    <div class="btn btn-sm btn-primary mb-2" onclick="toolbar.note_work_in_date({{ $row->id }})"><i class="fa fa-plus"></i> Thêm công việc trong ngày</div>
    @if($row->time_input_work_indate && date('Y-m-d', strtotime($row->time_input_work_indate)) == date('Y-m-d'))
    <div    class="btn btn-sm btn-info mb-2" 
            onclick="toolbar.send_sms_to_contact({{ $row->id }}, 'tn1', '{{ $row->user_sale->fullname }}', '{{ $row->user_sale->phone }}')" data-toggle="tooltip"
            title="Tin nhắn Tonykiet"><i class="fa fa-send"></i> Gửi Tin nhắn Tonykiet
    </div>
    <div    class="btn btn-sm btn-info mb-2" 
            onclick="toolbar.send_sms_to_contact({{ $row->id }}, 'tn2', '{{ $row->user_sale->fullname }}', '{{ $row->user_sale->phone }}')" data-toggle="tooltip"
            title="Tin nhắn GbrownFlower"><i class="fa fa-send"></i> Gửi Tin nhắn GbrownFlower
    </div>
    <div    class="btn btn-sm btn-info mb-2" 
            onclick="toolbar.send_sms_to_contact({{ $row->id }}, 'tn3', '{{ $row->user_sale->fullname }}', '{{ $row->user_sale->phone }}')" data-toggle="tooltip"
            title="Tin nhắn Tinnahuyen"><i class="fa fa-send"></i> Gửi Tin nhắn Tinnahuyen
    </div>
    <div    class="btn btn-sm btn-info mb-2" 
            onclick="toolbar.send_sms_to_contact({{ $row->id }}, 'tn4', '{{ $row->user_sale->fullname }}', '{{ $row->user_sale->phone }}')" data-toggle="tooltip"
            title="Tin nhắn Simontu Production"><i class="fa fa-send"></i> Gửi Tin nhắn Simontu Production
    </div>
    <div    class="btn btn-sm btn-info mb-2" 
            onclick="toolbar.send_sms_to_contact({{ $row->id }}, 'tn5', '{{ $row->user_sale->fullname }}', '{{ $row->user_sale->phone }}')" data-toggle="tooltip"
            title="Tin nhắn Hình ảnh thực tế Gbrown Flower"><i class="fa fa-send"></i> Gửi Tin nhắn Hình ảnh thực tế GbrownFlower
    </div>
    <div    class="btn btn-sm btn-info mb-2" 
            onclick="toolbar.send_sms_to_contact({{ $row->id }}, 'tn6', '{{ $row->user_sale->fullname }}', '{{ $row->user_sale->phone }}')" data-toggle="tooltip"
            title="Tin nhắn Hình ảnh thực tế Tony Kiệt"><i class="fa fa-send"></i> Gửi Tin nhắn Hình ảnh thực tế Tony Kiệt
    </div>
    @endif
    <br/>
    <div style="width: 500px; overflow: auto;">
    {!! $row->note ? '- '.$row->note.'<br/>' : '' !!}
    @if($row->admin_note)
        <span class="text-danger">*** Ghi chú phân công:</span> <br/>
        {!! $row->admin_note !!}
    @endif
    @if($row->work_in_date)
        <br/>
        <span class="text-danger">*** Công việc trong ngày:</span> <br/>
        {!! $row->work_in_date !!}
    @endif
    </div>
</td>
<td>
    @if($row->time_input_work_indate && date('Y-m-d', strtotime($row->time_input_work_indate)) == date('Y-m-d'))
    <div class="btn btn-sm btn-info" onclick="toolbar.fetch_reminder_list({{ $row->event_id }})"><i class="fa fa-plus"></i> Thêm nhắc nhở</div>
    @endif
    <legend>Nhắc nhở</legend>
    <div class="reminder" style="background-color: #fff; max-width: 500px;">
        <div class="list">
            <ul class="message-list">
                @foreach($row->task_reminders as $reminder)
                <li>
                    <span class="text-bold text-purple">{{ !empty($reminder->reminder_user_info) ? $reminder->reminder_user_info->fullname : '' }}</span>
                    @if($reminder->approveed == 1)
                    <label class="label label-success">Đã xác nhận</label>
                    @else
                    <label class="label label-default">Chưa xác nhận</label>
                    @endif
                    <div class="title">
                        <span class="{{ ($reminder->approveed == 0 && $reminder->remind_at < date('Y-m-d 00:00:00')) ? 'text-red' : '' }}">- Nội dung: 
                            <strong>{!! $reminder->name !!}</strong>
                        </span>
                        <span class="date">{{ date('d/m/Y H:i', strtotime($reminder->remind_at)) }}</span>
                    </div>
                    @if($reminder->approveed == 1)
                    <div class="content">- Nội dung xác nhận: <span>{{ $reminder->approved_note }}</span></div>
                    @endif
                    @if(!empty($reminder->note))
                    <div class="content">- Nội dung chuyển ngày: <span>{{ $reminder->note }}</span></div>
                    @endif

                    @if($row->time_input_work_indate && date('Y-m-d', strtotime($row->time_input_work_indate)) == date('Y-m-d'))
                    <div class="three-dot" onclick="$(this).find('+ul.message-control').toggleClass('show')">...</div>
                    <ul class="message-control">
                        <li onclick="toolbar.reminder_approve({{ $reminder->id }}, {{ $reminder->event_id }}, {{ $row->id }})" class="{{ $reminder->approveed == 0 ? '' : 'hidden' }}">Xác nhận <i class="fa fa-check"></i></li>
                    </ul>
                    @endif
                </li>
                @endforeach
            </div>
        </div>
    </div>
    <legend class="mt-5">Chat tổng</legend>
    <div class="msg" style="background-color: #fff; max-width: 500px;">
        <div class="list">
            <ul class="message-list">
                @foreach($row->messages as $ml)
                <li>
                    <div class="title">
                        <strong>{{ $ml->message_user }}</strong>
                        <span class="date">{{ $ml->message_date }}</span>
                    </div>
                    <div class="content">{!! $ml->content !!}</div>
                </li>
                @endforeach
                @if( count($row->messages) == 0 )
                <li>Trống</li>
                @endif
            </ul>
        </div>
        <div class="grp-inp">
            <input class="inp-text" data-row-id="{{ $row->id }}" onkeyup="toolbar.text_input_keypress(event, {{ $row->event_id }}, this)" placeholder="Nhập lời nhắn ở đây..." />
            <i class="fa fa-send"></i>
        </div>
    </div>
</td>