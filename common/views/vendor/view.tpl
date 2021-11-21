@{
    use yii\helpers\Url;
    use common\helpers\Html;
    use yii\widgets\ActiveForm;
    use common\helpers\Common;
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
    <ul class="nav nav-tabs mb10">
        <li class="active"><a data-toggle="tab" href="#personal-information"><i class="fa fa-info"></i> Personal Information</a></li>
        @if($user->isVendor()) {
            <li><a data-toggle="tab" href="#address-information"><i class="fa fa-info"></i> Vendor Information</a></li>
        }        
        <!-- <li><a data-toggle="tab" href="#payment"><i class="fa fa-dollar"></i> Payment</a></li> -->
        <li><a data-toggle="tab" href="#vehicle-details"><i class="fa fa-car"></i> Vehicle Details</a></li>
        <li><a data-toggle="tab" href="#contact-person"><i class="fa fa-ambulance"></i> In Case of Emergency</a></li>
        <li><a data-toggle="tab" href="#documents"><i class="fa fa-paperclip"></i> Documents</a></li>
    </ul>

    <div>
        <i class="small">Authkey: @($user->auth_key)</i>
    </div>

    <div class="tab-content">
        @if($user->isVendor()) {
            <div id="address-information" class="tab-pane fade container">
                <h2><i class="fa fa-info"></i> Vendor Information</h2>

                <div class="row mb10">
                    @Html::activeLabel($user, 'img_profile', ['class' => 'col-sm-4 col-md-3 control-label'])
                    <div class="col-sm-8 col-md-9">
                        @if($user->img_profile){
                            <img src="@($user->img_profile())" width="100">
                        }
                    </div>
                </div>
                
                @$form->field($user, 'vendor_name')->textInput(['class' => 'form-control input-sm', 'disabled' => true])
                @$form->field($user, 'vendor_description')->textInput(['class' => 'form-control input-sm', 'disabled' => true])
                @$form->field($user, 'about')->textArea(['class' => 'form-control input-sm', 'disabled' => true])

            </div>
        }

        <div id="personal-information" class="tab-pane active">
            <div id="personal-details" class="container">
                <h2><i class="fa fa-user"></i> Personal Information</h2>

                @if(!$user->isVendor()) {
                    <div class="row mb10">
                        @Html::activeLabel($user, 'img_profile', ['class' => 'col-sm-4 col-md-3 control-label'])
                        <div class="col-sm-8 col-md-9">
                            @if($user->img_profile){
                                <img src="@($user->img_profile())" width="100">
                            }
                        </div>
                    </div>
                }
                
                @$form->field($user, 'fullname')->textInput(['class' => 'form-control input-sm', 'disabled' => true])
                @$form->field($user, 'nric')->textInput(['class' => 'form-control input-sm', 'disabled' => true])
                @$form->field($user, 'mobile')->textInput(['class' => 'form-control input-sm', 'disabled' => true])
                @$form->field($user, 'email')->textInput(['class' => 'form-control input-sm', 'disabled' => true])
                @$form->field($user, 'birthday')->textInput(['class' => 'form-control input-sm', 'disabled' => true])
                @$form->field($user, 'gender')->textInput(['class' => 'form-control input-sm', 'value' => $user->gender(), 'disabled' => true])
                @$form->field($user, 'profession')->textInput(['class' => 'form-control input-sm', 'disabled' => true])
                @$form->field($user, 'company')->textInput(['class' => 'form-control input-sm', 'disabled' => true])
                @$form->field($user, 'annual_salary')->textInput(['class' => 'form-control input-sm', 'disabled' => true])
                @$form->field($user, 'status')->textInput(['class' => 'form-control input-sm', 'value' => $user->status(), 'disabled' => true])
                @$form->field($user, 'about')->textArea(['class' => 'form-control input-sm', 'disabled' => true])

               
            </div>

            <div id="address-information" class="container">
                <h2><i class="fa fa-map-marker"></i> Address</h2>

                @$form->field($user, 'add_1')->textInput(['class' => 'form-control input-sm', 'disabled' => true])
                @$form->field($user, 'add_2')->textInput(['class' => 'form-control input-sm', 'disabled' => true])
                @$form->field($user, 'unit_no')->textInput(['class' => 'form-control input-sm', 'disabled' => true])
                @$form->field($user, 'postal_code')->textInput(['class' => 'form-control input-sm', 'disabled' => true])
                @$form->field($user, 'country')->textInput(['class' => 'form-control input-sm', 'disabled' => true])

            </div>

            <div id="address-information" class="container">
                <h2><i class="fa fa-dollar"></i> Payment</h2>
        
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">Attachment</div>
                    <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">                        
                         <img src="@($user->transfer_screenshot())" class="img-fluid" style="max-width: 100%; height: auto;">
                       
                    </div>
                </div>
            </div>
        </div>

        <div id="vehicle-details" class="tab-pane fade container">>
            <h2><i class="fa fa-car"></i> Vehicle Details</h2>

            @$form->field($user, 'chasis_number')->textInput(['class' => 'form-control input-sm', 'disabled' => true])
            @$form->field($user, 'plate_no')->textInput(['class' => 'form-control input-sm', 'disabled' => true])
            @$form->field($user, 'car_model')->textInput(['class' => 'form-control input-sm', 'disabled' => true])
            @$form->field($user, 'are_you_owner')->textInput(['class' => 'form-control input-sm', 'value' => $user->are_you_owner(), 'disabled' => true])
            @$form->field($user, 'registration_code')->textInput(['class' => 'form-control input-sm', 'disabled' => true])

        </div>

        <div id="contact-person" class="tab-pane fade container">>
            <h2><i class="fa fa-ambulance"></i> In Case of Emergency</h2>

            @$form->field($user, 'contact_person')->textInput(['class' => 'form-control input-sm', 'disabled' => true])
            @$form->field($user, 'emergency_code')->textInput(['class' => 'form-control input-sm', 'disabled' => true])
            @$form->field($user, 'emergency_no')->textInput(['class' => 'form-control input-sm', 'disabled' => true])
            @$form->field($user, 'relationship')->textInput(['class' => 'form-control input-sm', 'value' => $user->relationship(), 'disabled' => true])
      
        </div>

        <div id="documents" class="tab-pane fade container">>
            <h2><i class="fa fa-paperclip"></i> Documents</h2>

            <div class="row mb10">
                <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                    @Html::activeLabel($user, 'img_nric', ['class' => 'control-label'])
                </div>
                <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
                    <span class="grey-bg form-control input-sm">
                        @if($user->img_nric){
                            <a href="@($user->img_nric())" download><i class="fa fa-download"></i> Download</a>
                        }
                    </span>             
                </div>             
            </div>

            <div class="row mb10">
                <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                    @Html::activeLabel($user, 'img_insurance', ['class' => 'control-label'])
                </div>
                <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
                    <span class="grey-bg form-control input-sm">
                        @if($user->img_insurance){
                            <a href="@($user->img_insurance())" download><i class="fa fa-download"></i> Download</a>
                        }
                    </span>             
                </div>             
            </div>

            <div class="row mb10">
                <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                    @Html::activeLabel($user, 'img_authorization', ['class' => 'control-label'])
                </div>
                <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
                    <span class="grey-bg form-control input-sm">
                        @if($user->img_authorization){
                            <a href="@($user->img_authorization())" download><i class="fa fa-download"></i> Download</a>
                        }
                    </span>             
                </div>             
            </div>

            <div class="row mb10">
                <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                    @Html::activeLabel($user, 'img_log_card', ['class' => 'control-label'])
                </div>
                <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
                    <span class="grey-bg form-control input-sm">
                        @if($user->img_log_card){
                            <a href="@($user->img_log_card())" download><i class="fa fa-download"></i> Download</a>
                        }
                    </span>             
                </div>             
            </div>
        </div>
    </div>

    <div class="text-right">
        @if($user->isPending() AND $user->account_id == $account->account_id){
            <button class="btn btn-sm btn-primary" id="reject">Reject</button>
            @if(!$user->approved_by) {
                <button class="btn btn-sm btn-success" id="approve">Approve</button>
            } else {
                <button class="btn btn-sm btn-success" id="approve">Confirm</button>
            }
        }
    </div>

@{ ActiveForm::end(); }

<script type="text/javascript">
(function($) {
    var Profile = function() {
        var bindEvents = function() {
            $('#approve').bind('click', function(e) {
                e.preventDefault();

                @if (!$user->approved_by) {
                    var message = 'Do you want to approve this member?';
                } else {
                    var message = 'Would you like to confirm this membership payment?';
                }

                modConfirm(message, function() {
                    serverProcess({
                        action:'vendor/approve',
                        data: {
                            user_id : @($user->user_id)
                        },
                        show_process:true,
                        callback:function(json){
                            if(json.success){
                                modAlert(json.message, function() {
                                    window.location.reload();
                                });
                            }else{
                                modAlert(json.error);
                            }
                        }
                    });
                });

            });

            $('#reject').bind('click', function(e) {
                e.preventDefault();

                modConfirm('Are you sure?', function() {
                    serverProcess({
                        action:'vendor/reject',
                        data: {
                            user_id : @($user->user_id)
                        },
                        show_process:true,
                        callback:function(json){
                            if(json.success){
                                modAlert(json.message, function() {
                                    window.location.reload();
                                });
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
