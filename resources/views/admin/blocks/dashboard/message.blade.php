<style type="text/css">
.msg-modal .list {
    height: 330px;
}
</style>
<div class="msg msg-modal">
    <div class="list">
        <ul class="message-list customer-message">
            @foreach($messages as $ml)
            <li>
                <div class="title">
                    <strong>{{ $ml->fullname }}</strong>
                    <span class="date">{{ $ml->created_at }}</span>
                </div>
                <div class="content">{!! $ml->content !!}</div>
            </li>
            @endforeach
            @if( count($messages) == 0 )
            <li>Trống</li>
            @endif
        </ul>
    </div>
    <div class="grp-inp">
        <input class="inp-text" id="inp-text-message" onkeyup="toolbar.message_create(event, '{{ $customer_id }}')" placeholder="Nhập lời nhắn ở đây..." />
        <i class="fa fa-send"></i>
    </div>
</div>
