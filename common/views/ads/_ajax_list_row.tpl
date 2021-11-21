@{
use yii\helpers\Url;
Use common\helpers\Common;
}

<td>@($model->id)</td>
<td>@($model->name)</td>
<td>@($model->description)</td>
<td><img src="@($model->filelink(). '&size=small' )" /></td>
<td>@($model->is_bottom ? '<span class="btn btn-primary "><i class="fa fa-long-arrow-up" title="IsBottom"></i></span>' : '<span class="btn btn-danger"><i class="fa fa-long-arrow-down" title="IsBottom"></i></span>')</td>
<td>
    <div class="btn-group dropdown">
        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">
            <i class=" fa fa-bars"></i>
        </a>
        <ul class="dropdown-menu pull-right">
            <li><a href="javascript:void(0)" class="is-bottom" data-id="@($model->id)" title="Bottom"><i class="fa fa-unsorted" title="IsBottom"></i> Is Bottom</a></li>
            <li><a href="@(Url::home())ads/@($model->id)" title="Edit Event"><i class="fa fa-info" title="Edit"></i> View</a></li>
            <li><a href="@(Url::home())ads/edit/@($model->id)" title="Edit Event"><i class="fa fa-edit" title="Edit"></i> Edit</a></li>
            <li><a href="javascript:void(0);" title="Remove" class="delete" data-id="@($model->id)"><i class="fa fa-trash" title="Remove"></i> Delete</a></li>
        </ul>
    </div>
</td>