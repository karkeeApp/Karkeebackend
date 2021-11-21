@{
    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;

    use common\models\Admin;
    use common\helpers\Common;
}

@($menu)

<div class="tab-content">

        @{
            $form = ActiveForm::begin([
                'id' => 'admin-form', 
                'enableClientScript' => true,
                'fieldConfig' => [
                    'labelOptions' => ['class' => 'control-label'],
                    'template' => "<div class='col-xs-12 col-sm-12 col-md-3 col-lg-3'>{label}</div>\n<div class='col-xs-12 col-sm-12 col-md-9 col-lg-9'>{input}{error}</div>",
                    'options' => [
                    'class' => 'row mb10',
                    ]
                ],
            ]);
        }

            <div class="hide">
                @$form->field($adminForm, 'admin_id')->hiddenInput()

                <input type="hidden" name="action" value="@(($adminForm->admin_id) ? 'edit' : 'add')">
            </div>

            @$form->field($adminForm, 'email')->textInput(['class' => 'form-control input-sm'])
            @$form->field($adminForm, 'username')->textInput(['class' => 'form-control input-sm'])
            @$form->field($adminForm, 'password')->textInput(['class' => 'form-control input-sm'])
            @$form->field($adminForm, 'role')->dropDownList(['' => 'Select role'] + Admin::roles(), ['class' => 'form-control input-sm'])
            @$form->field($adminForm, 'status')->dropDownList(Admin::statuses(), ['class' => 'form-control input-sm'])
        @{ ActiveForm::end(); }

        <div class="row">
            <div class="text-right mt10">
                <button class="btn btn-primary btn-sm" id="save"><i class="fa fa-save"></i> Save</button>
            </div>
        </div>

</div>

<script type="text/javascript">
(function($) {
    $('#save')
    .bind('click', function(e) {
        e.preventDefault();

        var data = $('#admin-form').serialize();

        serverProcess({
            action:'admin/update',
            data: data,
            show_process:true,
            callback:function(json){
                if(json.success){
                    modAlert(json.message);
                    
                    @if(!isset($admin)) {
                        window.location.href = '@(Url::Home())admin/edit/' + json.admin_id;
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