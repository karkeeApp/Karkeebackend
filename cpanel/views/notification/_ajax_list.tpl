@{
	use yii\helpers\Url;
	use common\widgets\PaginationWidget;	
	use common\models\Notification;
}

<div class="table-responsive">
<table class="table">
	<thead>
		<tr>
			<th>Date</th>
			<th>Title</th>
			<th>Recipient</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
		@if(!$notifications) {
			<tr>
				<td colspan="99">No notifications found.</td>
			</tr>
		} else {
			@foreach($notifications as $row) {
				<tr>
					<td>@($row->date())</td>
					<td>@($row->title)</td>
					<td>@(Notification::parseRecipients(json_decode($row->recipient, TRUE), FALSE))</td>
					<td>
						<a href="javascript:void(0)" data-id="@($row->notification_id)" class="viewNotification btn btn-primary btn-sm"><i class="fa fa-download"></i></a>
					</td>
				</tr>
			}
		}
	</tbody>
</table>
</div>

@(PaginationWidget::widget(['page'=>$page, 'view' => 'bootstrap.tpl']))