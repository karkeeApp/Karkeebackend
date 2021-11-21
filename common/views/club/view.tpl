@{
	use yii\helpers\Url;
	use common\helpers\Html;
    use yii\widgets\ActiveForm;
    use common\helpers\Common;
}

@($menu)

@{
    $form = ActiveForm::begin([
        'id' => 'user-form', 
        'enableClientScript' => true,
        'fieldConfig' => [
            'labelOptions' => ['class' => 'control-label'],
            'template' => "<div class='col-xs-12 col-sm-12 col-md-3 col-lg-3'>{label}</div>\n<div class=\"col-xs-12 col-sm-12 col-md-9 col-lg-9\">{input}{error}</div>",
            'options' => [
                'class' => 'row',
            ]
        ],
    ]);
}

<div class="container">
    <div class="row mb10">
        @Html::activeLabel($user, 'brand_synopsis', ['class' => 'col-sm-4 col-md-3 control-label'])
        @Html::activePrint($user, 'brand_synopsis', ['class' => 'col-sm-8 col-md-9'])
    </div>

    <div class="row mb10">
        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
            @Html::activeLabel($user, 'club_logo', ['class' => 'control-label'])
        </div>
        <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 col-md-offset-3 col-lg-offset-3">
            <div class="text-left">
                @if($user->club_logo){
                <img class="img-responsive" src="@($user->club_logo())" width="200"/>
                }else{
                <span class="grey-bg form-control input-sm">upload club logo</span>
                }
            </div>
        </div>
    </div>
    <div class="row mb10">
        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
            @Html::activeLabel($user, 'brand_guide', ['class' => 'control-label'])
        </div>
        <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 col-md-offset-3 col-lg-offset-3">
            <div class="text-left">
                @if($user->brand_guide){
                <img class="img-responsive" src="@($user->brand_guide())" width="200"/>
                }else{
                <span class="grey-bg form-control input-sm">upload brand_guide</span>
                }
            </div>
        </div>
    </div>

</div>
@{ ActiveForm::end(); }