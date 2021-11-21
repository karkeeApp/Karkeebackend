@{
    use yii\widgets\ActiveForm;
    use common\helpers\Common;

    use common\models\User;
}

@($menu)

<ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#map"><i class="fa fa-info"></i> Map Coordinates</a></li>
    @if(Common::isCpanel()){
        <li><a data-toggle="tab" href="#member-type"><i class="fa fa-user"></i> Member Type</a></li>
    }
</ul>

<div class="tab-content">
    <div id="map" class="tab-pane active">
        <div class="row">
            <div class="col-sm-6">
                @{
                    $form = ActiveForm::begin([
                        'id' => 'mapsettings-form', 
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
                        <button class="btn btn-primary btn-sm" id="updateMapCoordinates"><i class="fa fa-save"></i> Submit</button>
                    </div>
                </div>

                @{ ActiveForm::end(); }
            </div>
        </div>
    </div>

    @if(Common::isCpanel()){
        <div id="member-type" class="tab-pane">
            <div class="row">
                <div class="col-sm-6">
                    @{
                        $form = ActiveForm::begin([
                            'id' => 'settings-form', 
                            'enableClientScript' => true,
                            'fieldConfig' => [
                                'labelOptions' => ['class' => 'col-sm-4 control-label'],
                                'template' => "{label}\n<div class=\"col-sm-8\">{input}{error}</div>",
                            ],
                        ]);
                    }

                    <div class="hide">
                        <input type="hidden" name="action" value="add">
                        @$form->field($clubSettingsForm, 'user_id')->textInput()
                    </div>
                    
                    @$form->field($clubSettingsForm, 'carkee_member_type')->dropDownList(User::carkeeClubMemberTypes(), ['class' => 'form-control input-sm'])                

                    <div class="form-group">
                        <div class="col-sm-12 text-right">
                            <button class="btn btn-primary btn-sm" id="updateSettings"><i class="fa fa-save"></i> Submit</button>
                        </div>
                    </div>

                    @{ ActiveForm::end(); }
                </div>
            </div>
        </div>
    }
</div>


<script type="text/javascript">
(function($) {
    var Settings = function() {
        var bindEvents = function() {
            $('#updateMapCoordinates').bind('click', function(e) {
                e.preventDefault();

                modConfirm('Do you want to proceed?', function() {
                    serverProcess({
                        action:'account/update-map-coordinates',
                        data: $('#mapsettings-form').serialize() + '&account_id=@($account->account_id)',
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

            $('#updateSettings').bind('click', function(e) {
                e.preventDefault();

                modConfirm('Do you want to proceed?', function() {
                    serverProcess({
                        action:'account/update-membership',
                        data: $('#settings-form').serialize() + '&account_id=@($account->account_id)',
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