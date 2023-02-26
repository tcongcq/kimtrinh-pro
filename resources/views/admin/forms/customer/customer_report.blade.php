<div class="stat-container">
    <div class="stat-main" style="padding: 15px; overflow: auto; max-height: 450px;">
        <div style="max-width: 1500px;">
            <legend class="text-center">
                Báo cáo thống kê từ ngày
                    <span class="text-red" data-bind="text: date_format(rInputData().from_date)"></span>
                đến ngày
                    <span class="text-red" data-bind="text: date_format(rInputData().to_date)"></span>
                <!-- ko if: rInputData().reminder_users -->
                <br/> của nhân viên
                	<span data-bind="foreach: rInputData().reminder_users">
                		<span data-bind="visible: $index() != 0"> và </span>
                		<span class="text-orange" data-bind="text: fullname"></span>
                	</span>
                <!-- /ko -->
            </legend>
            <table class="table table-bordered table-hover" style="margin-bottom: 0px;">
                <thead>
                    <tr style="background-color: #f0ad4e; color: #fff;">
                        <th style="padding: 3px 5px; text-align: center; vertical-align: middle;">STT</th>
                        <th style="padding: 3px 5px; text-align: center; vertical-align: middle;">Tiêu đề</th>
                        <th style="padding: 3px 5px; text-align: center; vertical-align: middle;">Thông tin</th>
                        <th style="padding: 3px 5px; text-align: center; vertical-align: middle;">#</th>
                    </tr>
                </thead>
                <tbody>
                	<!-- ko if: statResult().input_data != undefined -->
                	<tr>
                		<td class="center-middle"><span class="badge badge-warning c_pointer">1</span></td>
                		<td>Khách hàng Potential đang chăm sóc</td>
                		<td class="center-middle"><strong data-bind="text: statResult().potential_customers.length"></strong></td>
                		<td class="center-middle"><div class="btn btn-sm btn-primary" data-bind="click: show_detail.bind($data, statResult().potential_customers, 'customer')">Xem chi tiết</div></td>
                	</tr>
                	<tr>
                		<td class="center-middle"><span class="badge badge-warning c_pointer">2</span></td>
                		<td>Nhắc nhở đã thực hiện</td>
                		<td class="center-middle"><strong data-bind="text: statResult().approved_reminders.length"></strong></td>
                		<td class="center-middle"><div class="btn btn-sm btn-primary" data-bind="click: show_detail.bind($data, statResult().approved_reminders, 'reminder')">Xem chi tiết</div></td>
                	</tr>
                	<tr>
                		<td class="center-middle"><span class="badge badge-warning c_pointer">3</span></td>
                		<td>Nhắc nhở chưa thực hiện</td>
                		<td class="center-middle"><strong data-bind="text: statResult().un_approved_reminders.length"></strong></td>
                		<td class="center-middle"><div class="btn btn-sm btn-primary" data-bind="click: show_detail.bind($data, statResult().un_approved_reminders, 'reminder')">Xem chi tiết</div></td>
                	</tr>
                	<tr>
                		<td class="center-middle"><span class="badge badge-warning c_pointer">4</span></td>
                		<td>Khách hàng đã nhập</td>
                		<td class="center-middle"><strong data-bind="text: statResult().input_contacts.length"></strong></td>
                		<td class="center-middle"><div class="btn btn-sm btn-primary" data-bind="click: show_detail.bind($data, statResult().input_contacts, 'contact')">Xem chi tiết</div></td>
                	</tr>
                	<!-- /ko -->
                	<!-- ko if: statResult().input_data == undefined -->
                    <tr>
                        <td colspan="4"><center>Trống</center></td>
                    </tr>
                	<!-- /ko -->
                </tbody>
            </table>
        </div>
    </div>
</div>