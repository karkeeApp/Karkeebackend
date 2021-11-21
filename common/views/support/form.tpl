@{
use yii\helpers\Url;

use yii\widgets\ActiveForm;
use \common\models\Support;

$token = Yii::$app->security->generateRandomString();
}

@($menu)

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
        <h4><i class="fa fa-file"></i> @($support ? 'Update' : 'Create')</h4>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-right">
        <!-- <a href="javascript:void(0)" onclick="$('#addMediaModal').modal();" class="btn btn-sm btn-default"><i class="fa fa-plus"></i> Add Media Library</a> -->
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
        'id' => 'support-form',
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
        @$form->field($supportForm, 'id')->hiddenInput()

        <input type="hidden" name="action" value="@(($supportForm->id) ? 'edit' : 'add')">
    </div>

    @$form->field($supportForm, 'title')->textInput(['class' => 'form-control input-sm'])
    @$form->field($supportForm, 'description')->textInput(['class' => 'form-control input-sm'])

    @{ ActiveForm::end(); }
</div>


<div class="text-right mt10">
    <button type="button" class="btn btn-primary btn-sm" id="saveSupport"><i class="fa fa-save"></i> Save</button>
</div>
</div>




<script type="text/javascript">
    (function($) {
        $('#saveSupport')
            .bind('click', function(e) {
                e.preventDefault();

                var data = $('#support-form').serializeArray();

                serverProcess({
                    action:'support/@($support ? 'update' : 'create')',
                    data: data,
                    dataType : 'array',
                    form : $('#support-form'),
                    show_process:true,
                    callback:function(json){
                        if(json.success){
                            modAlert(json.message);
                            window.location.href = '@(Url::Home())support';
                        @if(!$supportForm->id) {
                                $('#support-form').trigger('reset');

                            }
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

