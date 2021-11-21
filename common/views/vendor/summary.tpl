@{
	use common\helpers\Common;
	use common\helpers\LeaveHelper;

	use common\models\LeaveApplication;

	$quota = $user->leaveQuotaAll();
}

@($menu)

<h4><i class="fa fa-btc"></i> Credit Limit Summary</h4>

<div class="table-responsive">
<table class="table">
	<thead>
		<tr>
			<th>@(Yii::t('app', 'Credit Limit'))</th>
			<th>Credit Used</th>
			<th>Credit Remaining</th>
		</tr>
	</thead>
	<tr>
		<td>@(Common::currency($user->fund->creditLimit()))</td>
		<td>@(Common::currency($user->fund->creditUsed()))</td>
		<td>@(Common::currency($user->fund->creditBalance()))</td>
	</tr>		
</table>
</div>

<hr />

<h4><i class="fa fa-sign-out"></i> Leave Summary</h4>

<div class="table-responsive">
<table class="table">
	<thead>
		<tr>
			<th>Name</th>
			<th>Quota</th>
			<th>Used</th>
			<th>Available</th>
		</tr>
	</thead>
	@foreach($quota as $type => $row) {
		<tr>
			<td>@(LeaveApplication::types()[$type])</td>
			<td>@(Common::leaveLabel($row['quota']))</td>
			<td>@(Common::leaveLabel($row['used']))</td>
			<td>@(Common::leaveLabel($row['quota'] - $row['used']))</td>
		</tr>		
	} 
</table>
</div>