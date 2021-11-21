@{
	use yii\widgets\ActiveForm;
	use common\helpers\Common;
	use common\forms\HRSettingsForm;
}

@($menu)

<ul class="nav nav-tabs">
	<li class="active"><a data-toggle="tab" href="#general"><i class="fa fa-info"></i> General Settings</a></li>
	<li><a data-toggle="tab" href="#password"><i class="fa fa-lock"></i> Password</a></li>
</ul>


<div class="tab-content">
	<div id="general" class="tab-pane fade in active">
		<!-- h3><i class="fa fa-info"></i> General Settings</h3 -->
		@{
		    $form = ActiveForm::begin([
		        'id' => 'hrsettings-form', 
		        'enableClientScript' => true,
		        'fieldConfig' => [
		            'labelOptions' => ['class' => 'col-sm-3 control-label'],
		            'template' => "{label}\n<div class=\"col-sm-9\">{input}{error}</div>",
		        ],
		    ]);
		}

		<div class="hide">
			<input type="hidden" name="action" value="add">
			@$form->field($userSettingsForm, 'account_id')->textInput()
		</div>

		@$form->field($userSettingsForm, 'cut_off')->dropDownList(['' => 'Select day'] + Common::days(), ['class' => 'form-control input-sm'])
		@$form->field($userSettingsForm, 'salary_date')->dropDownList(['' => 'Select day'] + Common::days(), ['class' => 'form-control input-sm'])
		@$form->field($userSettingsForm, 'salary_tax')->dropDownList(HRSettingsForm::tax_payable(), ['class' => 'form-control input-sm'])
		@$form->field($userSettingsForm, 'loan_cut_off')->dropDownList(['' => 'Select day'] + Common::days(), ['class' => 'form-control input-sm'])

		<div class="form-group">
			<label class="col-sm-3 control-label">Working Days</label>
			<div class="col-sm-9">
				@foreach(Common::workingDays() as $day => $label) {
					<div class="form-group">
						<label class="col-sm-3 control-label">@($label)</label>
						<div class="col-sm-9">
							<input name="HRSettingsForm[working_@($day)][]" value="full" type="checkbox" class="working_day working_@($day)" data-day="@($day)" @(($userSettingsForm->{"working_$day"} == 'full')? 'checked=checked' : '')>Full
							<input name="HRSettingsForm[working_@($day)][]" value="half" type="checkbox" class="working_day working_@($day)" data-day="@($day)" @(($userSettingsForm->{"working_$day"} == 'half')? 'checked=checked' : '')>Half
						</div>
					</div>
					<div class="clearfix mb4"></div>
				}
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label">Leave</label>
			<div class="col-sm-9" style="padding: 0px">
				<div class="form-group" style="padding-left: 20px;">
					@foreach(Common::leaveType() as $leave => $label) {
						<div class="col-sm-2" style="padding: 0px">
							<input name="HRSettingsForm[leave_@($leave)][]" value="@($leave)" type="checkbox" class="leave_type leave_@($leave)" data-day="@($leave)" @(($userSettingsForm->{"leave_$leave"} == '1')? 'checked=checked' : '')> @($label)
						</div>
					}
				</div>
				<div class="clearfix mb4"></div>
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-12 text-right">
				<button class="btn btn-primary btn-sm" id="updateSettings"><i class="fa fa-save"></i> Submit</button>
			</div>
		</div>

		@{ ActiveForm::end(); }

	</div>

	<div id="password" class="tab-pane fade">
		<!-- h3><i class="fa fa-lock"></i> Password</h3 -->

		<div class="row">
			<div class="col-sm-6">
				@{
				    $form = ActiveForm::begin([
				        'id' => 'hrpassword-form', 
				        'enableClientScript' => true,
				        'fieldConfig' => [
				            'labelOptions' => ['class' => 'col-sm-2 control-label'],
				            'template' => "{label}\n<div class=\"col-sm-10\">{input}{error}</div>",
				        ],
				    ]);
				}

				<div class="hide">
					<input type="hidden" name="action" value="add">
					@$form->field($userPasswordForm, 'account_id')->textInput()
				</div>

				@$form->field($userPasswordForm, 'new')->passwordInput(['class' => 'form-control input-sm', 'autocomplete' => 'off'])
				
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
			$('.working_day').bind('click', function(e) {
				var day = $(this).attr('data-day');

				if ($(this).prop('checked') == true) {
					$('input.working_' + day).prop('checked', false);

					$(this).prop('checked', true);
				}
			});

			$('.leave_type').bind('click', function(e) {
				var leave_type = $(this).attr('data-day');

				if ($(this).prop('checked') == true) {
					$('input.leave_' + leave_type).prop('checked', false);

					$(this).prop('checked', true);
				}
			});

			$('#updateSettings').bind('click', function(e) {
				e.preventDefault();

				modConfirm('Do you want to proceed?', function() {
					serverProcess({
				        action:'account/updatesettings',
				        data: $('#hrsettings-form').serialize() + '&account_id=@($account->account_id)',
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
				        action:'hr/updatepassword',
				        data: $('#hrpassword-form').serialize() + '&hr_id=@($user->hr_id)',
				        show_process:true,
				        callback:function(json){
				            if(json.success){
				                modAlert(json.message);
				                $('#hrpassword-form').trigger('reset');
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