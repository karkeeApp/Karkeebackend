@{
	use yii\helpers\Url;
	use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use common\forms\UserForm;
}

@($menu)

<ul class="nav nav-tabs">
	<li class="active"><a data-toggle="tab" href="#password"><i class="fa fa-lock"></i> Password</a></li>
	<li><a data-toggle="tab" href="#email"><i class="fa fa-envelope"></i> Email Address</a></li>
	<li><a data-toggle="tab" href="#mobile"><i class="fa fa-mobile"></i> Mobile</a></li>
</ul>

<div class="tab-content">
	<div id="password" class="tab-pane fade in active">
		<h3><i class="fa fa-lock"></i> Password</h3>

		<div class="row">
			<div class="col-sm-6">
				@{
				    $form = ActiveForm::begin([
				        'id' => 'password-form', 
				        'enableClientScript' => true,
				        'fieldConfig' => [
				            'labelOptions' => ['class' => 'col-sm-4 control-label'],
				            'template' => "{label}\n<div class=\"col-sm-8\">{input}{error}</div>",
				        ],
				    ]);
				}

				<div class="hide">
					<input type="hidden" name="action" value="add">
				</div>

				@$form->field($passwordForm, 'old')->passwordInput(['class' => 'form-control input-sm', 'autocomplete' => 'off'])
				@$form->field($passwordForm, 'new')->passwordInput(['class' => 'form-control input-sm', 'autocomplete' => 'off'])
				@$form->field($passwordForm, 'confirm')->passwordInput(['class' => 'form-control input-sm', 'autocomplete' => 'off'])

				<div class="form-group">
					<div class="col-sm-12 text-right">
						<button class="btn btn-primary btn-sm" id="updatePassword"><i class="fa fa-save"></i> Submit</button>
					</div>
				</div>

				@{ ActiveForm::end(); }
			</div>
		</div>

	</div>

	<div id="email" class="tab-pane fade">
		<h3><i class="fa fa-envelope"></i> Email Address</h3>

		<div class="row">
			<div class="col-sm-6">
				@{
				    $form = ActiveForm::begin([
				        'id' => 'email-form', 
				        'enableClientScript' => true,
				        'fieldConfig' => [
				            'labelOptions' => ['class' => 'col-sm-4 control-label'],
				            'template' => "{label}\n<div class=\"col-sm-8\">{input}{error}</div>",
				        ],
				    ]);
				}

				<div class="hide">
					<input type="hidden" name="action" value="add">
				</div>

				@$form->field($emailForm, 'email')->textInput(['class' => 'form-control input-sm'])

				<div class="form-group">
					<div class="col-sm-12 text-right">
						<button class="btn btn-primary btn-sm" id="updateEmail"><i class="fa fa-save"></i> Submit</button>
					</div>
				</div>

				@{ ActiveForm::end(); }
			</div>
		</div>
	</div>

	<div id="mobile" class="tab-pane fade">
		<h3><i class="fa fa-mobile"></i> Mobile Number</h3>

		<div class="row">
			<div class="col-sm-6">
				@{
				    $form = ActiveForm::begin([
				        'id' => 'mobile-form', 
				        'enableClientScript' => true,
				        'fieldConfig' => [
				            'labelOptions' => ['class' => 'col-sm-4 control-label'],
				            'template' => "{label}\n<div class=\"col-sm-8\">{input}{error}</div>",
				        ],
				    ]);
				}

				<div class="hide">
					<input type="hidden" name="action" value="add">
				</div>

				@$form->field($mobileForm, 'remittance_type')->dropDownList(['' => 'Select type'] + UserForm::remittances(), ['class' => 'form-control input-sm'])
				@$form->field($mobileForm, 'mobile')->textInput(['class' => 'form-control input-sm'])

				<div class="form-group">
					<div class="col-sm-12 text-right">
						<button class="btn btn-primary btn-sm" id="updateMobile"><i class="fa fa-save"></i> Submit</button>
					</div>
				</div>

				@{ ActiveForm::end(); }
			</div>
		</div>
	</div>
</div>


<script type="text/javascript">
(function($) {
	var Settings = function() {
		var bindEvents = function() {
			$('#updatePassword').bind('click', function(e) {
				e.preventDefault();

				modConfirm('Do you want to proceed?', function() {
					serverProcess({
				        action:'settings/updatepassword',
				        data: $('#password-form').serialize(),
				        show_process:true,
				        callback:function(json){
				            if(json.success){
				                modAlert(json.message);
				                $('#password-form').trigger('reset');
				           	} else if(typeof(json.errorFields) == 'object'){
			           			window.highlightErrors(json.errorFields);
				            }else{
				                modAlert(json.error);
				            }
				        }
				    });
				});
			});

			$('#updateEmail').bind('click', function(e) {
				e.preventDefault();

				modConfirm('Do you want to proceed?', function() {
					serverProcess({
				        action:'settings/updateemail',
				        data: $('#email-form').serialize(),
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

			$('#updateMobile').bind('click', function(e) {
				e.preventDefault();

				modConfirm('Do you want to proceed?', function() {
					serverProcess({
				        action:'settings/updatemobile',
				        data: $('#mobile-form').serialize(),
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

