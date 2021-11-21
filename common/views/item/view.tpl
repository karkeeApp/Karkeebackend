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
            'template' => "<div class='col-sm-6 col-md-2'>{label}</div>\n<div class=\"col-sm-6 col-md-10\">{input}{error}</div>",
            'options' => [
                'class' => 'row',
            ]
        ],
    ]);
}
<div class="container">
    <div class="row mb10">
        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
            <span 'class' => 'control-label'>Account</span>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
            @($item->account->company)         
        </div>             
    </div>

    @$form->field($item, 'title')->textInput(['class' => 'form-control input-sm', 'disabled' => true])
    @$form->field($item, 'vendor')->textInput(['class' => 'form-control input-sm', 'disabled' => true])
    @$form->field($item, 'content')->textArea(['class' => 'form-control input-sm', 'disabled' => true])
    @$form->field($item, 'amount')->textInput(['class' => 'form-control input-sm', 'disabled' => true])
    @$form->field($item, 'limit')->textInput(['class' => 'form-control input-sm', 'disabled' => true])
    @$form->field($item, 'redeemCount')->textInput(['class' => 'form-control input-sm', 'disabled' => true])
    @$form->field($item, 'status')->textInput(['class' => 'form-control input-sm', 'value' => $listing->status(), 'disabled' => true])
</div>

@{ ActiveForm::end(); }