@{
use yii\helpers\Url;
}

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
        <h4><i class="fa fa-comment"></i> Settings</h4>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-right">
        <div class="btn-group" role="group">
            <a href="@(Url::Home())settings" class="btn btn-sm btn-primary"><i class="fa fa-list"></i> List</a>
        </div>
    </div>
</div>

<hr />
