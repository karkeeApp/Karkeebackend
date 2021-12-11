@{
	use yii\helpers\Url;	
}

<div class="row">
	<div class="col-lg-6">
		<h4><i class="fa fa-user-circle"></i> Account User Management</h4>
	</div>
	<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-right">
        <div class="dropdown">
            <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">
                <i class=" fa fa-bars"></i>
            </a>
            <ul class="dropdown-menu pull-right">
				<li><a href="@(Url::Home())user/edit/@($user->user_id)"><i class="fa fa-edit"></i> Edit this user</a></li>
				<li><a href="@(Url::Home())account/users/@($account->account_id)"><i class="fa fa-list"></i> All Users</a></li>
				<li><a href="@(Url::Home())account/hradd/@($account->account_id)"><i class="fa fa-user-circle"></i> Add User</a></li>
            </ul>
        </div> 		
		<div class="btn-group" role="group">
		</div>
	</div>
</div>

<hr />
