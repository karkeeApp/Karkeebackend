@{
	use yii\helpers\Url;
	use common\widgets\PaginationWidget;	
}

<div class="table-responsive">
<table class="table">
	<thead>
		<tr>
			<th>Date</th>
			<th>Account</th>
			<th>Staff</th>
			<th>Type</th>
			<th>Status</th>
			<th width="25%">Actions</th>
		</tr>
	</thead>
	<tbody>
		@if(!$requests) {
			<tr>
				<td colspan="99">No requests found.</td>
			</tr>
		} else {
			@foreach($requests as $request) {
				<tr>
					<td>@($request->date())</td>
					<td>@($request->hrname())</td>
					<td>@($request->staffname())</td>
					<td>@($request->type())</td>
					<td>@($request->status())</td>
					<td>
						<div class="btn-group" role="group">
							<a href="@(Url::home())request/@($request->id)" class="btn btn-sm btn-primary" title="View Request"><i class="fa fa-download" title="View"></i></a>
						</div>
					</td>
				</tr>
			}
		}
	</tbody>
</table>
</div>

@(PaginationWidget::widget(['page'=>$page, 'view' => 'bootstrap.tpl']))