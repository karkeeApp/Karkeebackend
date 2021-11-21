@{
Use yii\helpers\Url;
Use common\helpers\Common;
}

<td>@($model->id)</td>
<td>@($model->user_id)</td>
<td>@($model->description)</td>
<td>@(Common::systemDateFormat($model->created_at))</td>
<td>
    <a href="@(Url::home())supportreply/add/@($model->id)" title="Edit Support"><i class="fa fa-comment" title="Edit"></i> Reply</a>
</td>