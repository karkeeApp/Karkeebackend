@{
    Use yii\helpers\Url;    
    Use common\helpers\Common;  
}

<td >@(date('d M Y', strtotime($model->created_at)))</td>
<td>@($model->message())</td>
