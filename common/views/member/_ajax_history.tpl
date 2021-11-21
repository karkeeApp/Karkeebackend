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
			<th>Description</th>
			<th>Debit</th>
			<th>Credit</th>
		</tr>
	</thead>
	<tbody>
		@if(!$histories) {
			<tr>
				<td colspan="99">No transactions found.</td>
			</tr>
		} else {
			@foreach($histories as $history) {
				<tr>
					<td>@($history->date())</td>
					<td>@($history->description())</td>
					<td>@($history->debit())</td>
					<td>@($history->credit())</td>
				</tr>
			}
		}
	</tbody>
</table>
</div>

@(PaginationWidget::widget(['page'=>$page, 'view' => 'bootstrap.tpl']))