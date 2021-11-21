@{
    use yii\helpers\Url;    
}

<div class="row">
    <div class="col-lg-6">
        <h4><i class="fa fa-user"></i> 
            @($account->company)

            @(isset($subTitle) ? $subTitle : NULL)
        </h4>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-right">
        <div class="dropdown">
            <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">
                <i class=" fa fa-bars"></i>
            </a>
            <ul class="dropdown-menu pull-right">
                <li><a href="@(Url::Home())account/@($account->account_id)"><i class="fa fa-info"></i> View Account</a></li>
                <!-- <li><a href="@(Url::Home())account/edit/@($account->account_id)"><i class="fa fa-edit"></i> Edit Account</a></li> -->
                <li><a href="@(Url::Home())account/admins/@($account->account_id)"><i class="fa fa-users"></i> Account Admin</a></li>
                <li><a href="@(Url::Home())account/members/@($account->account_id)"><i class="fa fa-user"></i> Member</a></li>
                <li><a href="javascript:void(0);"><i class="fa fa-trash"></i> Remove</a>
            </ul>
        </div>  
    </div>
</div>

<hr />
