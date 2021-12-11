@{
	use yii\helpers\Url;
	use common\widgets\PaginationWidget;	
}

<div class="table-responsive">
<table class="table">
	<thead>
		<tr>
			<th>ID</th>
			<th>Name</th>
			<th>Title</th>
			<th width="15%">Actions</th>
		</tr>
	</thead>
	<tbody>
		@if(!$pages) {
			<tr>
				<td colspan="99">No pages found.</td>
			</tr>
		} else {
			@foreach($pages as $row) {
				<tr>
					<td>@($row->page_id)</td>
					<td>@($row->name)</td>
					<td>@($row->title)</td>
					<td>
						<div class="btn-group" role="group">
							<a href="@(Url::home())page/edit/@($row->page_id)" class="btn btn-sm btn-primary" title="Edit Page"><i class="fa fa-download" title="View"></i></a>
							<!--
								<a href="javascript:void(0);" class="btn btn-sm btn-primary" title="Remove"><i class="fa fa-trash" title="Remove"></i></a>
							-->
						</div>
					</td>
				</tr>
			}
		}
	</tbody>
</table>
</div>

@(PaginationWidget::widget(['page'=>$page, 'view' => 'bootstrap.tpl']))