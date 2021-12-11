@{
	use yii\helpers\Url;
	use common\helpers\Common;
	use common\widgets\PaginationWidget;	
}

<div class="table-responsive">
<table class="table full-width">
	<thead>
		<tr>
			<th>Date</th>
			<th>Filename</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
		@if(!$files) {
			<tr>
				<td colspan="99">No files found.</td>
			</tr>
		} else {
			@foreach($files as $file) {
				<tr>
					<td>@($file->date())</td>
					<td>@($file->filename)</td>
					<td>
						<a href="@(Url::home())file/staffimport/@($file->import_id)" class="fa fa-download"></a>
					</td>
				</tr>
			}
		}
	</tbody>
</table>
</div>

@(PaginationWidget::widget(['page'=>$page, 'view' => 'bootstrap.tpl']))