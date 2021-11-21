@{
use yii\helpers\Url;
use common\helpers\Html;
use yii\widgets\ActiveForm;
use common\helpers\Common;
}

@($menu)






<ul class="nav nav-tabs mb10">
    <li class="active"><a data-toggle="tab" href="#details"><i class="fa fa-info"></i> Details</a></li>
</ul>

<div class="tab-content">



    <div id="details" class="tab-pane active">
        <div class="row mb10">
            <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 col-md-offset-3 col-lg-offset-3">
                <div class="text-left">
                    @if ($user->img_profile()) {
                    <img class="img-responsive" src="@($user->img_profile())" width="200"/>
                    }
                </div>
            </div>
        </div>

        <div class="row mb10">
            @Html::activeLabel($user, 'role', ['class' => 'col-sm-4 col-md-3 control-label'])
            @Html::activePrint($user, 'role', ['class' => 'col-sm-8 col-md-9'])
        </div>


        <div class="row mb10">
            @Html::activeLabel($user, 'level', ['class' => 'col-sm-4 col-md-3 control-label'])
            @Html::activePrint($user, 'level', ['class' => 'col-sm-8 col-md-9', 'value' => ''])
        </div>

        <div class="row mb10">
            @Html::activeLabel($user, 'fullname', ['class' => 'col-sm-4 col-md-3 control-label'])
            @Html::activePrint($user, 'fullname', ['class' => 'col-sm-8 col-md-9'])
        </div>


        <div class="row mb10">
            @Html::activeLabel($user, 'mobile', ['class' => 'col-sm-4 col-md-3 control-label'])
            @Html::activePrint($user, 'mobile', ['class' => 'col-sm-8 col-md-9'])
        </div>

        <div class="row mb10">
            @Html::activeLabel($user, 'email', ['class' => 'col-sm-4 col-md-3 control-label'])
            @Html::activePrint($user, 'email', ['class' => 'col-sm-8 col-md-9'])
        </div>

        <div class="row mb10">
            @Html::activeLabel($user, 'birthday', ['class' => 'col-sm-4 col-md-3 control-label'])
            @Html::activePrint($user, 'birthday', ['class' => 'col-sm-8 col-md-9'])
        </div>



</div>

</div>


