<style type="text/css">
    .table>tr>th{
        padding: 3px 5px;
        text-align: center;
        vertical-align: middle;
    }
    .table>tr>td{
        padding: 3px 5px;
        text-align: left;
        vertical-align: top;
    }
    .mb-2{
        margin-bottom: 2px;
    }
</style>
<div class="stat-container">
    <div class="stat-main" style="padding: 15px;overflow: auto;">
        <div style="font-weight: 700;font-size: 15px;text-align: center; margin-bottom: 0px">
            DANH SÁCH LIÊN HỆ ĐẶT TIỆC<br/>
            @if($from_date == $to_date)
            NGÀY <span class="text-blue">{{ $from_date }}</span>
            @else
            TỪ NGÀY <span class="text-blue">{{ $from_date }}</span> ĐẾN <span class="text-purple">{{ $to_date }}</span>
            @endif
        </div>
        <div style="font-size: 14px;text-align: center; margin-bottom: 10px">
            <i>(Có <strong class="text-danger">{{ $data['count_free_row'] }} liên hệ</strong> chưa thực hiện chuyển sale)</i>
        </div>
        <div class="text-left" style="margin-bottom: 10px;">
            - Tổng cộng: <strong class="text-blue">{{ count($data['rows']) }}</strong> đơn hàng. <br/>
            - Báo báo đơn hàng: <br/>
            @foreach($states as $state)
            <?php
                $count_state = 0;
                foreach($data['rows'] as $nor => $row){
                    if ($row->state == $state)
                        $count_state++;
                }
            ?>
            + Trạng thái {!! state_to_text($state) !!}: <strong class="text-{{ $count_state == 0 ? '' : 'red' }}">{{ $count_state }}</strong> đơn hàng <br/>
            @endforeach
        </div>
        <table class="table table-bordered">
            <thead>
                <tr style="font-weight: 700">
                    <th>#</th>
                    <th>Khách hàng</th>
                    <th>Ghi chú</th>
                    <th>Nhắc nhở và chat</th>
                </tr>
            </thead>
            <?php
                $sale_chosen = $data['sale_chosen'];
            ?>
            <tbody>
                @foreach($data['rows'] as $nor => $row)
                    <tr id="tr-index-{{ $row->id }}" data-nor="{{ $nor }}">
                        @include('admin.blocks.customer.customer_contact')
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>