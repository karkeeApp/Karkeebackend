@{
    use yii\helpers\Url;
    use common\widgets\PaginationWidget;    
}

<div class="table-responsive">
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th width="25%">Actions</th>
        </tr>
    </thead>
    <tbody>
        @if(!$admins) {
            <tr>
                <td colspan="99">@(Yii::t('app', 'No account admins found.'))</td>
            </tr>
        } else {
            @foreach($admins as $admin) {
                <tr>
                    <td>@($admin->admin_id)</td>
                    <td>@($admin->username)</td>
                    <td>@($admin->email)</td>
                    <td>@($admin->role())</td>
                    <td>
                        <div class="btn-group dropdown">
                            <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">
                                <i class=" fa fa-bars"></i>
                            </a>
                            <ul class="dropdown-menu pull-right">
                                <li><a href="@(Url::home())admin/@($admin->admin_id)" title="View"><i class="fa fa-download" title="View"></i> View</a></li>
                                <li><a href="@(Url::home())admin/edit/@($admin->admin_id)" title="Edit"><i class="fa fa-download" title="Edit"></i> Edit</a></li>
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