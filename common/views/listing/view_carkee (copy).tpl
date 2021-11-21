@{
	use yii\helpers\Url;
	use common\helpers\Html;
    use yii\widgets\ActiveForm;
    use common\helpers\Common;
}

@($menu)

<div class="row mb10">
    @Html::activeLabel($listing, 'title', ['class' => 'col-sm-4 col-md-3 control-label'])
    @Html::activePrint($listing, 'title', ['class' => 'col-sm-8 col-md-9'])
</div>

<div class="row mb10">
    @Html::activeLabel($listing, 'vendor', ['class' => 'col-sm-4 col-md-3 control-label'])
    @Html::activePrint($listing, 'vendor', ['class' => 'col-sm-8 col-md-9'])
</div>

<div class="row mb10">
    @Html::activeLabel($listing, 'content', ['class' => 'col-sm-4 col-md-3 control-label'])
    @Html::activePrint($listing, 'content', ['class' => 'col-sm-8 col-md-9'])
</div>

<div class="row mb10">
    @Html::activeLabel($listing, 'status', ['class' => 'col-sm-4 col-md-3 control-label'])
    @Html::activePrint($listing, 'status', ['class' => 'col-sm-8 col-md-9'])
</div>