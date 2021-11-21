@{
    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use common\forms\UserForm;
    use common\models\User;
}

@($menu)

@{
    $form = ActiveForm::begin([
        'id' => 'user-form', 
        'enableClientScript' => true,
        'fieldConfig' => [
            'labelOptions' => ['class' => 'col-xs-12 col-sm-12 col-md-4 col-lg-4 control-label'],
            'template' => "{label}\n<div class=\"col-xs-12 col-sm-12 col-md-8 col-lg-8\">{input}{error}</div>",
        ],
    ]);
}

    <div class="hide">
        @$form->field($userForm, 'user_id')->hiddenInput()

        <input type="hidden" name="action" value="@(($userForm->user_id) ? 'edit_vendor' : 'add_vendor')">
    </div>

    @$form->field($userForm, 'email')->textInput(['class' => 'form-control input-sm'])
    @$form->field($userForm, 'password')->textInput(['class' => 'form-control input-sm'])

    <div class="clearboth"></div>
    <hr />

    @$form->field($userForm, 'vendor_name')->textInput(['class' => 'form-control input-sm'])
    @$form->field($userForm, 'vendor_description')->textArea(['class' => 'form-control input-sm'])
    @$form->field($userForm, 'founded_date')->textInput(['class' => 'form-control input-sm'])
    @$form->field($userForm, 'about')->textArea(['class' => 'form-control input-sm'])

    <div class="clearboth"></div>
    <hr />

    @$form->field($userForm, 'mobile_code')->textInput(['class' => 'form-control input-sm'])
    @$form->field($userForm, 'mobile')->textInput(['class' => 'form-control input-sm'])
    @$form->field($userForm, 'telephone_code')->textInput(['class' => 'form-control input-sm'])
    @$form->field($userForm, 'telephone_no')->textInput(['class' => 'form-control input-sm'])

    <div class="clearboth"></div>
    <hr />

    @$form->field($userForm, 'country')->textInput(['class' => 'form-control input-sm'])
    @$form->field($userForm, 'postal_code')->textInput(['class' => 'form-control input-sm'])
    @$form->field($userForm, 'unit_no')->textInput(['class' => 'form-control input-sm'])
    @$form->field($userForm, 'add_1')->textInput(['class' => 'form-control input-sm'])

    @$form->field($userForm, 'status')->dropDownList(User::statuses(), ['class' => 'form-control input-sm'])

@{ ActiveForm::end(); }

<div class="clearboth"></div>

<div class="row">
    <div class="col-lg-12 text-right">
        <button class="btn btn-primary btn-sm" id="save"><i class="fa fa-save"></i> Save</button>
    </div>
</div>

<script type="text/javascript">
(function($) {
    $('#save')
    .bind('click', function(e) {
        e.preventDefault();

        serverProcess({
            @if(isset($user)) {
                action:'member/edit-vendor',
                data: $('#user-form').serialize() + '&user_id=@($user->user_id)',
            } else {
                action:'member/add-vendor',
                data: $('#user-form').serialize(),                
            }
            show_process:true,
            callback:function(json){
                if(json.success){
                    $('#user-form').trigger('reset');
                    window.location.href = '@(Url::Home())member';
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