<div class="table-responsive">
<table class="table">
	<thead>
		<tr>
			<th></th>
			<th>Name</th>
			<th>Email</th>
		</tr>
	</thead>
	@if($users) {
		@foreach($users as $user) {
			<tr>
				<td><input type="checkbox" name="staff[@($user->user_id)]" value="@($user->user_id)"></td>
				<td>@($user->fullname())</td>
				<td>@($user->email)</td>
			</tr>
		}
	} else {
		<tr>
			<td colspan="99">Staff not found.</td>
		</tr>
	}
</table>
</div>
