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
            'labelOptions' => ['class' => 'col-sm-4 control-label'],
            'template' => "{label}\n<div class=\"col-sm-8\">{input}{error}</div>",
        ],
    ]);
}
    <h4><i class="fa fa-info"></i> Company Details</h4>

    <div class="row mb10">
        @Html::activeLabel($user, 'company', ['class' => 'col-xm-12 col-sm-12 col-md-3 col-lg-3 control-label'])
        @Html::activePrint($user, 'company', ['class' => 'col-xm-12 col-sm-12 col-md-9 col-lg-9'])
    </div>

    <div class="row mb10">
        @Html::activeLabel($user, 'status', ['class' => 'col-xm-12 col-sm-12 col-md-3 col-lg-3 control-label'])
        @Html::activePrint($user, 'status', ['class' => 'col-xm-12 col-sm-12 col-md-9 col-lg-9'])
    </div>

    <div class="row mb10">
        @Html::activeLabel($user, 'eun', ['class' => 'col-xm-12 col-sm-12 col-md-3 col-lg-3 control-label'])
        @Html::activePrint($user, 'eun', ['class' => 'col-xm-12 col-sm-12 col-md-9 col-lg-9'])
    </div>

    <div class="row mb10">
        @Html::activeLabel($user, 'about', ['class' => 'col-xm-12 col-sm-12 col-md-3 col-lg-3 control-label'])
        @Html::activePrint($user, 'about', ['class' => 'col-xm-12 col-sm-12 col-md-9 col-lg-9'])
    </div>

    <div class="row mb10">
        @Html::activeLabel($user, 'number_of_employees', ['class' => 'col-xm-12 col-sm-12 col-md-3 col-lg-3 control-label'])
        @Html::activePrint($user, 'number_of_employees', ['class' => 'col-xm-12 col-sm-12 col-md-9 col-lg-9'])
    </div>
    <br />

    <h4><i class="fa fa-user"></i> Contact Details</h4>

    <div class="row mb10">
        @Html::activeLabel($user, 'email', ['class' => 'col-xm-12 col-sm-12 col-md-3 col-lg-3 control-label'])
        @Html::activePrint($user, 'email', ['class' => 'col-xm-12 col-sm-12 col-md-9 col-lg-9'])
    </div>

    <div class="row mb10">
        @Html::activeLabel($user, 'fullname', ['class' => 'col-xm-12 col-sm-12 col-md-3 col-lg-3 control-label'])
        @Html::activePrint($user, 'fullname', ['class' => 'col-xm-12 col-sm-12 col-md-9 col-lg-9'])
    </div>

    <div class="row mb10">
        @Html::activeLabel($user, 'gender', ['class' => 'col-xm-12 col-sm-12 col-md-3 col-lg-3 control-label'])
        @Html::activePrint($user, 'gender', ['class' => 'col-xm-12 col-sm-12 col-md-9 col-lg-9'])
    </div>

    <div class="row mb10">
        @Html::activeLabel($user, 'birthday', ['class' => 'col-xm-12 col-sm-12 col-md-3 col-lg-3 control-label'])
        @Html::activePrint($user, 'birthday', ['class' => 'col-xm-12 col-sm-12 col-md-9 col-lg-9'])
    </div>

    <div class="row mb10">
        @Html::activeLabel($user, 'nric', ['class' => 'col-xm-12 col-sm-12 col-md-3 col-lg-3 control-label'])
        @Html::activePrint($user, 'nric', ['class' => 'col-xm-12 col-sm-12 col-md-9 col-lg-9'])
    </div>

    <br />

    <h4><i class="fa fa-map-marker"></i> Address</h4>

    <div class="row mb10">
        @Html::activeLabel($user, 'add_1', ['class' => 'col-xm-12 col-sm-12 col-md-3 col-lg-3 control-label'])
        @Html::activePrint($user, 'add_1', ['class' => 'col-xm-12 col-sm-12 col-md-9 col-lg-9'])
    </div>

    <div class="row mb10">
        @Html::activeLabel($user, 'add_2', ['class' => 'col-xm-12 col-sm-12 col-md-3 col-lg-3 control-label'])
        @Html::activePrint($user, 'add_2', ['class' => 'col-xm-12 col-sm-12 col-md-9 col-lg-9'])
    </div>

    <div class="row mb10">
        @Html::activeLabel($user, 'unit_no', ['class' => 'col-xm-12 col-sm-12 col-md-3 col-lg-3 control-label'])
        @Html::activePrint($user, 'unit_no', ['class' => 'col-xm-12 col-sm-12 col-md-9 col-lg-9'])
    </div>

    <div class="row mb10">
        @Html::activeLabel($user, 'postal_code', ['class' => 'col-xm-12 col-sm-12 col-md-3 col-lg-3 control-label'])
        @Html::activePrint($user, 'postal_code', ['class' => 'col-xm-12 col-sm-12 col-md-9 col-lg-9'])
    </div>

    <div class="row mb10">
        @Html::activeLabel($user, 'country', ['class' => 'col-xm-12 col-sm-12 col-md-3 col-lg-3 control-label'])
        @Html::activePrint($user, 'country', ['class' => 'col-xm-12 col-sm-12 col-md-9 col-lg-9'])
    </div>

    <br />

    <h4><i class="fa fa-paperclip"></i> Documents</h4>

    <div class="row mb10">
        @Html::activeLabel($user, 'img_acra', ['class' => 'col-xm-12 col-sm-12 col-md-3 col-lg-3 control-label'])
        <div class="col-xm-12 col-sm-12 col-md-9 col-lg-9">
            <div class="grey-bg form-control input-sm">
                @if($user->img_acra){
                    <a href="@($user->img_acra())" target="_blank"><i class="fa fa-download"></i> Download</a>
                }
            </div>
        </div>
    </div>

    <br />

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

