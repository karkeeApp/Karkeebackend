@{
    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use common\forms\AccountForm;
    use common\models\Account;
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
    @if(Yii::$app->session->hasFlash('success')) {
        <div class="mb10 text-success">@(Yii::$app->session->get('success'))</div>
    }
    
    @if(Yii::$app->session->hasFlash('error')) {
        <div class="mb10 text-success">@(Yii::$app->session->get('error'))</div>
    }

    <div class="hide">
        @$form->field($accountForm, 'account_id')->hiddenInput()

        <input type="hidden" name="action" value="@(($accountForm->account_id) ? 'edit' : 'add')">
    </div>

    @$form->field($accountForm, 'company')->textInput(['class' => 'form-control input-sm'])
    @$form->field($accountForm, 'company_full_name')->textInput(['class' => 'form-control input-sm'])
    @$form->field($accountForm, 'address')->textInput(['class' => 'form-control input-sm'])
    @$form->field($accountForm, 'contact_name')->textInput(['class' => 'form-control input-sm'])
    @$form->field($accountForm, 'email')->textInput(['class' => 'form-control input-sm'])
    @$form->field($accountForm, 'status')->dropDownList(Account::statuses(), ['class' => 'form-control input-sm'])
        
    <div class="row">
        <div class="col-lg-12 text-right">
            <button class="btn btn-primary btn-sm" id="save"><i class="fa fa-save"></i> Save</button>
        </div>
    </div>
</div>
@{ ActiveForm::end(); }

<script type="text/javascript">
(function($) {
    $('#save')
    .bind('click', function(e) {
        e.preventDefault();

        serverProcess({
            action:'account/update',
            data: $('#account-form').serialize(),
            show_process:true,
            callback:function(json){                
                if(json.success){
                    window.location.href = '@(Url::Home())account/edit/' + json.account_id;
                } else if(typeof(json.errorFields) == 'object'){
                    window.highlightErrors(json.errorFields);
                }else{
                    modAlert(json.error);
                }
            }
        });
    });
})(jQuery);

</script>
