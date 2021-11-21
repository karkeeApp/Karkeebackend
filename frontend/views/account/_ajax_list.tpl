@{
	use yii\helpers\Url;
}

<div class="table-responsive">
<table class="table full-width">
	<thead>
		<tr>
			<th>ID</th>
			<th>Name</th>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody>
		@if(!$users) {
			<tr>
				<td colspan="99">No users found.</td>
			</tr>
		} else {
			@foreach($users as $user) {
				<tr>
					<td>@($user->user_id)</td>
					<td>@($user->username)</td>
					<td>
						<div class="btn-group" role="group">
							<a href="@(Url::home())staff/edit/@($user->user_id)" class="btn btn-sm btn-primary" title="Update"><i class="fa fa-edit" title="Update"></i></a>
							<a href="javascript:void(0);" class="btn btn-sm btn-primary" title="Remove"><i class="fa fa-trash" title="Remove"></i></a>
						</div>
					</td>
				</tr>
			}
		}
	</tbody>
</table>
</div>