@{
	use yii\helpers\Url;
	use common\widgets\PaginationWidget;	
}

<div class="table-responsive">
<table class="table">
	<thead>
		<tr>
			<th>Title</th>
			<th>Type</th>
			<th>Thumb</th>
			<th>URL</th>
			<th width="25%">Actions</th>
		</tr>
	</thead>
	<tbody>
		@if(!$medias) {
			<tr>
				<td colspan="99">No medias found.</td>
			</tr>
		} else {
			@foreach($medias as $media) {
				<tr>
					<td>@($media->title)</td>
					<td>@($media->extension())</td>
					<td>@($media->thumb())</td>
					<td>@($media->url())</td>
					<td>
						<div class="btn-group" role="group">
							<a href="javascript:void(0);" class="btn btn-sm btn-primary" title="Remove"><i class="fa fa-trash" title="Remove"></i></a>
						</div>
					</td>
				</tr>
			}
		}
	</tbody>
</table>
</div>

@(PaginationWidget::widget(['page'=>$page, 'view' => 'bootstrap.tpl']))