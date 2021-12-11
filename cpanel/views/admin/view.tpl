@{
    use yii\helpers\Url;
    use common\helpers\Html;
    use yii\widgets\ActiveForm;
    use common\helpers\Common;
}

@($menu)

<div class="row mb10">
    @Html::activeLabel($admin, 'username', ['class' => 'col-xs-12 col-sm-12 col-md-3 col-lg-3 '])
    @Html::activePrint($admin, 'username', ['class' => 'col-xs-12 col-sm-12 col-md-9 col-lg-9'])
</div>

<div class="row mb10">
    @Html::activeLabel($admin, 'email', ['class' => 'col-xs-12 col-sm-12 col-md-3 col-lg-3 '])
    @Html::activePrint($admin, 'email', ['class' => 'col-xs-12 col-sm-12 col-md-9 col-lg-9'])
</div>

<div class="row mb10">
    @Html::activeLabel($admin, 'role', ['class' => 'col-xs-12 col-sm-12 col-md-3 col-lg-3 '])
    @Html::activePrint($admin, 'role', ['class' => 'col-xs-12 col-sm-12 col-md-9 col-lg-9'])
</div>

<div class="row mb10">
    @Html::activeLabel($admin, 'status', ['class' => 'col-xs-12 col-sm-12 col-md-3 col-lg-3 '])
    @Html::activePrint($admin, 'status', ['class' => 'col-xs-12 col-sm-12 col-md-9 col-lg-9'])
</div>