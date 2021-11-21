@{
    use yii\helpers\Url;
}

<td>@($model->user_id)</td>
<td><img src="@($model->img_profile())&size=small" /></td>
<td>@($model->fullname)</td>
<td>@($model->email)</td>
<td>@($model->role())</td>
<td>
    <div class="dropdown">
        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">
            <i class=" fa fa-bars"></i>
        </a>
        <ul class="dropdown-menu">
            <li><a href="@(Url::home())member/@($model->user_id)" title="View"><i class="fa fa-info" title="View"></i> View</a></li>
            <li><a href="@(Url::home())accountadmin/settings/@($model->user_id)" title="Settings"><i class="fa fa-cog" title="Settings"></i> Settings</a></li>
            <li><a href="javascript:void(0);" class="delete" title="Remove" data-id="@($model->user_id)"><i class="fa fa-trash" title="Remove"></i> Delete</a></li>
        </ul>
    </div> 
</td>