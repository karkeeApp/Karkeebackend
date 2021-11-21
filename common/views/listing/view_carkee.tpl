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
    @$form->field($listing, 'title')->textInput(['class' => 'form-control input-sm', 'disabled' => true])
    @$form->field($listing, 'vendor')->textInput(['class' => 'form-control input-sm',  'value' => $listing->user->email, 'disabled' => true])
    @$form->field($listing, 'content')->textArea(['class' => 'form-control input-sm', 'disabled' => true])
    @$form->field($listing, 'status')->textInput(['class' => 'form-control input-sm', 'value' => $listing->status(), 'disabled' => true])
</div>
@{ ActiveForm::end(); }