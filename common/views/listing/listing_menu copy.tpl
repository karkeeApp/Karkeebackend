@{
    use yii\helpers\Url;    
    use common\helpers\Common;
}

<div class="row">
    <div class="col-lg-4">
        <h4><i class="fa fa-cog"></i> @($listing->title)</h4>
    </div>
    <div class="col-lg-8 text-right">
        <div class="btn-group dropdown">
            <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">
                <i class=" fa fa-bars"></i>
            </a>
            <ul class="dropdown-menu pull-right">
                <li><a href="@(Url::home())listing/@($listing->listing_id)" title="View"><i class="fa fa-info" title="View"></i> View</a></li>
                <li><a href="@(Url::home())listing/edit/@($listing->listing_id)" title="Edit"><i class="fa fa-edit" title="Edit"></i> Edit</a></li>
                <li><a href="@(Url::home())listing/redeems/@($listing->listing_id)" title="Redeems"><i class="fa fa-life-ring" title="Redeems"></i> Redeems</a></li>
                <li><a href="javascript:void(0);" title="Remove"><i class="fa fa-trash" title="Remove"></i> Delete</a></li>
            </ul>
        </div>
    </div>
</div>

<hr />
