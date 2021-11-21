@{
	use yii\helpers\Url;
	use common\helpers\Common;
	use common\widgets\PaginationWidget;	
}

<div class="table-responsive">
<table class="table">
	<thead>
		<tr>
			<th>Date</th>
			<th>Title</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
		@if(!$notifications) {
			<tr>
				<td colspan="99">No notifications found.</td>
			</tr>
		} else {
			@foreach($notifications as $notification) {
				<tr class="@((!$notification->is_read) ? 'bold' : '')">
					<td>@($notification->date())</td>
					<td>@($notification->title)</td>
					<td>
						<a href="@(Url::home())notification/@($notification->notification_id)" title='View'><i class="fa fa-info"></i></a>
					</td>
				</tr>
			}
		}
	</tbody>
</table>
</div>

@(PaginationWidget::widget(['page'=>$page, 'view' => 'bootstrap.tpl']))