@{
	use yii\helpers\Url;
	use common\helpers\Common;
	use common\widgets\PaginationWidget;
}

<div class="table-responsive">
<table class="table">
	<thead>
		<tr>
			<th>ID</th>
			@if(Common::isCpanel()) {
				<th>Account</th>
			}
			<th>Name</th>
			<th>Amount</th>
			<th>Date Applied</th>
			<th>Date Approved</th>
			<th>Status</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
		@if(!$loans) {
			<tr>
				<td colspan="99">No loans found.</td>
			</tr>
		} else {
			@foreach($loans as $loan) {
				<tr>
					<td>@($loan->loan_id)</td>
					@if(Common::isCpanel()) {
						<td>@($loan->account->username)</td>
					}
					<td>@($loan->user->fullname())</td>
					<td>@(Common::currency($loan->amount))</td>
					<td>@(Common::date($loan->created_at))</td>
					<td>@(Common::date($loan->approved_at))</td>
					<td>@($loan->status())</td>
					<td>
						<div class="btn-group">
							<a class="btn btn-default btn-sm" title="View" href="@(Url::home())loan/@($loan->loan_id)"><i class="fa fa-download"></i></a>
						</div>
					</td>
				</tr>
			}
		}
	</tbody>
</table>
</div>

@(PaginationWidget::widget(['page'=>$page, 'view' => 'bootstrap.tpl']))