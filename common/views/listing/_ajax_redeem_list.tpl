@{
    use yii\helpers\Url;
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
                    <td>@($redeem->created_at())</td>
                    <td>@($redeem->user->fullname())</td>
                    <td>@($redeem->item->user->vendor_name)</td>
                    <td>@($redeem->user->email)</td>
                </tr>
            }
        }
    </tbody>
</table>
</div>

@(PaginationWidget::widget(['page'=>$page, 'view' => 'bootstrap.tpl']))