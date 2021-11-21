@{
use yii\helpers\Url;
use common\helpers\Html;
use yii\widgets\ActiveForm;
use common\helpers\Common;
}

@($menu)

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
        <h4><i class="fa fa-info"></i> @($userpayment->name)</h4>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-right">
        <a href="@(Url::home())userpayment/edit/@($userpayment->id)" class="btn btn-sm btn-default"><i class="fa fa-edit"></i> Edit</a>
    </div>
</div>

<br />

<ul class="nav nav-tabs mb10">
    <li class="active"><a data-toggle="tab" href="#details"><i class="fa fa-info"></i> Details</a></li>
</ul>

<div class="tab-content">
    <div id="details" class="tab-pane active">
        <div class="row mb10">
            <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 col-md-offset-3 col-lg-offset-3">
                <div class="text-left">
                    <img class="img-responsive" src="@($userpayment->filelink())" width="200"/>
                </div>
            </div>
        </div>

        <div class="row mb10">
            @Html::activeLabel($userpayment, 'name', ['class' => 'col-xs-12 col-sm-12 col-md-3 col-lg-3 control-label'])
            @Html::activePrint($userpayment, 'name', ['class' => 'col-xs-12 col-sm-12 col-md-9 col-lg-9'])
        </div>

        <div class="row mb10">
            @Html::activeLabel($userpayment, 'description', ['class' => 'col-xs-12 col-sm-12 col-md-3 col-lg-3 control-label'])
            @Html::activePrint($userpayment, 'description', ['class' => 'col-xs-12 col-sm-12 col-md-9 col-lg-9'])
        </div>



        <div class="row mb10">
            @Html::activeLabel($userpayment, 'amount', ['class' => 'col-xs-12 col-sm-12 col-md-3 col-lg-3 control-label'])
            @Html::activePrint($userpayment, 'amount', ['class' => 'col-xs-12 col-sm-12 col-md-9 col-lg-9'])
        </div>


        <div class="row mb10">
            @Html::activeLabel($userpayment, 'status', ['class' => 'col-xs-12 col-sm-12 col-md-3 col-lg-3 control-label'])
            @Html::activePrint($userpayment, 'status', ['class' => 'col-xs-12 col-sm-12 col-md-9 col-lg-9'])
        </div>
    </div>

    <div class="text-right">
        @if($userpayment AND $userpayment->isPending()){
        <button class="btn btn-sm btn-primary" data-id="@($userpayment->id)" id="reject">Reject</button>
        <button class="btn btn-sm btn-success" data-id="@($userpayment->id)" id="approve">Approve</button>
        }
    </div>

</div>



<script type="text/javascript">
    (function($) {
        var Profile = function() {
            var bindEvents = function() {
                $('#approve').bind('click', function(e) {
                    e.preventDefault();
                    var message = 'Do you want to approve this payment?';

                    var id = $(this).attr('data-id');
                    modConfirm(message, function() {
                        serverProcess({
                            action : 'userpayment/approve',
                            data: {
                                id : id
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

                    var id = $(this).attr('data-id');
                    modConfirm('Are you sure?', function() {
                        serverProcess({
                            action : 'userpayment/reject',
                            data: {
                                id : id
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


