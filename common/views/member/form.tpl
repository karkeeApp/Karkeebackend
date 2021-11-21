@{
    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use common\forms\UserForm;
    use common\models\User;
    use common\models\Country;
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
    <div class="hide">
        @$form->field($userForm, 'user_id')->hiddenInput()

        <input type="hidden" name="action" value="@(($userForm->user_id) ? 'edit_member' : 'add_member')">
    </div>

    <div class="container">
        <h4><i class="fa fa-user"></i> Personal Information</h4>

        @$form->field($userForm, 'fullname')->textInput(['class' => 'form-control input-sm'])
        @$form->field($userForm, 'nric')->textInput(['class' => 'form-control input-sm'])
        @$form->field($userForm, 'birthday')->textInput(['class' => 'form-control input-sm'])
        @$form->field($userForm, 'gender')->dropDownList(UserForm::genders(), ['class' => 'form-control input-sm'])
        @$form->field($userForm, 'profession')->textInput(['class' => 'form-control input-sm'])
        @$form->field($userForm, 'company')->textInput(['class' => 'form-control input-sm'])
        @$form->field($userForm, 'annual_salary')->textInput(['class' => 'form-control input-sm'])
        @$form->field($userForm, 'about')->textArea(['class' => 'form-control input-sm'])
        @if($logged_user->isAdminOrMemDirectory()){ 
            @$form->field($userForm, 'member_expire')->textInput(['class' => 'form-control input-sm'])
        }else{ 
            @$form->field($userForm, 'member_expire')->textInput(['class' => 'form-control input-sm', 'disabled' => true])
        }

        <h4><i class="fa fa-briefcase"></i> Address</h4>
        @$form->field($userForm, 'add_1')->textInput(['class' => 'form-control input-sm'])
        @$form->field($userForm, 'add_2')->textInput(['class' => 'form-control input-sm'])
        @$form->field($userForm, 'unit_no')->textInput(['class' => 'form-control input-sm'])
        @$form->field($userForm, 'postal_code')->textInput(['class' => 'form-control input-sm'])
        @$form->field($userForm, 'country')->dropDownList(Country::all(), ['class' => 'form-control input-sm'])


        <h4><i class="fa fa-info"></i> Vehicle Details</h4>
        @$form->field($userForm, 'chasis_number')->textInput(['class' => 'form-control input-sm'])
        @$form->field($userForm, 'plate_no')->textInput(['class' => 'form-control input-sm'])
        @$form->field($userForm, 'car_model')->textInput(['class' => 'form-control input-sm'])
        @$form->field($userForm, 'are_you_owner')->dropDownList(User::carkeeOwnerOptions(), ['class' => 'form-control input-sm'])
        @$form->field($userForm, 'registration_code')->textInput(['class' => 'form-control input-sm'])

        <h4><i class="fa fa-briefcase"></i> In Case of Emergency</h4>
        @$form->field($userForm, 'contact_person')->textInput(['class' => 'form-control input-sm'])
        @$form->field($userForm, 'emergency_code')->textInput(['class' => 'form-control input-sm'])
        @$form->field($userForm, 'emergency_no')->textInput(['class' => 'form-control input-sm'])
        @$form->field($userForm, 'relationship')->dropDownList(User::relationships(), ['class' => 'form-control input-sm'])

        @if($logged_user->isRoleSuperAdmin()){
            @$form->field($userForm, 'transfer_screenshot')->fileInput(['class' => ''])
        }
        
        <ul class="list-inline pull-right">
            <li><button type="button" class="btn btn-primary btn-sm " id="next-step" data-action='edit_personal_information'><i class="fa fa-save"></i> Save</button></li>
        </ul>

        
    </div>
@{ ActiveForm::end(); }

<script type="text/javascript">
(function($) {
    var Profile = function() {
        var bindEvents = function() {
            $('#userform-birthday').datepicker({
                format : 'yyyy-mm-dd',
                todayHighlight: true
            });
            $('#userform-member_expire').datepicker({
                format : 'yyyy-mm-dd',
                todayHighlight: true
            });

            $('#addFile').bind('click', function(e) {
                e.preventDefault();

                $('#uploadIdentityModal').modal();
            });

            $('#uploadIdentity').bind('click', function(e) {
                // e.preventDefault();

                // var parent = $('#fileform-filename').parent();

                // $('input[type=hidden]', parent).val($('#fileform-filename').val());

                // var data = $('#file-form').serializeArray();

                // data.push({
                //     name : 'user_id',
                //     value : '@($user->user_id)'
                // });

                // data.push({
                //     name : 'action',
                //     value : 'add'
                // });

                // serverProcess({
                //     action:'($controller)/attach',
                //     data: data,
                //     dataType : 'array',
                //     form : $('#file-form'),
                //     show_process:true,
                //     callback:function(json){
                //         if(json.success){
                //             $('#uploadIdentityModal').modal('hide');
                //             $('#file-form').trigger('reset');

                //             identityReload();
                //         } else if(typeof(json.errorFields) == 'object'){
                //             window.highlightErrors(json.errorFields);
                //         }else{
                //             modAlert(json.error);
                //         }
                //     }
                // });
            });

            $(".next-step").bind('click', function (e) {
                var data = $('#user-form').serialize();

                serverProcess({
                    action:'member/update',
                    data: data + '&user_id=@($user->user_id)',
                    show_process:true,
                    callback:function(json){
                        if(json.success){
                            modAlert(json.message);
                        } else if(typeof(json.errorFields) == 'object'){
                            window.highlightErrors(json.errorFields);
                        }else{
                            modAlert(json.error);
                        }
                    }
                });
            });

            $('#next-step').bind('click', function(e) {
                e.preventDefault();
                var data = new FormData($('#user-form')[0]);
                var url = app.urlsite;
                url += 'server/member/update';
                data.append('user_id','@($user->user_id)');
                modConfirm('Do you want to proceed?', function() {
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: data,
                        processData:false,
                        contentType:false,
                        show_process:true,
                        success:function(json){
                            if(json.success){
                                modAlert(json.message);
                            } else if(typeof(json.errorFields) == 'object'){
                                window.highlightErrors(json.errorFields);
                            }else{
                                modAlert(json.error);
                            }
                        }
                    });
                });
            });
        };

        return {
            _construct : function() {
                bindEvents();
            }
        };
    }();

    $(document).ready(function() {
        Profile._construct();
    });

})(jQuery);

</script>
