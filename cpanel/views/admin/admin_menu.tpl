@{
	use yii\helpers\Url;	
}

<div class="row">
	<div class="col-lg-6">
		<h4><i class="fa fa-user-circle"></i> Admin</h4>
	</div>
	<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-right">
        <div class="dropdown">
            <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">
                <i class=" fa fa-bars"></i>
            </a>
            <ul class="dropdown-menu pull-right">
	            <li><a href="@(Url::home())admin/@($admin->admin_id)" title="View"><i class="fa fa-info" title="View"></i> View</a></li>
	            <li><a href="@(Url::home())admin/edit/@($admin->admin_id)" title="Edit"><i class="fa fa-download" title="Edit"></i> Edit</a></li>
	            <li><a href="javascript:void(0);" title="Remove"><i class="fa fa-trash" title="Remove"></i> Delete</a></li>
            </ul>
        </div>
	</div>
</div>

<hr />
