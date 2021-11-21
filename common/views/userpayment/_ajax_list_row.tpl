@{
use yii\helpers\Url;
Use common\helpers\Common;
}

<td>@($model->id)</td>
<td>@($model->user_id)</td>
<td>@($model->user->fullname)</td>
<td>@(($model->amount ? $model->amount : "-"))</td>
<td>@($model->description)</td>
<td><img src="@($model->filelink(). '&size=small')" /></td>
<td>@($model->status == 1 ? 'Pending' : ($model->status == 2 ? 'Approved': ($model->status == 3 ? 'Rejected': '')))</td>
<td>
    <div class="btn-group dropdown">
        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">
            <i class=" fa fa-bars"></i>
        </a>
        <ul class="dropdown-menu pull-right">
            <li><a href="@(Url::home())userpayment/@($model->id)" title="Edit Event"><i class="fa fa-info" title="Edit"></i> View</a></li>
            <li><a href="@(Url::home())userpayment/edit/@($model->id)" title="Edit Event"><i class="fa fa-edit" title="Edit"></i> Edit</a></li>
            <li><a href="javascript:void(0);" title="Remove" class="delete" data-id="@($model->id)"><i class="fa fa-trash" title="Remove"></i> Delete</a></li>
        </ul>
    </div>
</td>