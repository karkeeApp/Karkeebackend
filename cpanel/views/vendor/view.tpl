@{
    use yii\helpers\Url;
    use common\helpers\Html;
    use yii\widgets\ActiveForm;
    use common\helpers\Common;
    use common\models\User;
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
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#vendor-information"><i class="fa fa-info"></i> Vendor Information</a></li>
    </ul>

    <div class="tab-content">
        <div id="vendor-information" class="tab-pane active">
            <h2><i class="fa fa-info"></i> Vendor Information</h2>

            <div class="row mb10">
                @Html::activeLabel($user, 'email', ['class' => 'col-sm-4 col-md-3 control-label'])
                @Html::activePrint($user, 'email', ['class' => 'col-sm-8 col-md-9'])
            </div>

            <div class="row mb10">
                @Html::activeLabel($user, 'vendor_name', ['class' => 'col-sm-4 col-md-3 control-label'])
                @Html::activePrint($user, 'vendor_name', ['class' => 'col-sm-8 col-md-9'])
            </div>

            <div class="row mb10">
                @Html::activeLabel($user, 'founded_date', ['class' => 'col-sm-4 col-md-3 control-label'])
                @Html::activePrint($user, 'founded_date', ['class' => 'col-sm-8 col-md-9'])
            </div>

            <div class="row mb10">
                @Html::activeLabel($user, 'about', ['class' => 'col-sm-4 col-md-3 control-label'])
                @Html::activePrint($user, 'about', ['class' => 'col-sm-8 col-md-9'])
            </div>

            <div class="row mb10">
                @Html::activeLabel($user, 'mobile', ['class' => 'col-sm-4 col-md-3 control-label'])
                @Html::activePrint($user, 'mobile', ['class' => 'col-sm-8 col-md-9'])
            </div>

            <div class="row mb10">
                @Html::activeLabel($user, 'telephone', ['class' => 'col-sm-4 col-md-3 control-label'])
                @Html::activePrint($user, 'telephone', ['class' => 'col-sm-8 col-md-9'])
            </div>

            <div class="row mb10">
                @Html::activeLabel($user, 'country', ['class' => 'col-sm-4 col-md-3 control-label'])
                @Html::activePrint($user, 'country', ['class' => 'col-sm-8 col-md-9'])
            </div>

            <div class="row mb10">
                @Html::activeLabel($user, 'postal_code', ['class' => 'col-sm-4 col-md-3 control-label'])
                @Html::activePrint($user, 'postal_code', ['class' => 'col-sm-8 col-md-9'])
            </div>

            <div class="row mb10">
                @Html::activeLabel($user, 'unit_no', ['class' => 'col-sm-4 col-md-3 control-label'])
                @Html::activePrint($user, 'unit_no', ['class' => 'col-sm-8 col-md-9'])
            </div>

            <div class="row mb10">
                @Html::activeLabel($user, 'add_1', ['class' => 'col-sm-4 col-md-3 control-label'])
                @Html::activePrint($user, 'add_1', ['class' => 'col-sm-8 col-md-9'])
            </div>

            <div class="row mb10">
                @Html::activeLabel($user, 'status', ['class' => 'col-sm-4 col-md-3 control-label'])
                @Html::activePrint($user, 'status', ['class' => 'col-sm-8 col-md-9'])
            </div>
        </div>
    </div>

    <div class="text-right">
        @if($user->isPending() AND $user->account_id == $account->account_id){
            <button class="btn btn-sm btn-primary" id="reject">Reject</button>
            <button class="btn btn-sm btn-success" id="approve">Approve</button>
        }
    </div>

@{ ActiveForm::end(); }

<script type="text/javascript">
(function($) {
    var Profile = function() {
        var bindEvents = function() {
            $('#approve').bind('click', function(e) {
                e.preventDefault();

                modConfirm('Are you sure?', function() {
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
