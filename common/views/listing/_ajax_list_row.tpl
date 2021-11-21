@{
    use yii\helpers\Url;
use common\helpers\Common;
use common\widgets\PaginationWidget;
}


<td>@($model->listing_id)</td>
<td>@($model->title)</td>
<td>@($model->content)</td>
<td>@($model->user->email)</td>
<td class="@($model->statusClass())">@($model->status())</td>
<td>
    <div class="btn-group dropdown">
        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">
            <i class=" fa fa-bars"></i>
        </a>
        <ul class="dropdown-menu pull-right">
            @if($model->isPending()){
                <li><a href="javascript:void(0)" class="approve-now" data-id="@($model->listing_id)" title="Approve"><i class="fa fa-thumbs-up" title="Approve"></i> 
                    @if($model->approved_by){
                        Confirm Now
                    } else {
                        Approve Now
                    }
                </a></li>
            }
            <li><a href="@(Url::home())listing/@($model->listing_id)" title="View"><i class="fa fa-info" title="View"></i> View</a></li>
            <li><a href="@(Url::home())listing/edit/@($model->listing_id)" title="Edit"><i class="fa fa-edit" title="Edit"></i> Edit</a></li>
            <li><a href="javascript:void(0);" class="delete" data-id="@($model->listing_id)" title="Remove"><i class="fa fa-trash" title="Remove"></i> Delete</a></li>
        </ul>
    </div> 
</td>
                