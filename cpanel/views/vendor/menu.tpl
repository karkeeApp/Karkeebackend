@{
    use yii\helpers\Url;    
}

<div class="row">
    <div class="col-lg-6">
        <h4><i class="fa fa-user"></i> Vendor</h4>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-right">
		<div class="btn-group" role="group">
            <a class="btn btn-sm btn-primary" href="@(Url::home())vendor/add-vendor">Add Vendor</a>
        </div>
    </div>
</div>

<hr />