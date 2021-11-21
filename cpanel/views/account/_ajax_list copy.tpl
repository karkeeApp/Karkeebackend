@{
    use yii\helpers\Url;
    use common\widgets\PaginationWidget;    
    use common\helpers\Common;
}

<div class="table-responsive">
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Company</th>
            <th>Email</th>
            <th>Member Type</th>
            <th>Date Created</th>
            <th>Status</th>
            <th width="25%">Actions</th>
        </tr>
    </thead>
    <tbody>
        @if(!$accounts) {
            <tr>
                <td colspan="99">@(Yii::t('app', 'No accounts found.'))</td>
            </tr>
        } else {
            @foreach($accounts as $account) {
                @{
                    $user = $account->user;
                    $sysDateFormat = Common::systemDateFormat($user->created_at);
                    
                }
                <tr>
                    <td>@($account->account_id) <span style="display: none;">@($user->auth_key)</span></td>
                    <td>@($user->company)</td>
                    <td>@($user->email)</td>
                    <td>@($user->carkee_member_type())</td>
                    <td>@($sysDateFormat)</td>
                    <td class="@($user->statusClass())">@($user->status())</td>
                    <td>
                        <div class="btn-group dropdown">
                            <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">
                                <i class=" fa fa-bars"></i>
                            </a>
                            <ul class="dropdown-menu pull-right">
                                <li><a href="@(Url::Home())account/@($account->account_id)"><i class="fa fa-download"></i> View Account</a></li>
                                <li><a href="@(Url::Home())account/edit/@($account->account_id)"><i class="fa fa-edit"></i> Edit Account</a></li>
                                <li><a href="@(Url::Home())account/admins/@($account->account_id)"><i class="fa fa-users"></i> Account Admin</a></li>
                                <li><a href="@(Url::Home())account/members/@($account->account_id)"><i class="fa fa-user"></i> Member</a></li>
                                <li><a href="@(Url::Home())account/settings/@($account->account_id)"><i class="fa fa-cog"></i> Settings</a></li>
                                <li><a href="javascript:void(0);" class="remove-account" data-account_id="@($account->account_id)"><i class="fa fa-trash"></i> Remove</a>
                            </ul>
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
                var accountId = $(this).data('account_id');

                modConfirm('Are you sure you want to remove this account?', function() {
                    serverProcess({
                        action : 'account/delete',
                        data : 'account_id=' + accountId,
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