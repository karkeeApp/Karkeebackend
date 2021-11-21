@{
    use yii\helpers\Url;

    use yii\widgets\ActiveForm;

    use common\forms\UserForm;
    use common\helpers\Html;
    use common\helpers\Common;

    use common\models\User;
}

@($member_menu)

<h4><i class="fa fa-cog"></i> Settings</h4>

<ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#general"><i class="fa fa-cog"></i> General</a></li>
    <li><a data-toggle="tab" href="#password"><i class="fa fa-lock"></i> Password</a></li>
    <li><a data-toggle="tab" href="#email"><i class="fa fa-envelope"></i> Email Address</a></li>
    <li><a data-toggle="tab" href="#mobile"><i class="fa fa-mobile"></i> Mobile</a></li>
    <li><a data-toggle="tab" href="#map"><i class="fa fa-map-marker"></i> Map Coordinates</a></li>
</ul>

<div class="tab-content">
    <div id="general" class="tab-pane active">
        <div class="row">
            <div class="col-sm-6">
                TODO
            </div>
        </div>
    </div>

    <div id="password" class="tab-pane">
        <!-- h3><i class="fa fa-lock"></i> Password</h3 -->

        <div class="row">
            <div class="col-sm-6">
                @{
                    $form = ActiveForm::begin([
                        'id' => 'password-form', 
                        'enableClientScript' => true,
                        'fieldConfig' => [
                            'labelOptions' => ['class' => 'col-sm-4 control-label'],
                            'template' => "{label}\n<div class=\"col-sm-8\">{input}{error}</div>",
                        ],
                    ]);
                }

                <div class="hide">
                    <input type="hidden" name="action" value="add">
                    @$form->field($passwordForm, 'user_id')->textInput()
                </div>
                
                @$form->field($passwordForm, 'new')->passwordInput(['class' => 'form-control input-sm', 'autocomplete' => 'off'])

                <div class="form-group">
                    <div class="col-sm-12 text-right">
                        <button class="btn btn-primary btn-sm" id="updatePassword"><i class="fa fa-save"></i> Submit</button>
                    </div>
                </div>

                @{ ActiveForm::end(); }
            </div>
        </div>

    </div>

    <div id="email" class="tab-pane fade">
        <!-- h3><i class="fa fa-envelope"></i> Email Address</h3 -->

        <div class="row">
            <div class="col-sm-6">
                @{
                    $form = ActiveForm::begin([
                        'id' => 'email-form', 
                        'enableClientScript' => true,
                        'fieldConfig' => [
                            'labelOptions' => ['class' => 'col-sm-4 control-label'],
                            'template' => "{label}\n<div class=\"col-sm-8\">{input}{error}</div>",
                        ],
                    ]);
                }

                <div class="hide">
                    <input type="hidden" name="action" value="add">
                    @$form->field($emailForm, 'user_id')->textInput()
                </div>

                @$form->field($emailForm, 'email')->textInput(['class' => 'form-control input-sm'])

                <div class="form-group">
                    <div class="col-sm-12 text-right">
                        <button class="btn btn-primary btn-sm" id="updateEmail"><i class="fa fa-save"></i> Submit</button>
                    </div>
                </div>

                @{ ActiveForm::end(); }
            </div>
        </div>
    </div>

    <div id="mobile" class="tab-pane fade">
        <!-- h3><i class="fa fa-mobile"></i> Mobile Number</h3 -->

        <div class="row">
            <div class="col-sm-6">
                @{
                    $form = ActiveForm::begin([
                        'id' => 'mobile-form', 
                        'enableClientScript' => true,
                        'fieldConfig' => [
                            'labelOptions' => ['class' => 'col-sm-4 control-label'],
                            'template' => "{label}\n<div class=\"col-sm-8\">{input}{error}</div>",
                        ],
                    ]);
                }

                <div class="hide">
                    <input type="hidden" name="action" value="add">
                    @$form->field($mobileForm, 'user_id')->textInput()
                </div>

                @$form->field($mobileForm, 'mobile_code')->textInput(['class' => 'form-control input-sm'])
                @$form->field($mobileForm, 'mobile')->textInput(['class' => 'form-control input-sm'])

                <div class="form-group">
                    <div class="col-sm-12 text-right">
                        <button class="btn btn-primary btn-sm" id="updateMobile"><i class="fa fa-save"></i> Submit</button>
                    </div>
                </div>

                @{ ActiveForm::end(); }
            </div>
        </div>
    </div>

    <div id="map" class="tab-pane">
        <div class="row">
            <div class="col-sm-6">
                @{
                    $form = ActiveForm::begin([
                        'id' => 'map-settings-form', 
                        'enableClientScript' => true,
                        'fieldConfig' => [
                            'labelOptions' => ['class' => 'col-sm-4 control-label'],
                            'template' => "{label}\n<div class=\"col-sm-8\">{input}{error}</div>",
                        ],
                    ]);
                }

                <div class="hide">
                    <input type="hidden" name="action" value="add">
                    @$form->field($mapSettingsForm, 'user_id')->textInput()
                </div>

                @$form->field($mapSettingsForm, 'longitude')->textInput(['class' => 'form-control input-sm'])            
                @$form->field($mapSettingsForm, 'latitude')->textInput(['class' => 'form-control input-sm'])            


                <div class="form-group">
                    <div class="col-sm-12 text-right">
                        <button class="btn btn-primary btn-sm" id="updateMapCoordinate"><i class="fa fa-save"></i> Submit</button>
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
            $('#updateSettings').bind('click', function(e) {
                e.preventDefault();

                modConfirm('Do you want to proceed?', function() {
                    serverProcess({
                        action:'vendor/updatesettings',
                        data: $('#settings-form').serialize() + '&user_id=@($user->user_id)',
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
            });

            $('#updatePassword').bind('click', function(e) {
                e.preventDefault();

                modConfirm('Do you want to proceed?', function() {
                    serverProcess({
                        action:'vendor/updatepassword',
                        data: $('#password-form').serialize() + '&user_id=@($user->user_id)',
                        show_process:true,
                        callback:function(json){
                            if(json.success){
                                modAlert(json.message);
                                $('#password-form').trigger('reset');
                            } else if(typeof(json.errorFields) == 'object'){
                                window.highlightErrors(json.errorFields);
                            }else{
                                modAlert(json.error);
                            }
                        }
                    });
                });
            });

            $('#updateEmail').bind('click', function(e) {
                e.preventDefault();

                modConfirm('Do you want to proceed?', function() {
                    serverProcess({
                        action:'vendor/updateemail',
                        data: $('#email-form').serialize() + '&user_id=@($user->user_id)',
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
            });

            $('#updateMobile').bind('click', function(e) {
                e.preventDefault();

                modConfirm('Do you want to proceed?', function() {
                    serverProcess({
                        action:'vendor/updatemobile',
                        data: $('#mobile-form').serialize() + '&user_id=@($user->user_id)',
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
            });

            $('#updateMapCoordinate').bind('click', function(e) {
                e.preventDefault();

                modConfirm('Do you want to proceed?', function() {
                    serverProcess({
                        action:'vendor/update-coordinate',
                        data: $('#map-settings-form').serialize() + '&user_id=@($user->user_id)',
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