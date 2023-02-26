<div class="stat-container">
    <div class="stat-main" style="padding: 15px; overflow: auto; max-height: 450px;">
        <div style="max-width: 1500px;">
            <legend class="text-center">
                Lịch nhắc từ ngày 
                    <span class="text-red">{{ date('d/m/Y', strtotime(array_get_default($input_data, 'from_date', ''))) }}</span>
                đến ngày
                    <span class="text-red">{{ date('d/m/Y', strtotime(array_get_default($input_data, 'to_date', ''))) }}</span>
                @if(!empty($input_data['reminder_user_ids']))
                <br/> của nhân viên
                    @foreach(array_get_default($input_data, 'reminder_user_ids', []) as $idx => $user_id)
                        {{ ($idx != 0) ? ' và ' : '' }} <span class="text-orange">{{ \App\Admin\User\User::find($user_id)->fullname }}</span>
                    @endforeach
                @endif
                @if(!empty($input_data['approveeds']))
                <br/> có trạng thái
                    @foreach(array_get_default($input_data, 'approveeds', []) as $idx => $approved)
                        {{ ($idx != 0) ? ' và ' : '' }}
                        @if($approved == 1)
                        <span class="badge badge-success">Đã xác nhận(thực hiện)</span>
                        @else
                        <span class="badge badge-default">Chưa xác nhận(thực hiện)</span>
                        @endif
                    @endforeach
                @endif
            </legend>
            Đã thực hiện: <span class="text-bold text-purple">{{ $approved_reminders }}</span>/<span class="text-bold">{{ count($reminders) }}</span> nhắc nhở <br/>
            Chưa thực hiện: <span class="text-bold text-red">{{ $un_approved_reminders }}</span>/<span class="text-bold">{{ count($reminders) }}</span> nhắc nhở <br/>
            <table class="table table-bordered table-hover" style="margin-bottom: 0px;">
                <thead>
                    <tr style="background-color: #f0ad4e; color: #fff;">
                        <th style="padding: 3px 5px; text-align: center; vertical-align: middle;">MSHĐ</th>
                        <th style="padding: 3px 5px; text-align: center; vertical-align: middle;">Đơn hàng</th>
                        <th style="padding: 3px 5px; text-align: center; vertical-align: middle;">Nhắc nhở</th>
                        <th style="padding: 3px 5px; text-align: center; vertical-align: middle;">#</th>
                    </tr>
                </thead>
                <tbody>
                @if(count($reminders))
                    @foreach($reminders as $nOr => $reminder)
                    <?php
                        $date_now   = date('Y-m-d 00:00:00');
                        $date_2     = change_the_date($date_now, '-3');
                    ?>
                    @if(($reminder->remind_at < $date_now) && ($reminder->approveed == 0))
                    <tr style="background-color: #aaa;">
                    @else
                    <tr>
                    @endif
                        <td style="padding: 3px 5px; text-align: center; vertical-align: middle; 
                            @if($reminder->priority == 1)
                            background: #f0ad4e;
                            @endif
                            ">
                            <a class="badge" style="background-color: ;" href="{{ url('admin/customer?id='.$reminder->customer_id) }}" target="_blank">{{ $nOr+1 }}</a> <br/>
                            <a class="badge" style="background-color: #f0ad4e;" href="{{ url('admin/customer?id='.$reminder->customer_id) }}" target="_blank">{{ leading_zero($reminder->customer_id) }}</a>
                        </td>
                        <td style="padding: 3px 5px;">
                            @if($reminder->customer_id != 1)
                            <div>
                                <i class="glyphicon glyphicon-info-sign"></i><strong> {{ !empty($reminder->brand_name) ? $reminder->brand_name : '(trống)' }}</strong>
                            </div>
                            <div>
                                <i class="glyphicon glyphicon-user"></i><span> {{ $reminder->customer_name }}</span>
                            </div>
                            <div>
                                <i class="glyphicon glyphicon-phone-alt"></i><span> {{ $reminder->phone }}</span> <br />
                            </div>
                            @endif
                            <div>
                                <strong class="pull-right">
                                    @if($reminder->customer_id == 1)
                                    {!! type_to_text($reminder->type) !!}
                                    @else
                                    {!! state_to_text($reminder->state) !!}
                                    @endif
                                </strong> <br />
                            </div>
                        </td>
                        <td style="padding: 3px 5px; position: relative;">
                            <div>
                                <strong>Nhắc nhở:</strong> {{ $reminder->name }}
                            </div>
                            <div>
                                @if(\Account::has_permission(['route'=>'customer-reminder','permission_name'=>'access-all']))
                                - Người thực hiện: <strong>{{ $reminder->ru_fullname }}</strong>
                                @endif
                            </div>
                            <div>
                                - Thời gian: <strong>{{ date('d/m/Y H:m', strtotime($reminder->remind_at)) }}</strong> || 
                                @if($reminder->approveed == 1)
                                <label class="label label-success">Đã xác nhận</label>
                                @elseif($reminder->approveed == 0)
                                <label class="label label-default">Chưa xác nhận</label>
                                @endif
                            </div>
                            @if(!empty($reminder->approved_note))
                            <div>
                                - Nội dung xác nhận: {{ $reminder->approved_note }}
                            </div>
                            @endif
                            @if(!empty($reminder->note))
                            <div>
                                - Nội dung chuyển ngày: {{ $reminder->note }}
                            </div>
                            @endif
                            @if($reminder->approveed == 0)
                                <div class="btn btn-sm btn-success mt-2" title="Xác nhận đã hoàn thành nhắc nhở" onclick="toolbar.confirmation_reminder({{ $reminder->id }})"><i class="fa fa-check"></i> Hoàn thành</div>
                                @if($reminder->priority != 1)
                                <div class="btn btn-sm btn-info mt-2" title="Chuyển nhắc nhở sang thời ian khác" onclick="toolbar.change_date({{ $reminder->id }})"><i class="fa fa-calendar-times-o"></i> Chuyển ngày</div>
                                @endif
                            @endif
                        </td>
                        <td style="padding: 3px 5px; text-align: center; vertical-align: middle;">
                            <div class="btn btn-default mt-2" onclick="toolbar.show_chat({{ $reminder->customer_id }}, {{ $reminder->id }})"><i class="fa fa-pencil"></i></div>
                        </td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5"><center>Trống</center></td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>