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

        <input type="hidden" name="action" value="@(($userForm->user_id) ? 'edit_docs' : 'add_member')">
    </div>

    <div class="container">
        <h4><i class="fa fa-user"></i> Documents</h4>


        @$form->field($userForm, 'img_nric', ['template' => "<div class='col-xs-12 col-sm-12 col-md-3 col-lg-3'>{label}</div>\n<div class='col-xs-12 col-sm-12 col-md-9 col-lg-9'>{input}<div class='small'><i>Allowed files: png, jpg, jpeg</i></div>{error}</div>"])->fileInput(['class' => ''])
        @$form->field($userForm, 'img_insurance', ['template' => "<div class='col-xs-12 col-sm-12 col-md-3 col-lg-3'>{label}</div>\n<div class='col-xs-12 col-sm-12 col-md-9 col-lg-9'>{input}<div class='small'><i>Allowed files: png, jpg, jpeg</i></div>{error}</div>"])->fileInput(['class' => ''])
        @$form->field($userForm, 'img_authorization', ['template' => "<div class='col-xs-12 col-sm-12 col-md-3 col-lg-3'>{label}</div>\n<div class='col-xs-12 col-sm-12 col-md-9 col-lg-9'>{input}<div class='small'><i>Allowed files: png, jpg, jpeg</i></div>{error}</div>"])->fileInput(['class' => ''])
        @$form->field($userForm, 'img_log_card', ['template' => "<div class='col-xs-12 col-sm-12 col-md-3 col-lg-3'>{label}</div>\n<div class='col-xs-12 col-sm-12 col-md-9 col-lg-9'>{input}<div class='small'><i>Allowed files: png, jpg, jpeg</i></div>{error}</div>"])->fileInput(['class' => ''])

        
        <ul class="list-inline pull-right">
            <li><button type="button" class="btn btn-primary btn-sm " id="next-step" data-action='edit_personal_information'><i class="fa fa-save"></i> Save</button></li>
        </ul>

        
    </div>
@{ ActiveForm::end(); }

<script type="text/javascript">
(function($) {
    var Profile = function() {
        var bindEvents = function() {
          

            $(".next-step").bind('click', function (e) {
                var data = $('#user-form').serialize();

                serverProcess({
                    action:'member/edit-docs/' + '?user_id=@($user->user_id)',
                    data: data ,
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


                var parent = $('#docsform-image').parent();

                $('input[type=hidden]', parent).val($('#docsform-image-image').val());


                var data = new FormData($('#user-form')[0]);
                var url = app.urlsite;
                url += 'server/member/edit-docs' + '?id=@($user->user_id)';
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
                                window.location.href = '@(Url::home())member/@($user->user_id)';
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
            });

            $('#docsform-image').bind('change', function(e) {
                readURL(this,'#nric-img');
                $("#div-nric-img").show('slow');
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
