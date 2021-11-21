@{
    use yii\helpers\Url;
}

<td>@($model->user_id)</td>
<td>@($model->fullname)</td>
<td>@($model->email)</td>
<td>@($model->brand_synopsis)</td>
<td>
    <a href="@(Url::home())club/@($model->user_id)" title="View"><i class="fa fa-eye" title="View"></i> View</a>
</td>