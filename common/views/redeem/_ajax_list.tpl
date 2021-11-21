@{
    use yii\helpers\Url;
    use common\helpers\Common;
    use common\widgets\PaginationWidget;    
}

<div class="table-responsive">
<table class="table">
    <thead>
        <tr>
            <th width="10%">Date</th>
            <th>Name</th>
            <th>Vendor</th>
            <th>Email</th>
            <th width="15%">Actions</th>
        </tr>
    </thead>
    <tbody>
        @if(!$redeems) {
            <tr>
                <td colspan="99">No redeems found.</td>
            </tr>
        } else {
            @foreach($redeems as $redeem) {
                <tr>
                    <td>@(Common::systemDateFormat($redeem->created_at))</td>
                    <td>@($redeem->user->fullname())</td>
                    <td>@($redeem->item->user->vendor_name)</td>
                    <td>@($redeem->user->email)</td>
                    <td>
                        <div class="btn-group dropdown">
                            <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">
                                <i class=" fa fa-bars"></i>
                            </a>
                            <ul class="dropdown-menu pull-right">
                                <li><a href="@(Url::home())item/@($redeem->item_id)" title="View"><i class="fa fa-info" title="View"></i> View Service</a></li>
                            </ul>
                        </div> 
                    </td>
                </tr>
            }
        }
    </tbody>
</table>
</div>

@(PaginationWidget::widget(['page'=>$page, 'view' => 'bootstrap.tpl']))