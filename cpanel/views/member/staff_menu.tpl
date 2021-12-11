@{
	use yii\helpers\Url;	
}

<div class="row">
	<div class="col-lg-6">
		<h4><i class="fa fa-user"></i> Staff: @($user->email)</h4>
	</div>
	<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-right">
			<div class="btn-group" role="group">
			<a href="@(Url::Home())staff/@($user->user_id)" class="btn btn-sm btn-default"><i class="fa fa-info"></i> View Profile</a>
			<a href="@(Url::Home())staff/edit/@($user->user_id)" class="btn btn-sm btn-default"><i class="fa fa-edit"></i> Edit Profile</a>
			<a href="@(Url::Home())staff/loans/@($user->user_id)" class="btn btn-sm btn-default"><i class="fa fa-list"></i> Loans</a>
			<a href="@(Url::Home())staff/settings/@($user->user_id)" class="btn btn-sm btn-default"><i class="fa fa-cog"></i> Settings</a>
			<a href="@(Url::Home())staff/scoresummary/@($user->user_id)" class="btn btn-sm btn-default"><i class="fa fa-cog"></i> Score</a>
			<a href="javasript:void(0);" class="btn btn-sm btn-default"><i class="fa fa-trash"></i> Remove</a>
		</div>
	</div>
</div>

<br />
