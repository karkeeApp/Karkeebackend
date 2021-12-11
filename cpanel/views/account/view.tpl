@{
	use yii\helpers\Url;
	use common\helpers\Html;
    use yii\widgets\ActiveForm;
    use common\forms\user;
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
<div id="company-details" class="container">

    <h4><i class="fa fa-info"></i> Company Details</h4>
    
    @$form->field($user, 'company')->textInput(['class' => 'form-control input-sm', 'disabled' => true])
    @$form->field($user, 'status')->textInput(['class' => 'form-control input-sm', 'value' => $user->status(), 'disabled' => true])
    @$form->field($user, 'eun')->textInput(['class' => 'form-control input-sm', 'disabled' => true])
    @$form->field($user, 'about')->textInput(['class' => 'form-control input-sm', 'disabled' => true])
    @$form->field($user, 'number_of_employees')->textInput(['class' => 'form-control input-sm', 'disabled' => true])
 
</div>
<div id="contact-details" class="container">
    <h4><i class="fa fa-user"></i> Contact Details</h4>

    @$form->field($user, 'email')->textInput(['class' => 'form-control input-sm', 'disabled' => true])
    @$form->field($user, 'fullname')->textInput(['class' => 'form-control input-sm', 'disabled' => true])
    @$form->field($user, 'gender')->textInput(['class' => 'form-control input-sm', 'value' => $user->gender(), 'disabled' => true])
    @$form->field($user, 'birthday')->textInput(['class' => 'form-control input-sm', 'disabled' => true])
    @$form->field($user, 'nric')->textInput(['class' => 'form-control input-sm', 'disabled' => true])
 
</div>
<div id="address" class="container">
    <h4><i class="fa fa-map-marker"></i> Address</h4>

    @$form->field($user, 'add_1')->textInput(['class' => 'form-control input-sm', 'disabled' => true])
    @$form->field($user, 'add_2')->textInput(['class' => 'form-control input-sm', 'disabled' => true])
    @$form->field($user, 'unit_no')->textInput(['class' => 'form-control input-sm', 'disabled' => true])
    @$form->field($user, 'postal_code')->textInput(['class' => 'form-control input-sm', 'disabled' => true])
    @$form->field($user, 'country')->textInput(['class' => 'form-control input-sm', 'disabled' => true])

</div>
<div id="documents" class="container">
    <h4><i class="fa fa-paperclip"></i> Documents</h4>

    <div class="row mb10">
        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
            @Html::activeLabel($user, 'img_acra', ['class' => 'control-label'])
        </div>
        <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
            <span class="grey-bg form-control input-sm">
                @if($user->img_acra){
                    <a href="@($user->img_acra())" target="_blank"><i class="fa fa-download"></i> Download</a>
                }
            </span>             
        </div>             
    </div>

</div>
<div id="directors-shareholders" class="container">

    <h4><i class="fa fa-users"></i> Directors / Shareholders</h4>
    <div class="table-responsive">
        <table class="table">
            <tr>
                <th>Fullname</th>
                <th>Email</th>
                <th>Mobile</th>
                <th>Is Director</th>
                <th>Is Shareholder</th>
            </tr>
            @if($user->directors) {
                @foreach($user->directors as $director) {
                    <tr>
                        <td><i class="fa fa-dot-circle-o"></i> @($director->fullname)</td>
                        <td>@($director->email)</td>
                        <td>@($director->mobile_no())</td>
                        <td>@($director->is_director())</td>
                        <td>@($director->is_shareholder())</td>
                    </tr>
                }
            } else {
                <tr>
                    <td colspan="99">No director/shareholder found</td>
                </tr>
            }
        </table>
    </div>

    <div class="text-right">
        @if($user->isPending()){
            <button class="btn btn-sm btn-primary" id="reject">Reject</button>
            <button class="btn btn-sm btn-success" id="approve">Approve</button>
        }
    </div>
</div>
@{ ActiveForm::end(); }

<script type="text/javascript">
(function($) {
    var Account = function() {
        var bindEvents = function() {
            $('#approve').bind('click', function(e) {
                e.preventDefault();

                modConfirm('Are you sure?', function() {
                    serverProcess({
                        action:'account/approve',
                        data: {
                            account_id : @($account->account_id)
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
                        action:'account/reject',
                        data: {
                            account_id : @($account->account_id)
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
        Account._construct();
    });

})(jQuery);

</script>

