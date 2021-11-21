@{
    use yii\helpers\Url;
    use common\helpers\Common;
    use common\widgets\PaginationWidget;    
}

<td>@($model->id)</td>
<td><a href="@(Url::home())sponsor/@($model->user_id)">@($model->user->fullname())</a></td>
<td>@($model->user->email)</td>
<td>@(Common::systemDateFormat($model->created_at))</td>
<td>@($model->status())</td>
<td>
    <div class="btn-group dropdown">
        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">
            <i class=" fa fa-bars"></i>
        </a>
        <ul class="dropdown-menu pull-right">
            @if($model->isPending()){
                <li><a href="javascript:void(0);" class="approve-renewal" data-id="@($model->id)"><i class="fa fa-thumbs-up" title="Approve"></i> Approve</a></li>
                <li><a href="javascript:void(0);" class="reject-renewal" data-id="@($model->id)"><i class="fa fa-trash" title="Reject"></i> Reject</a></li>
            }
        </ul>
    </div>

    <div class="btn-group" role="group">
    </div>
</td>
              
           