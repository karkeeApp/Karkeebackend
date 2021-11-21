@{
    use yii\helpers\Url;
    use common\helpers\Common;
    use common\widgets\PaginationWidget;    
}

<td>@($model->event_id)</td>
<td>@($model->title)</td>
<td><img src="@($model->imagelink() . '&size=small')" /></td>
<td>@(Common::systemDateFormat($model->created_at))</td>
<td>@($model->event_time)</td>
<td>
    <div class="btn-group dropdown">
        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">
            <i class=" fa fa-bars"></i>
        </a>
        <ul class="dropdown-menu pull-right">
            <li><a href="@(Url::home())event/@($model->event_id)" title="Edit Event"><i class="fa fa-info" title="Edit"></i> View</a></li>
            <li><a href="@(Url::home())event/edit/@($model->event_id)" title="Edit Event"><i class="fa fa-edit" title="Edit"></i> Edit</a></li>
            <li><a href="javascript:void(0);" title="Remove" class="delete" data-id="@($model->event_id)"><i class="fa fa-trash" title="Remove"></i> Delete</a></li>
        </ul>
    </div>
</td>