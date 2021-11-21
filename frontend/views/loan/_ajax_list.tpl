@{
	use yii\helpers\Url;
	use common\helpers\Common;
	use common\widgets\PaginationWidget;
}

<div class="table-responsive">
<table class="table full-width">
	<thead>
		<tr>
			<th>ID</th>
			<th>Amount</th>
			<th>Date Applied</th>
			<th>Date Approved</th>
			<th>Status</th>
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
					<td>@(Common::currency($loan->amount))</td>
					<td>@(Common::date($loan->created_at))</td>
					<td>@(Common::date($loan->approved_at))</td>
					<td>@($loan->status())</td>
				</tr>
			}
		}
	</tbody>
</table>
</div>

@(PaginationWidget::widget(['page'=>$page, 'view' => 'bootstrap.tpl']))