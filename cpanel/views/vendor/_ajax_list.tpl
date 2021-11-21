@{
    use yii\helpers\Url;
    use common\helpers\Common;
    use common\widgets\PaginationWidget;    
}

<div class="table-responsive">
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Photo</th>
            <th>Vendor Name</th>
            <th>Email</th>
            <th>Member Type</th>
            <th>Date Created</th>
            <th>Status</th>
            <th width="20%">Actions</th>
        </tr>
    </thead>
    <tbody>
        @if(!$users) {
            <tr>
                <td colspan="99">No users found.</td>
            </tr>
        } else {
            @foreach($users as $user) {
                <tr>
                    <td>@($user->user_id)</td>
                    <td><img src="@($user->img_profile() . '&size=small')" /></td>
                    <td><a href="@(Url::home())vendor/@($user->user_id)">@($user->vendor_name)</a></td>
                    <td>@($user->email)</td>
                    <td>@($user->carkee_member_type())</td>
                    <td>@(Common::systemDateFormat($user->created_at))</td>
                    <td class="@($user->statusClass())">@($user->status())</td>
                    <td>
                        <div class="btn-group dropdown">
                            <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">
                                <i class=" fa fa-bars"></i>
                            </a>
                            <ul class="dropdown-menu pull-right">
                                <li><a href="@(Url::home())vendor/@($user->user_id)" title="View Profile"><i class="fa fa-info" title="View"></i> View</a></li>
                                <li><a href="@(Url::home())vendor/edit/@($user->user_id)" title="Edit Profile"><i class="fa fa-edit" title="Edit"></i> Edit</a></li>
                                <li><a href="@(Url::home())vendor/settings/@($user->user_id)" title="Settings"><i class="fa fa-cog" title="Settings"></i> Settings</a></li>
                                <li><a href="javascript:void(0);" class="remove-account" data-user_id="@($user->user_id)"><i class="fa fa-trash" title="Remove"></i> Remove</a></li>
                            </ul>
                        </div>

                        <div class="btn-group" role="group">
                        </div>
                    </td>
                </tr>
            }
        }
    </tbody>
</table>
</div>

<script type="text/javascript">
    (function($) {
        $(document).ready(function() {
            $('.remove-account').on('click', function(e){
                e.preventDefault();
                var userId = $(this).data('user_id');

                modConfirm('Are you sure you want to remove this vendor?', function() {
                    serverProcess({
                        action : 'vendor/delete',
                        data : 'user_id=' + userId,
                        show_process : true,
                        callback : function(json) {
                            if(json.success){
                                modAlert(json.message);
                                window.location.reload();
                            } else if(typeof(json.errorFields) == 'object'){
                                window.highlightErrors(json.errorFields);
                            }else{
                                modAlert(json.error);
                            }
                        }
                    });
                });
            });
        });
    })(jQuery);
</script>

@(PaginationWidget::widget(['page'=>$page, 'view' => 'bootstrap.tpl']))