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
    <div>
        <i class="small">Member Exipry: @($user->member_expire)</i>
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
            @if(!is_null($user->getRenewals()->one()) || !is_null($user->transfer_screenshot)){
                <div id="address-information" class="container">
                    <h2><i class="fa fa-dollar"></i> Payment</h2>
            
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">Attachment</div>
                        <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
                            @if(!is_null($user->getRenewals()->one())){

                                @if($user->getRenewals()->one()->isImage()){
                                    <img src="@($user->getRenewals()->one()->docLink())" class="img-fluid" style="max-width: 100%; height: auto;">
                                } else{
                                    <a href="@($user->getRenewals()->one()->docLink())" download><i class="fa fa-download"></i> Download</a>
                                }
                                
                            } elseif(!is_null($user->transfer_screenshot)){
                                <img src="@($user->transfer_screenshot())" class="img-fluid" style="max-width: 100%; height: auto;">
                            }           
                           
                        </div>
                    </div>
                </div>
            }
        </div>

        <div id="vehicle-details" class="tab-pane fade container">
            <h2><i class="fa fa-car"></i> Vehicle Details</h2>

            @$form->field($user, 'chasis_number')->textInput(['class' => 'form-control input-sm', 'disabled' => true])
            @$form->field($user, 'plate_no')->textInput(['class' => 'form-control input-sm', 'disabled' => true])
            @$form->field($user, 'car_model')->textInput(['class' => 'form-control input-sm', 'disabled' => true])
            @$form->field($user, 'are_you_owner')->textInput(['class' => 'form-control input-sm', 'value' => $user->are_you_owner(), 'disabled' => true])
            @$form->field($user, 'registration_code')->textInput(['class' => 'form-control input-sm', 'disabled' => true])


        </div>

        <div id="contact-person" class="tab-pane fade container">
            <h2><i class="fa fa-ambulance"></i> In Case of Emergency</h2>

            @$form->field($user, 'contact_person')->textInput(['class' => 'form-control input-sm', 'disabled' => true])
            @$form->field($user, 'emergency_code')->textInput(['class' => 'form-control input-sm', 'disabled' => true])
            @$form->field($user, 'emergency_no')->textInput(['class' => 'form-control input-sm', 'disabled' => true])
            @$form->field($user, 'relationship')->textInput(['class' => 'form-control input-sm', 'value' => $user->relationship(), 'disabled' => true])
      
        </div>

        <div class="" style="float: right">
            <a href="@(Url::home())member/edit-docs/@($user->user_id)" update><i class="fa fa-edit"></i> update</a>
        </div>

        <div id="documents" class="tab-pane fade container">
            <h2><i class="fa fa-paperclip"></i> Documents</h2>
           
            <div class="row mb10">
                <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                    @Html::activeLabel($user, 'img_nric', ['class' => 'control-label'])
                </div>
                <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
                    <span class="grey-bg form-control input-sm">
                        @if(!empty($user->img_nric) && $user->img_nric !== 'NULL'){
                            <a href="@($user->img_nric())" download><i class="fa fa-download"></i> Download</a>  
                        }

                        <div class="" style="float: right">
                            @if(!empty($user->img_nric) && $user->img_nric !== 'NULL'){
                                <a href="javascript:void(0);" id="delete-nric" data-id="@($user->user_id)"><i class="fa fa-trash"></i> delete</a>
                            }
                        </div>
                        
                    </span>             
                </div>             
            </div>

            <div class="row mb10">
                <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                    @Html::activeLabel($user, 'img_insurance', ['class' => 'control-label'])
                </div>
                <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
                    <span class="grey-bg form-control input-sm">
                        @if(!empty($user->img_insurance) && $user->img_insurance !== 'NULL'){
                            <a href="@($user->img_insurance())" download><i class="fa fa-download"></i> Download</a>
                        }

                        <div class="" style="float: right">
                            @if(!empty($user->img_insurance) && $user->img_insurance !== 'NULL'){
                                <a href="javascript:void(0);" id="delete-ins" data-id="@($user->user_id)"><i class="fa fa-trash"></i> delete</a>
                            }
                        </div>
                    </span>             
                </div>             
            </div>

            <div class="row mb10">
                <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                    @Html::activeLabel($user, 'img_authorization', ['class' => 'control-label'])
                </div>
                <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
                    <span class="grey-bg form-control input-sm">
                        @if(!empty($user->img_authorization) && $user->img_authorization !== 'NULL'){
                            <a href="@($user->img_authorization())" download><i class="fa fa-download"></i> Download</a>
                        }
                        <div class="" style="float: right">
                            @if(!empty($user->img_authorization) && $user->img_authorization !== 'NULL'){
                                <a href="javascript:void(0);" id="delete-auth" data-id="@($user->user_id)"><i class="fa fa-trash"></i> delete</a>
                            }
                        </div>
                    </span>             
                </div>             
            </div>

            <div class="row mb10">
                <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                    @Html::activeLabel($user, 'img_log_card', ['class' => 'control-label'])
                </div>
                <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
                    <span class="grey-bg form-control input-sm">
                        @if(!empty($user->img_log_card) && $user->img_log_card !== 'NULL'){
                            <a href="@($user->img_log_card())" download><i class="fa fa-download"></i> Download</a>
                        }
                        <div class="" style="float: right">
                            @if(!empty($user->img_log_card) && $user->img_log_card !== 'NULL'){
                                <a href="javascript:void(0);" id="delete-log" data-id="@($user->user_id)"><i class="fa fa-trash"></i> delete</a>
                            }
                        </div>
                    </span>             
                </div>             
            </div>   
            @if($user->account_id == 1 || $user->account_id == 8){
                @foreach($user->getLogs()->all() as $key => $log ){
                    @if($key == 1){
                        <h2><i class="fa fa-archive mt"></i> Archive</h2>
                    }
                    @if($key > 0){
                        <div class="row mb10">
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                <label class="control-label" for="user-img_log_card">Log Card Changed: (@(Common::systemDateFormat($log->created_at)))</label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
                                <span class="grey-bg form-control input-sm">
                                    
                                    <a href="@($log->log_card())" download><i class="fa fa-download"></i> Download</a>
                                    
                                </span>             
                            </div>             
                        </div>
                    }
                }
            }

            <!-- @if($user->archive()){
                <h2><i class="fa fa-archive mt"></i> Archive</h2>         
                <div class="row mb10">
                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                        <label class="control-label" for="user-img_log_card">Registration Log Card (@(Common::systemDateFormat($user->created_at)))</label>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
                        <span class="grey-bg form-control input-sm">
                            @if($user->img_log_card){
                                <a href="@($user->old_img_log_card())" download><i class="fa fa-download"></i> Download</a>
                            }
                        </span>             
                    </div>             
                </div>
            }
            @foreach($user->getRenewals()->andWhere(['status' => 2])->all() as $key => $renewal){
                @if($key != 0 && !is_null($renewal->log_card)){
                    <div class="row mb10">
                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                            <label class="control-label" for="user-img_log_card">Renewal Log Card (@(Common::systemDateFormat($renewal->created_at)))</label>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
                            <span class="grey-bg form-control input-sm">
                                
                                <a href="@($renewal->log_card())" download><i class="fa fa-download"></i> Download</a>
                                 
                            </span>             
                        </div>             
                    </div>
                }
            } -->
             
        </div>
    </div>

    <div class="text-right">
        @if($user->isPending() AND $logged_user->isAdminOrMemDirectory()){
            <button class="btn btn-sm btn-primary" id="reject">Reject</button>
            @if(!$user->approved_by) {
                <button class="btn btn-sm btn-success" id="approve">Approve</button>
            } else {
                <button class="btn btn-sm btn-success" id="approve">Confirm</button>
            }
        }
    </div>

@{ ActiveForm::end(); }


    <input type="hidden" value="@($user->member_expire? 1 : 0)" id="hidMemberExpiry" />


    <div id="msgBoxMemberExpiry" class="modal fade" role="dialog" style="display: none; z-index: 99999">
        <div class="modal-dialog">
            <div class="modal-content" style="width: 40%;">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Member Expiry:</h4>
                </div>
                <div class="modal-body">
                    <div id="member_expiry_dt"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="mem_expiry_set" class="btn btn-success yes btn-sm">Set</button>
                    <button type="button" class="btn btn-primary no btn-sm" data-dismiss="modal">Cancel</button>
                </div>
            </div>

        </div>
    </div>
<script type="text/javascript">
(function($) {
    var Profile = function() {
        var bindEvents = function() {

            $('#delete-nric').bind('click', function(e) {
                e.preventDefault();

                var id = $(this).attr('data-id');
                var dis = $(this);

                serverProcess({
                    action:'member/delete-nric',
                    data: 'id=' + id,
                    show_process:true,
                    callback:function(json){                        
                        if(json.success){
                            dis.parent().parent().remove();
                        }else{
                            modAlert(json.error);
                        }
                    }
                });
            });

            $('#delete-ins').bind('click', function(e) {
                e.preventDefault();

                var id = $(this).attr('data-id');
                var dis = $(this);

                serverProcess({
                    action:'member/delete-ins-img',
                    data: 'id=' + id,
                    show_process:true,
                    callback:function(json){                        
                        if(json.success){
                            dis.parent().parent().remove();
                        }else{
                            modAlert(json.error);
                        }
                    }
                });
            });

            $('#delete-auth').bind('click', function(e) {
                e.preventDefault();

                var id = $(this).attr('data-id');
                var dis = $(this);

                serverProcess({
                    action:'member/delete-auth-img',
                    data: 'id=' + id,
                    show_process:true,
                    callback:function(json){                        
                        if(json.success){
                            dis.parent().parent().remove();
                        }else{
                            modAlert(json.error);
                        }
                    }
                });
            });


            $('#delete-log').bind('click', function(e) {
                e.preventDefault();

                var id = $(this).attr('data-id');
                var dis = $(this);

                serverProcess({
                    action:'member/delete-log-card',
                    data: 'id=' + id,
                    show_process:true,
                    callback:function(json){                        
                        if(json.success){
                            dis.parent().parent().remove();
                        }else{
                            modAlert(json.error);
                        }
                    }
                });
            });
            
            $('#member_expiry_dt').datepicker({
                format : 'yyyy-mm-dd',
                todayHighlight: true
            });

            $('#approve').bind('click', function(e) {
                e.preventDefault();

                var message = 'Do you want to approve this membership request?';
                if (parseInt($("#hidMemberExpiry").val()) == 1){
                    modConfirm(message, function() {
                        serverProcess({
                            action:'member/approve',
                            data: {
                                user_id : @($user->user_id)
                            },
                            show_process:true,
                            callback:function(json){
                                if(json.success){
                                    window.location.reload();
                                }else{
                                    modAlert(json.error);
                                }
                            }
                        });
                    });
                }else{  
                    $('#msgBoxMemberExpiry').modal('show');
                }
                
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

            $('#mem_expiry_set').bind('click', function(e) {   
                e.preventDefault();
                
                var dateTime = new Date($("#member_expiry_dt").datepicker("getDate"));
                var strDateTime = dateTime.getFullYear() + "/" + (dateTime.getMonth()+1) + "/" + dateTime.getDate();
                $('#msgBoxMemberExpiry').modal('hide');
                serverProcess({
                    action:'member/set-expiry',
                    data: { user_id: @($user->user_id), member_expiry: strDateTime },
                    show_process:true,
                    callback:function(json){
                        if(json.success){
                            $("#hidMemberExpiry").val(1);
                            $('#approve').trigger('click');
                        } else if(typeof(json.errorFields) == 'object'){
                            window.highlightErrors(json.errorFields);
                        }else{
                            modAlert(json.error);
                        }
                    }
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
