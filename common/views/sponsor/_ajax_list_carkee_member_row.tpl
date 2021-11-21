@{
    use yii\helpers\Url;
    use common\helpers\Common;
    use common\widgets\PaginationWidget;    
}

<td>@($model->memberId())</td>
<td><img src="@($model->img_profile() . '&size=small')" /></td>
<td><a href="@(Url::home())member/@($model->user_id)">@($model->fullname())</a></td>
<td>@($model->vendor_name)</td>
<td>@($model->email)</td>
<td>@($model->club())</td>
<td>@($model->level == 0 ? '<i class="fa fa-cc-discover" > Normal' : ($model->level == 1 ? '<i class="fa fa-cc-discover" aria-hidden="true"style="color:sienna"> Silver' : ($model->level == 2 ? '<i class="fa fa-cc-discover" aria-hidden="true"style="color:gold"> Gold' : ($model->level == 3 ? '<i class="fa fa-cc-discover" aria-hidden="true"style="color:darkviolet"> Platinum' : ($model->level == 4 ? '<i class="fa fa-diamond" aria-hidden="true"style="color:indianred"> Diamond' : '')))))</td>

<td class="@($model->statusClass())">@($model->status())</td>
<td>
    <div class="btn-group dropdown">
        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">
            <i class=" fa fa-bars"></i>
        </a>
        <ul class="dropdown-menu pull-right">
            <li><a href="javascript:void(0);" class="set-normal" data-user_id="@($model->user_id)"><i class="fa fa-cc-discover" title="Remove"></i> Normal</a></li>
            <li><a href="javascript:void(0);" class="set-silver" data-user_id="@($model->user_id)"><i class="fa fa-cc-discover" title="Remove"></i> Silver</a></li>
            <li><a href="javascript:void(0);" class="set-gold" data-user_id="@($model->user_id)"><i class="fa fa-cc-discover" title="Remove"></i> Gold</a></li>
            <li><a href="javascript:void(0);" class="set-platinum" data-user_id="@($model->user_id)"><i class="fa fa-cc-discover" title="Remove"></i> Platinum</a></li>
            <li><a href="javascript:void(0);" class="set-diamond" data-user_id="@($model->user_id)"><i class="fa fa-diamond" title="Remove"></i> Diamond</a></li>
            <li><a href="javascript:void(0);" class="remove-sponsor" data-user_id="@($model->user_id)"><i class="fa fa-trash" title="Remove"></i> Remove</a></li>
        </ul>


    </div>

    <div class="btn-group" role="group">
    </div>
</td>
              
           