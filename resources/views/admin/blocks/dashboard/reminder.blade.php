@foreach($reminders as $idx => $row)
<tr>
    <td>
        @if($row->customer_id != 1)
        <a class="badge badge-warning" href="customer?id={{ $row->customer_id }}" target="_blank">
        {{ leading_zero($row->customer_id) }}</a> || 
        @endif
        <span>
            {{ date('H:m d/m/Y', strtotime($row->remind_at)) }} || 
            @if($row->customer_id == 1)
            {!! type_to_text($row->type) !!}
            @else
            {!! state_to_text($row->state) !!}
            @endif
        </span> <br/>
        @if($row->customer_id != 1)
        <i class="glyphicon glyphicon-user"></i> <strong>{{ $row->customer_name }}</strong> - <strong>{{ $row->phone }}</strong> <br/>
        @endif
        <strong>Nội dung: </strong> {{ $row->name }} <br />
    </td>
    <td class="center-middle"><span class="badge badge-success c_pointer" onclick="toolbar.confirmation_reminder({{$row->id}}, {{$row->customer_id}})"><i class="fa fa-check"></i></span></td>
</tr>
@endforeach
@if(count($reminders) == 0)
<tr>
    <td colspan="2" class="text-center"><i>(Không tìm thấy dữ liệu)</i></td>
</tr>
@endif