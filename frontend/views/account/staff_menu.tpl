@{
use yii\helpers\Url;
}

<div class="row">
    <div class="col-lg-6">
        <h4><i class="fa fa-user"></i> Staff: @($user->email)</h4>
    </div>
    <div class="col-lg-6 text-right">
        <div class="btn-group" role="group">
            <a href="@(Url::home())account/edit/@($user->user_id)" class="btn btn-sm btn-default"><i class="fa fa-edit"></i> Edit Profile</a>
        </div>
    </div>
</div>

<br />
