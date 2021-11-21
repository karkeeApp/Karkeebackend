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
            'labelOptions' => ['class' => 'col-sm-4 col-md-3 control-label'],
            'template' => "{label}\n<div class=\"col-sm-8 col-md-9\">{input}{error}</div>",
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
            <div id="address-information" class="tab-pane fade">
                <h2><i class="fa fa-info"></i> Vendor Information</h2>

                <div class="row mb10">
                    @Html::activeLabel($user, 'img_profile', ['class' => 'col-sm-4 col-md-3 control-label'])
                    <div class="col-sm-8 col-md-9">
                        @if($user->img_profile){
                            <img src="@($user->img_profile())" width="100">
                        }
                    </div>
                </div>

                <div class="row mb10">
                    @Html::activeLabel($user, 'vendor_name', ['class' => 'col-sm-4 col-md-3 control-label'])
                    @Html::activePrint($user, 'vendor_name', ['class' => 'col-sm-8 col-md-9'])
                </div>

                <div class="row mb10">
                    @Html::activeLabel($user, 'vendor_description', ['class' => 'col-sm-4 col-md-3 control-label'])
                    @Html::activePrint($user, 'vendor_description', ['class' => 'col-sm-8 col-md-9'])
                </div>

                <div class="row mb10">
                    @Html::activeLabel($user, 'about', ['class' => 'col-sm-4 col-md-3 control-label'])
                    @Html::activePrint($user, 'about', ['class' => 'col-sm-8 col-md-9'])
                </div>
            </div>
        }

        <div id="personal-information" class="tab-pane active">
            <div id="personal-details">
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

                <!--
                <div class="row mb10">
                    @Html::activeLabel($user, 'lastname', ['class' => 'col-sm-4 col-md-3 control-label'])
                    @Html::activePrint($user, 'lastname', ['class' => 'col-sm-8 col-md-9'])
                </div>

                <div class="row mb10">
                    @Html::activeLabel($user, 'firstname', ['class' => 'col-sm-4 col-md-3 control-label'])
                    @Html::activePrint($user, 'firstname', ['class' => 'col-sm-8 col-md-9'])
                </div>
                -->

                <div class="row mb10">
                    @Html::activeLabel($user, 'fullname', ['class' => 'col-sm-4 col-md-3 control-label'])
                    @Html::activePrint($user, 'fullname', ['class' => 'col-sm-8 col-md-9'])
                </div>

                <div class="row mb10">
                    @Html::activeLabel($user, 'nric', ['class' => 'col-sm-4 col-md-3 control-label'])
                    @Html::activePrint($user, 'nric', ['class' => 'col-sm-8 col-md-9'])
                </div>

                <div class="row mb10">
                    @Html::activeLabel($user, 'mobile', ['class' => 'col-sm-4 col-md-3 control-label'])
                    @Html::activePrint($user, 'mobile', ['class' => 'col-sm-8 col-md-9'])
                </div>

                <div class="row mb10">
                    @Html::activeLabel($user, 'email', ['class' => 'col-sm-4 col-md-3 control-label'])
                    @Html::activePrint($user, 'email', ['class' => 'col-sm-8 col-md-9'])
                </div>

                <div class="row mb10">
                    @Html::activeLabel($user, 'birthday', ['class' => 'col-sm-4 col-md-3 control-label'])
                    @Html::activePrint($user, 'birthday', ['class' => 'col-sm-8 col-md-9'])
                </div>

                <div class="row mb10">
                    @Html::activeLabel($user, 'gender', ['class' => 'col-sm-4 col-md-3 control-label'])
                    @Html::activePrint($user, 'gender', ['class' => 'col-sm-8 col-md-9'])
                </div>

                <div class="row mb10">
                    @Html::activeLabel($user, 'profession', ['class' => 'col-sm-4 col-md-3 control-label'])
                    @Html::activePrint($user, 'profession', ['class' => 'col-sm-8 col-md-9'])
                </div>
                
                <div class="row mb10">
                    @Html::activeLabel($user, 'company', ['class' => 'col-sm-4 col-md-3 control-label'])
                    @Html::activePrint($user, 'company', ['class' => 'col-sm-8 col-md-9'])
                </div>

                <div class="row mb10">
                    @Html::activeLabel($user, 'annual_salary', ['class' => 'col-sm-4 col-md-3 control-label'])
                    @Html::activePrint($user, 'annual_salary', ['class' => 'col-sm-8 col-md-9'])
                </div>

                <div class="row mb10">
                    @Html::activeLabel($user, 'status', ['class' => 'col-sm-4 col-md-3 control-label'])
                    @Html::activePrint($user, 'status', ['class' => 'col-sm-8 col-md-9'])
                </div>

                <div class="row mb10">
                    @Html::activeLabel($user, 'about', ['class' => 'col-sm-4 col-md-3 control-label'])
                    @Html::activePrint($user, 'about', ['class' => 'col-sm-8 col-md-9'])
                </div>
            </div>

            <div id="address-information">
                <h2><i class="fa fa-map-marker"></i> Address</h2>

                <div class="row mb10">
                    @Html::activeLabel($user, 'add_1', ['class' => 'col-sm-4 col-md-3 control-label'])
                    @Html::activePrint($user, 'add_1', ['class' => 'col-sm-8 col-md-9'])
                </div>

                <div class="row mb10">
                    @Html::activeLabel($user, 'add_2', ['class' => 'col-sm-4 col-md-3 control-label'])
                    @Html::activePrint($user, 'add_2', ['class' => 'col-sm-8 col-md-9'])
                </div>

                <div class="row mb10">
                    @Html::activeLabel($user, 'unit_no', ['class' => 'col-sm-4 col-md-3 control-label'])
                    @Html::activePrint($user, 'unit_no', ['class' => 'col-sm-8 col-md-9'])
                </div>

                <div class="row mb10">
                    @Html::activeLabel($user, 'postal_code', ['class' => 'col-sm-4 col-md-3 control-label'])
                    @Html::activePrint($user, 'postal_code', ['class' => 'col-sm-8 col-md-9'])
                </div>
                
                <div class="row mb10">
                    @Html::activeLabel($user, 'country', ['class' => 'col-sm-4 col-md-3 control-label'])
                    @Html::activePrint($user, 'country', ['class' => 'col-sm-8 col-md-9'])
                </div>
            </div>

            <div id="address-information">
                <h2><i class="fa fa-dollar"></i> Payment</h2>
        
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">Attachment</div>
                    <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">                        
                        <a href="@($user->transfer_screenshot())&size=large" target="_blank">
                            <img src="@($user->transfer_screenshot())">
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div id="vehicle-details" class="tab-pane fade">
            <h2><i class="fa fa-car"></i> Vehicle Details</h2>

            <div class="row mb10">
                @Html::activeLabel($user, 'chasis_number', ['class' => 'col-sm-4 col-md-3 control-label'])
                @Html::activePrint($user, 'chasis_number', ['class' => 'col-sm-8 col-md-9'])
            </div>

            <div class="row mb10">
                @Html::activeLabel($user, 'plate_no', ['class' => 'col-sm-4 col-md-3 control-label'])
                @Html::activePrint($user, 'plate_no', ['class' => 'col-sm-8 col-md-9'])
            </div>

            <div class="row mb10">
                @Html::activeLabel($user, 'car_model', ['class' => 'col-sm-4 col-md-3 control-label'])
                @Html::activePrint($user, 'car_model', ['class' => 'col-sm-8 col-md-9'])
            </div>

            <div class="row mb10">
                @Html::activeLabel($user, 'are_you_owner', ['class' => 'col-sm-4 col-md-3 control-label'])
                @Html::activePrint($user, 'are_you_owner', ['class' => 'col-sm-8 col-md-9'])
            </div>

            <div class="row mb10">
                @Html::activeLabel($user, 'registration_code', ['class' => 'col-sm-4 col-md-3 control-label'])
                @Html::activePrint($user, 'registration_code', ['class' => 'col-sm-8 col-md-9'])
            </div>
        </div>

        <div id="contact-person" class="tab-pane fade">
            <h2><i class="fa fa-ambulance"></i> In Case of Emergency</h2>

            <div class="row mb10">
                @Html::activeLabel($user, 'contact_person', ['class' => 'col-sm-4 col-md-3 control-label'])
                @Html::activePrint($user, 'contact_person', ['class' => 'col-sm-8 col-md-9'])
            </div>

            <div class="row mb10">
                @Html::activeLabel($user, 'emergency_no', ['class' => 'col-sm-4 col-md-3 control-label'])
                @Html::activePrint($user, 'emergency_no', ['class' => 'col-sm-8 col-md-9'])
            </div>

            <div class="row mb10">
                @Html::activeLabel($user, 'relationship', ['class' => 'col-sm-4 col-md-3 control-label'])
                @Html::activePrint($user, 'relationship', ['class' => 'col-sm-8 col-md-9'])
            </div>            
        </div>

        <div id="documents" class="tab-pane fade">
            <h2><i class="fa fa-paperclip"></i> Documents</h2>
            
            <div class="row mb10">
                @Html::activeLabel($user, 'img_nric', ['class' => 'col-sm-4 col-md-3 control-label'])
                <div class="col-sm-8 col-md-9">
                    <div class="grey-bg form-control input-sm">
                        @if($user->img_nric){
                            <a href="@($user->img_nric())" target="_blank"><i class="fa fa-download"></i> Download</a>
                        }
                    </div>
                </div>                
            </div>

            <div class="row mb10">
                @Html::activeLabel($user, 'img_insurance', ['class' => 'col-sm-4 col-md-3 control-label'])
                <div class="col-sm-8 col-md-9">
                    <div class="grey-bg form-control input-sm">
                        @if($user->img_insurance){
                            <a href="@($user->img_insurance())" target="_blank"><i class="fa fa-download"></i> Download</a>
                        }
                    </div>
                </div>                
            </div>

            <div class="row mb10">
                @Html::activeLabel($user, 'img_authorization', ['class' => 'col-sm-4 col-md-3 control-label'])
                <div class="col-sm-8 col-md-9">
                    <div class="grey-bg form-control input-sm">
                        @if($user->img_authorization){
                            <a href="@($user->img_authorization())" target="_blank"><i class="fa fa-download"></i> Download</a>
                        }
                    </div>
                </div>                
            </div>

            <div class="row mb10">
                @Html::activeLabel($user, 'img_log_card', ['class' => 'col-sm-4 col-md-3 control-label'])
                <div class="col-sm-8 col-md-9">
                    <div class="grey-bg form-control input-sm">
                        @if($user->img_log_card){
                            <a href="@($user->img_log_card())" target="_blank"><i class="fa fa-download"></i> Download</a>
                        }
                    </div>
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
                        action:'member/approve',
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
                        action:'member/reject',
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
