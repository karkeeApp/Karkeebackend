@{
    use yii\helpers\Url;
    use common\widgets\PaginationWidget;    
}

<div class="table-responsive">
<table class="table">
    <thead>
        <tr>
            <th width="10%">ID</th>
            <th>Title</th>
            <th>Description</th>
            <th width="10%">Amount</th>
            <th width="10%">Redeems</th>
            <th width="10%">Status</th>
            <th width="15%">Actions</th>
        </tr>
    </thead>
    <tbody>
        @if(!$items) {
            <tr>
                <td colspan="99">No items found.</td>
            </tr>
        } else {
            @foreach($items as $item) {
                <tr>
                    <td>@($item->item_id)</td>
                    <td>@($item->title)</td>
                    <td>@($item->content)</td>
                    <td>@($item->amount)</td>
                    <td>@($item->redeemCount)</td>
                    <td class="@($item->statusClass())">@($item->status())</td>
                    <td>
                        <div class="dropdown">
                            <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">
                                <i class=" fa fa-bars"></i>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="@(Url::home())item/redeem/@($item->item_id)" title="Redeem"><i class="fa fa-gift" title="Redeems"></i> Redeems (@($item->redeemCount))</a></li>
                                <li><a href="@(Url::home())item/@($item->item_id)" title="View"><i class="fa fa-info" title="View"></i> View</a></li>
                                <li><a href="@(Url::home())item/edit/@($item->item_id)" title="Edit"><i class="fa fa-edit" title="Edit"></i> Edit</a></li>
                                <li><a href="javascript:void(0);" title="Remove"><i class="fa fa-trash" title="Remove"></i> Delete</a></li>
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