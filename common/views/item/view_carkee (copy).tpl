@{
	use yii\helpers\Url;
	use common\helpers\Html;
    use yii\widgets\ActiveForm;
    use common\helpers\Common;
}

@($menu)

<div class="row mb10">
    @Html::activeLabel($item, 'title', ['class' => 'col-sm-4 col-md-3 control-label'])
    @Html::activePrint($item, 'title', ['class' => 'col-sm-8 col-md-9'])
</div>

<div class="row mb10">
    @Html::activeLabel($item, 'vendor', ['class' => 'col-sm-4 col-md-3 control-label'])
    @Html::activePrint($item, 'vendor', ['class' => 'col-sm-8 col-md-9'])
</div>

<div class="row mb10">
    @Html::activeLabel($item, 'content', ['class' => 'col-sm-4 col-md-3 control-label'])
    @Html::activePrint($item, 'content', ['class' => 'col-sm-8 col-md-9'])
</div>

<div class="row mb10">
    @Html::activeLabel($item, 'amount', ['class' => 'col-sm-4 col-md-3 control-label'])
    @Html::activePrint($item, 'amount', ['class' => 'col-sm-8 col-md-9'])
</div>

<div class="row mb10">
    @Html::activeLabel($item, 'limit', ['class' => 'col-sm-4 col-md-3 control-label'])
    @Html::activePrint($item, 'limit', ['class' => 'col-sm-8 col-md-9'])
</div>

<div class="row mb10">
    @Html::activeLabel($item, 'redeemCount', ['class' => 'col-sm-4 col-md-3 control-label'])
    @Html::activePrint($item, 'redeemCount', ['class' => 'col-sm-8 col-md-9'])
</div>

<div class="row mb10">
    @Html::activeLabel($item, 'status', ['class' => 'col-sm-4 col-md-3 control-label'])
    @Html::activePrint($item, 'status', ['class' => 'col-sm-8 col-md-9'])
</div>