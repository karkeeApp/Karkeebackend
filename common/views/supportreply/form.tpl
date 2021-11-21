@{
use yii\helpers\Url;

use yii\widgets\ActiveForm;
use \common\models\SupportReply;
use \common\models\Support;

$token = Yii::$app->security->generateRandomString();
}

@($menu)


<div class="tab-content">
    <div id="details" class="tab-pane active">
        @{
        $form = ActiveForm::begin([
                    'id' => 'support-reply-form',
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

    @$form->field($support, 'email')->textInput(['class' => 'form-control input-sm', 'disabled' => true])
    @$form->field($support, 'name')->textInput(['class' => 'form-control input-sm', 'disabled' => true])
    @$form->field($support, 'description')->textInput(['class' => 'form-control input-sm', 'disabled' => true])
    @$form->field($supportReplyForm, 'message')->textarea(['class' => 'form-control input-sm'])

    @{ ActiveForm::end(); }
</div>


<div class="text-right mt10">
    <button type="button" class="btn btn-primary btn-sm" id="saveSupport"><i class="fa fa-save"></i> Send Reply</button>
</div>
</div>




<script type="text/javascript">
    (function($) {
        $('#saveSupport')
            .bind('click', function(e) {
                e.preventDefault();

                var data = $('#support-reply-form').serializeArray();

                serverProcess({
                    action:'supportreply/create?support_id=@($support->id)',
                    data: data,
                    dataType : 'array',
                    form : $('#support-reply-form'),
                    show_process:true,
                    callback:function(json){
                        if(json.success){
                            modAlert(json.message);
                            window.location.href = '@(Url::Home())support';
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

