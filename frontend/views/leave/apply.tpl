@{
	use yii\helpers\Url;
    use yii\widgets\ActiveForm;	
    use common\forms\LeaveForm;
    use common\models\LeaveApplication;
    use common\helpers\Common;
}

@($menu)

<h4><i class="fa fa-file-o"></i> Leave Application</h4>

@{
    $form = ActiveForm::begin([
        'id' => 'leave-form', 
        'enableClientScript' => true,
        'fieldConfig' => [
            'labelOptions' => ['class' => 'control-label'],
            'template' => "<div class='col-sm-3 col-md-3'>{label}</div>\n<div class=\"col-sm-9 col-md-9\">{input}{error}</div>",
            'options' => [
            	'class' => 'row',
            ]
        ],
    ]);
}

	@$form->field($leaveForm, 'type')->dropDownList(['' => 'Select leave type'] + LeaveApplication::types(), ['class' => 'form-control input-sm'])
	@$form->field($leaveForm, 'definition')->dropDownList(['' => 'Select definition type'] + LeaveApplication::definitions(), ['class' => 'form-control input-sm'])
	@$form->field($leaveForm, 'day_time_type')->dropDownList(LeaveApplication::dayTimeTypes(), ['class' => 'form-control input-sm'])
	@$form->field($leaveForm, 'date_from')->textInput(['class' => 'form-control input-sm', 'placeholder' => 'yyyy-mm-dd'])
	@$form->field($leaveForm, 'date_to')->textInput(['class' => 'form-control input-sm', 'placeholder' => 'yyyy-mm-dd'])
	@$form->field($leaveForm, 'staff_remarks')->textArea(['class' => 'form-control input-sm'])

	<div class="text-right">
		<a href="javascript:void(0)" id="apply" class="btn btn-sm btn-primary"><i class="fa fa-save"></i> Submit</a>
	</div>
@{ ActiveForm::end(); }


<script type="text/javascript">
(function($) {
	var bindConfirmation = function() {
		$('#confirmApplication')
		.unbind('click')
		.bind('click', function(e) {
			e.preventDefault();

			serverProcess({
		        action:'loan/confirm',
		        data: $('#loan-form').serialize(),
		        show_process:true,
		        callback:function(json){
		            if(json.success){
		                //modAlert(json.message);
		                $('#loan-form').trigger('reset');
		                window.location.reload();
		           	} else if(typeof(json.errorFields) == 'object'){
	           			window.highlightErrors(json.errorFields);
		            }else{
		                modAlert(json.error);
		            }
		        }
		    });
		});
	};

	var bindEvents = function() {
		$('#leaveform-date_from, #leaveform-date_to').datepicker({
			format : 'yyyy-mm-dd'
		});

		$('#apply')
		.bind('click', function(e) {
			e.preventDefault();

			$('.help-block').empty();

			modConfirm('Do you want to proceed?', function() {
				serverProcess({
			        action:'leave/apply',
			        data: $('#leave-form').serialize() + '&action=add',
			        show_process:true,
			        callback:function(json){
			            if(json.success){
			            	@if(Common::isStaff()) {
			            		modAlert(json.message);
			            		window.location.href = "@(Url::home())leave";
			            	} else {
				            	$('#leaveApplyConfirm div.modal-body').html(json.content);	
				            	$('#leaveApplyConfirm').modal();

				            	bindConfirmation();			            		
			            	}
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

	var Leave = function() {
		return {
			_construct : function() {
				bindEvents();
			}
		}
	}();

	$(document).ready(function() {
		Leave._construct();
	});
})(jQuery);

</script>