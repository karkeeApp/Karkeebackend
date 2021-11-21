@{
    use yii\helpers\Url;    
    use common\helpers\Common;
}

<div class="row">
    <div class="col-lg-6">
        <h4><i class="fa fa-user"></i> @($user->fullname())</h4>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-right">
        <div class="dropdown">
            <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">
                <i class=" fa fa-bars"></i>
            </a>
            <ul class="dropdown-menu pull-right ">
                <li><a href="@(Url::Home())member/@($user->user_id)"><i class="fa fa-info"></i> View</a></li>
                <li><a href="@(Url::Home())member/edit/@($user->user_id)"><i class="fa fa-edit"></i> Edit</a></li>
                <li><a href="@(Url::Home())member/settings/@($user->user_id)"><i class="fa fa-cog"></i> Settings</a></li>
                <li><a href="javasript:void(0);"><i class="fa fa-trash"></i> Remove</a></li>
            </ul>
        </div>
    </div>
</div>

<br />
