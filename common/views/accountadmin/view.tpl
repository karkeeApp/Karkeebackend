@{
	use yii\helpers\Url;
	use common\helpers\Html;
    use yii\widgets\ActiveForm;
    use common\helpers\Common;
}

@($menu)

<div class="row">
	<label class="col-sm-4 col-md-3 control-label" for="hruser-username">Account</label>
	<div class="col-sm-8 col-md-9">@($user->account->company)</div>
</div>

<div class="row">
    @Html::activeLabel($user, 'username', ['class' => 'col-xs-12 col-sm-12 col-md-3 col-lg-3 '])
    @Html::activePrint($user, 'username', ['class' => 'col-xs-12 col-sm-12 col-md-9 col-lg-9'])
</div>

<div class="row">
    @Html::activeLabel($user, 'email', ['class' => 'col-sm-4 col-md-3 control-label'])
    @Html::activePrint($user, 'email', ['class' => 'col-xs-12 col-sm-12 col-md-9 col-lg-9']
</div>

<div class="row">
    @Html::activeLabel($user, 'role', ['class' => 'col-sm-4 col-md-3 control-label'])
    @Html::activePrint($user, 'role', ['class' => 'col-xs-12 col-sm-12 col-md-9 col-lg-9'])
</div>

<div class="row">
    @Html::activeLabel($user, 'status', ['class' => 'col-sm-4 col-md-3 control-label'])
    @Html::activePrint($user, 'status', ['class' => 'col-xs-12 col-sm-12 col-md-9 col-lg-9'])
</div>