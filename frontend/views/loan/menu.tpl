@{
	use yii\helpers\Url;	
}

<div class="row">
	<div class="col-lg-6">
		<h4><i class="fa fa-list"></i> Loan</h4>
	</div>
	<div class="col-lg-6 text-right">
		<div class="btn-group" role="group">
			<a href="@(Url::Home())loan" class="btn btn-sm btn-primary"><i class="fa fa-list"></i> List</a>
			<a href="@(Url::Home())loan/apply" class="btn btn-sm btn-primary"><i class="fa fa-btc"></i> Apply Loan</a>
		</div>
	</div>
</div>

<hr />

@($funds)