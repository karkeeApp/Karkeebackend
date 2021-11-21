@{
    use yii\helpers\Url;

    use yii\widgets\ActiveForm;
    use \common\models\UserPayment;

    $token = Yii::$app->security->generateRandomString();
}

@($menu)

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
        <h4><i class="fa fa-file"></i> @($userpayment ? 'Update' : 'Create')</h4>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-right">

    </div>
</div>

<br />

<ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#details"><i class="fa fa-info"></i> Details</a></li>
</ul>

<div class="tab-content">
    <div id="details" class="tab-pane active">
        @{
            $form = ActiveForm::begin([
                'id' => 'user-payment-form',
                'enableClientScript' => true,
                'fieldConfig' => [
                    'labelOptions' => ['class' => 'control-label'],
                    'template' => "<div class='col-xs-12 col-sm-12 col-md-3 col-lg-3'>{label}</div>\n<div class=\"col-xs-12 col-sm-12 col-md-9 col-lg-9\">{input}{error}</div>",
                    'options' => [
                        'class' => 'row mb10',
                    ]
                ],
            ]);
        }

    <div class="hide">
        @$form->field($userPaymentForm, 'id')->hiddenInput()
        <input type="hidden" name="action" value="@(($userPaymentForm->id) ? 'edit' : 'add')">
    </div>

    @$form->field($userPaymentForm, 'name')->textInput(['class' => 'form-control input-sm'])

    @$form->field($userPaymentForm, 'filename', ['template' => "<div class='col-xs-12 col-sm-12 col-md-3 col-lg-3'>{label}</div>\n<div class='col-xs-12 col-sm-12 col-md-9 col-lg-9'>{input}<div class='small'><i>Allowed files: png, jpg, jpeg</i></div>{error}</div>"])->fileInput(['class' => ''])


    <div class="row mb10" id="div-banner-img" style="@($userpayment ? 'display: block;' : 'display: none;')">
        <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 col-md-offset-3 col-lg-offset-3">
            <img id="banner-img" src="@($userpayment ? $userpayment->filelink() : '')" width="200" />
        </div>
    </div>

    @$form->field($userPaymentForm, 'description')->textInput(['class' => 'form-control input-sm'])
    @$form->field($userPaymentForm, 'amount')->textInput(['class' => 'form-control input-sm'])


    @{ ActiveForm::end(); }
</div>

<div class="text-right mt10">
    <button type="button" class="btn btn-primary btn-sm" id="savePayment"><i class="fa fa-save"></i> Save</button>
</div>
</div>

<script type="text/javascript">
    (function($) {
        $('#savePayment')
            .bind('click', function(e) {
                e.preventDefault();

                var data = $('#user-payment-form').serializeArray();

                serverProcess({
                    action:'userpayment/@($userpayment ? 'update' : 'create')',
                    data: data,
                    dataType : 'array',
                    form : $('#user-payment-form'),
                    show_process:true,
                    callback:function(json){
                        if(json.success){
                            modAlert(json.message);
                             window.location.href = '@(Url::Home())userpayment';
                        @if(!isset($userPayment)) {
                                $('#user-payment-form').trigger('reset');
                            }
                        } else if(typeof(json.errorFields) == 'object'){
                            window.highlightErrors(json.errorFields);
                            for(var i in json.errorFields){
                                for(var j in json.errorFields[i]){
                                    modAlert(json.errorFields[i][j]);
                                    break;
                                }
                            }
                        }else{
                            modAlert(json.error);
                        }
                    }
                });
            });
})(jQuery);

</script>