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
    
        @$form->field($item, 'title')->textInput(['class' => 'form-control input-sm', 'disabled' => true])
        @$form->field($item, 'vendor')->textInput(['class' => 'form-control input-sm', 'disabled' => true])
        @$form->field($item, 'content')->textArea(['class' => 'form-control input-sm', 'disabled' => true])
        @$form->field($item, 'amount')->textInput(['class' => 'form-control input-sm', 'disabled' => true])
        @$form->field($item, 'limit')->textInput(['class' => 'form-control input-sm', 'disabled' => true])
        @$form->field($item, 'redeemCount')->textInput(['class' => 'form-control input-sm', 'disabled' => true])
        @$form->field($item, 'status')->textInput(['class' => 'form-control input-sm', 'value' => $item->status(), 'disabled' => true])
   
</div>
@{ ActiveForm::end(); }