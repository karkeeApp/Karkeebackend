@{
use yii\helpers\Url;

use yii\widgets\ActiveForm;
use \common\models\Settings;

}

@($menu)


<div class="tab-content">
    <div id="details" class="tab-pane active">
        @{
            $form = ActiveForm::begin([
                        'id' => 'settings-form',
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

        
        @$form->field($settingsForm, 'renewal_fee')->textInput(['class' => 'form-control input-sm', 'type' => 'number'])

        @{ ActiveForm::end(); }
    </div>


    <div class="text-right mt10">
        <button type="button" class="btn btn-primary btn-sm" id="saveSettings"><i class="fa fa-save"></i> Save</button>
    </div>
</div>




<script type="text/javascript">
    (function($) {
        $('#saveSettings')
            .bind('click', function(e) {
                e.preventDefault();

                var data = $('#settings-form').serializeArray();

                serverProcess({
                    action:'settings/update?setting_id=@($settings->setting_id)',
                    data: data,
                    dataType : 'array',
                    form : $('#settings-form'),
                    show_process:true,
                    callback:function(json){
                        if(json.success){
                            modAlert(json.message);
                            window.location.href = '@(Url::Home())settings';
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

