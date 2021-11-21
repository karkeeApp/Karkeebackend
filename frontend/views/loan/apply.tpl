@{
	use yii\helpers\Url;
	use yii\helpers\Html;
    use yii\widgets\ActiveForm;
}

@($menu)

<h4>Apply for funding</h4>

<div class="row">
	<div class="col-sm-6">
		@{
		    $form = ActiveForm::begin([
		        'id' => 'loan-form', 
		        'enableClientScript' => true,
		        'fieldConfig' => [
		            'labelOptions' => ['class' => 'col-sm-4 control-label'],
		            'template' => "{label}\n<div class=\"col-sm-8\">{input}{error}</div>",
		        ],
		    ]);
		}

		<input type="hidden" name="action" value="add">

		@$form->field($loanForm, 'amount')->textInput(['class' => 'form-control input-sm'])
		@$form->field($loanForm, 'reason')->textArea(['class' => 'form-control input-sm'])

		<div class="form-group">
			<div class="col-sm-12 text-right">
				<button class="btn btn-primary btn-sm" id="apply">Apply</button>
			</div>
		</div>
		@{ ActiveForm::end(); }
	</div>
</div>

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

		$('#declineApplication')
		.unbind('click')
		.bind('click', function(e) {
			e.preventDefault();

			serverProcess({
		        action:'loan/decline',
		        data: $('#loan-form').serialize(),
		        show_process:true,
		        callback:function(json){
		            if(json.success){
		                $('#loan-form').trigger('reset');
		           	} else if(typeof(json.errorFields) == 'object'){
	           			window.highlightErrors(json.errorFields);
		            }else{
		                modAlert(json.error);
		            }
		        }
		    });
		});
	};

	$('#apply')
	.bind('click', function(e) {
		e.preventDefault();

		modConfirm('Do you want to proceed?', function() {
			serverProcess({
		        action:'loan/apply',
		        data: $('#loan-form').serialize(),
		        show_process:true,
		        callback:function(json){
		            if(json.success){
		            	$('#loanApplyConfirm div.modal-body').html(json.content);	
		            	$('#loanApplyConfirm').modal();

		            	bindConfirmation();
		           	} else if(typeof(json.errorFields) == 'object'){
	           			window.highlightErrors(json.errorFields);
		            }else{
		                modAlert(json.error);
		            }
		        }
		    });
		});
	});
	
})(jQuery);

</script>