@{
	use yii\helpers\Url;
	use common\helpers\Html;
    use yii\widgets\ActiveForm;
    use common\helpers\Common;
}

@($menu)

<div class="row mb10">
    @Html::activeLabel($user, 'brand_synopsis', ['class' => 'col-sm-4 col-md-3 control-label'])
    @Html::activePrint($user, 'brand_synopsis', ['class' => 'col-sm-8 col-md-9'])
</div>

<div class="row mb10">
    @Html::activeLabel($user, 'club_logo', ['class' => 'col-sm-4 col-md-3 control-label'])
    <div class="col-sm-8 col-md-9">
        @if($user->club_logo){
        <img src="@($user->club_logo())" width="100">
        }
    </div>

</div>

<div class="row mb10">
    @Html::activeLabel($user, 'brand_guide', ['class' => 'col-sm-4 col-md-3 control-label'])
    <div class="col-sm-8 col-md-9">
        @if($user->brand_guide){
        <img src="@($user->brand_guide())" width="100">
        }
    </div>

</div>