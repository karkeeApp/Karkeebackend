@{
Use yii\helpers\Url;
Use common\helpers\Common;
}

<td>@($model->renewal_fee)</td>
<td>
    <a href="@(Url::home())settings/edit/@($model->setting_id)" title="Edit" class="edit" data-id="@($model->setting_id)"><i class="fa fa-pencil" title="Edit"></i> Edit</a>
</td>