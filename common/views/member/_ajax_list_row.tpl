@{
    use yii\helpers\Url;
    use common\helpers\Common;
    use common\widgets\PaginationWidget;    
}

<td>@($model->memberId())</td>
<td><img src="@($model->img_profile() . '&size=small')" /></td>
<td><a href="@(Url::home())member/@($model->user_id)">@($model->fullname)</a></td>
<td>
    @($model->email)
    @if($model->isAdmin()){
        <i class="fa fa-user"></i>
    }
</td>
<td>@($model->membershipStatus())</td>
<td>@($model->mem_expiry())</td>
<td>@($model->approvedAt())</td>
<td>@(Common::systemDateFormat($model->created_at))</td>
<td class="@($model->statusClass())">@($model->status())</td>
<td>
    <div class="btn-group dropdown">
        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">
            <i class=" fa fa-bars"></i>
        </a>
        <ul class="dropdown-menu pull-right">
            <li><a href="@(Url::home())member/@($model->user_id)" title="View Profile"><i class="fa fa-info" title="View"></i> View</a></li>
            @if($model->isVendor()){
                <li><a href="@(Url::home())member/edit-vendor/@($model->user_id)" title="Edit Vendor"><i class="fa fa-edit"></i> Edit Vendor</a></li>
            } else {
                <li><a href="@(Url::home())member/edit/@($model->user_id)" title="Edit"><i class="fa fa-edit"></i> Edit</a></li>
            }
            <li><a href="@(Url::home())member/settings/@($model->user_id)" title="Settings"><i class="fa fa-cog" title="Settings"></i> Settings</a></li>
            <li><a href="javascript:void(0);" class="remove-account" title="Remove" data-user_id="@($model->user_id)"><i class="fa fa-trash" title="Remove"></i> Remove</a></li>

            @if((Common::isClub() AND !$model->isAdmin()) OR (Common::isCpanel() AND !$model->isCarkeeAdmin())) {
                <li><a href="javascript:void(0);" title="Add to Admin" class="add-to-admin" data-user_id="@($model->user_id)"><i class="fa fa-user"></i> Add to Admin</a></li>
            }                                
        </ul>
    </div>

    <div class="btn-group" role="group">
    </div>
</td>
               