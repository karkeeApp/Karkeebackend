@{
	use common\helpers\Common;
	use yii\helpers\Url;	
}


<div class="row">
	<div class="col-sm-6">
		<h4><i class="fa fa-sign-out"></i> Leaves</h4>
	</div>
	<div class="col-sm-6 text-right">
		<div class="btn-group">
			<a href="@(Url::home())leave" class="btn btn-primary btn-sm" title="Leave applications"><i class="fa fa-sign-out"></i> List</a>
			<a href="@(Url::home())leave/apply" class="btn btn-primary btn-sm" title="Apply for Leave"><i class="fa fa-sign-out"></i> Apply</a>
		</div>
	</div>
</div>

<hr />

