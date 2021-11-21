@{
    use yii\widgets\ActiveForm;
    use common\helpers\Common;
    use common\models\User;
}

@($menu)

<ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#role"><i class="fa fa-lock"></i> Role</a></li>
</ul>


<div class="tab-content">
    <div id="role" class="tab-pane fade in active">
        <!-- h3><i class="fa fa-lock"></i> Password</h3 -->

        <div class="row">
            <div class="col-sm-6">
                @{
                    $form = ActiveForm::begin([
                        'id' => 'admin-role-form', 
                        'enableClientScript' => true,
                        'fieldConfig' => [
                            'labelOptions' => ['class' => 'col-sm-2 control-label'],
                            'template' => "{label}\n<div class=\"col-sm-10\">{input}{error}</div>",
                        ],
                    ]);
                }

                <div class="hide">
                    <input type="hidden" name="action" value="admin_add">
                    @$form->field($adminRoleForm, 'user_id')->textInput()
                </div>

                @$form->field($adminRoleForm, 'role')->dropDownList(User::roles(), ['class' => 'form-control input-sm', 'autocomplete' => 'off'])
                
                <div class="form-group">
                    <div class="col-sm-12 text-right">
                        <button class="btn btn-primary btn-sm" id="updatePassword"><i class="fa fa-save"></i> Submit</button>
                    </div>
                </div>

                @{ ActiveForm::end(); }
            </div>
        </div>

    </div>
</div>


<script type="text/javascript">
(function($) {
    var Settings = function() {
        var bindEvents = function() {
            $('.working_day').bind('click', function(e) {
                var day = $(this).attr('data-day');

                if ($(this).prop('checked') == true) {
                    $('input.working_' + day).prop('checked', false);

                    $(this).prop('checked', true);
                }
            });
            
        
            $('#updatePassword').bind('click', function(e) {
                e.preventDefault();

                modConfirm('Do you want to proceed?', function() {
                    serverProcess({
                        action:'accountadmin/update-role',
                        data: $('#admin-role-form').serialize() + '&user_id=@($user->user_id)',
                        show_process:true,
                        callback:function(json){
                            if(json.success){
                                window.location.reload();
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
        }
    }();

    $(document).ready(function() {
        Settings._construct();
    });

})(jQuery);
    
</script>