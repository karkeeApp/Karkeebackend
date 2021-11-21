@{
    use yii\helpers\Url;
    use common\helpers\Common;
    use common\widgets\PaginationWidget;
    $user = $model->user;
}

<td>@($user->memberId())</td>
<td><img src="@($user->img_profile() . '&size=small')" /></td>
<td><a href="@(Url::home())member/@($user->user_id)">@($user->fullname)</a></td>

<td>
    <div class="btn-group dropdown">
        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">
            <i class=" fa fa-bars"></i>
        </a>
        <ul class="dropdown-menu pull-right">
            <li><a href="@(Url::home())member/@($user->user_id)" title="View Profile"><i class="fa fa-info"></i> View</a></li>
            <li><a href="javascript:void(0);" data-attendee_id="@($model->user_id)" data-event_id="@($model->event_id)" title="Confirm Attendee" id="confirm_attendee">
                    <i class=" fa fa-thumbs-up"></i> Confirm
                </a>
            </li>
            <li><a href="javascript:void(0);" data-attendee_id="@($model->user_id)" data-event_id="@($model->event_id)" title="Cancel Attendee" id="cancel_attendee">
                    <i class=" fa fa-remove"></i> Cancel
                </a>
            </li>                      
        </ul>
    </div>
</td>