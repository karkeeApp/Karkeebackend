@{
	use common\helpers\Common;

	$user = Yii::$app->user->getIdentity();
}

<div class="row">
	<div class="col-lg-4 text-center">
		<h3>Credit Limit</h3>
		<h2>@(Common::currency($user->fund->creditLimit()))</h2>
	</div>

	<div class="col-lg-4 text-center">
		<h3>Credit Used</h3>
		<h2>@(Common::currency($user->fund->creditUsed()))</h2>
	</div>

	<div class="col-lg-4 text-center">
		<h3>Credit Remaining</h3>
		<h2>@(Common::currency($user->fund->creditBalance()))</h2>
	</div>
</div>

<br />