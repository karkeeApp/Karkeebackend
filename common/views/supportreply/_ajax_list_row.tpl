@{
Use yii\helpers\Url;
Use common\helpers\Common;
}

<td>@($model->id)</td>
<td>@($model->inquiry)</td>
<td>@($model->message)</td>
<td>@(Common::systemDateFormat($model->created_at))</td>
<td>
    <a href="javascript:void(0);" title="Remove" class="delete" data-id="@($model->id)"><i class="fa fa-trash" title="Remove"></i> Delete</a>
</td>