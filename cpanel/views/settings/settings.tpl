@{
	use yii\widgets\ActiveForm;
	use common\helpers\Common;
}

@($menu)

<ul class="nav nav-tabs">
	<li class="active"><a data-toggle="tab" href="#general"><i class="fa fa-info"></i> General Settings</a></li>
	<li><a data-toggle="tab" href="#password"><i class="fa fa-lock"></i> Password</a></li>
</ul>


<div class="tab-content">
	<div id="general" class="tab-pane fade in active">
		<!-- h3><i class="fa fa-info"></i> General Settings</h3 -->

		<div class="row">
			<div class="col-sm-6">
				TODO
			</div>
		</div>

	</div>

	<div id="password" class="tab-pane fade">
		<!-- h3><i class="fa fa-lock"></i> Password</h3 -->

		<div class="row">
			<div class="col-sm-6">
				@{
				    $form = ActiveForm::begin([
				        'id' => 'password-form', 
				        'enableClientScript' => true,
				        'fieldConfig' => [
				            'labelOptions' => ['class' => 'col-sm-2 control-label'],
				            'template' => "{label}\n<div class=\"col-sm-10\">{input}{error}</div>",
				        ],
				    ]);
				}

				<div class="hide">
					<input type="hidden" name="action" value="add">
				</div>

				@$form->field($mfiPasswordForm, 'old')->passwordInput(['class' => 'form-control input-sm', 'autocomplete' => 'off'])
				@$form->field($mfiPasswordForm, 'new')->passwordInput(['class' => 'form-control input-sm', 'autocomplete' => 'off'])
				@$form->field($mfiPasswordForm, 'confirm')->passwordInput(['class' => 'form-control input-sm', 'autocomplete' => 'off'])

				<div class="form-group">
					<div class="col-sm-12 text-right">
						<button class="btn btn-primary btn-sm" id="updatePassword"><i class="fa fa-save"></i> Submit</button>
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
			$('#updateSettings').bind('click', function(e) {
				e.preventDefault();

				modConfirm('Do you want to proceed?', function() {
					serverProcess({
				        action:'settings/updatesettings',
				        data: $('#settings-form').serialize(),
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