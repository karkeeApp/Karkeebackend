@{
	use yii\helpers\Url;
	use yii\helpers\Html;
    use yii\widgets\ActiveForm;

    use common\models\Notification;
}

@($menu)

<h4><i class="fa fa-envelope"></i> Create Notification</h4>

<div class="row">
	<div class="col-sm-6"> 
		@{
		    $form = ActiveForm::begin([
		        'id' => 'notification-form', 
		        'enableClientScript' => true,
		        'fieldConfig' => [
		            'labelOptions' => ['class' => 'col-sm-2 control-label'],
		            'template' => "{label}\n<div class=\"col-sm-10\">{input}{error}</div>",
		        ],
		    ]);
		}
			<div class="hide">
				@$form->field($notificationForm, 'notification_id')->hiddenInput()

				<input type="hidden" name="action" value="@(($notificationForm->notification_id) ? 'edit' : 'add')">
			</div>

			@$form->field($notificationForm, 'title')->textInput(['class' => 'form-control input-sm'])
			
			@$form->field($notificationForm, 'recipient', ['template' => '{label}<div class="col-sm-10">{input}<div id="recipientHtml" class="input-sm form-control" style="height: auto; min-height: 30px;"></div>{error}<div class="mb10"><a href="javascript:void(0)" id="selectRecipient"><i class="fa fa-address-book"></i> Add recipient</a></div></div>'])->hiddenInput(['class' => 'form-control input-sm'])

			@$form->field($notificationForm, 'message')->textArea(['class' => 'form-control input-sm'])
			
		@{ ActiveForm::end(); }

		<div class="row">
			<div class="col-lg-12 text-right">
				<button class="btn btn-primary btn-sm" id="send"><i class="fa fa-envelope"></i> Send</button>
			</div>
		</div>
	</div>
</div>


<script type="text/javascript">
(function($) {
	var Notification = function() {
		var recipientStaffId = [];

		var removeRecipientCallback = function() {
			$('a.removeRecipient')
			.unbind('click')
			.bind('click', function(e) {
				e.preventDefault();

				var data = {
					recipient : $(this).attr('data-recipient'),
					current_recipient : $('#mfinotificationform-recipient').val()
				};

				serverProcess({
			        action:'notification/removerecipient',
			        data: data,
			        show_process:true,
			        callback:function(json){
			            if(json.success){
			            	$('#recipientHtml').html(json.recipientHtml);
			            	$('#mfinotificationform-recipient').val(json.recipients);

			            	removeRecipientCallback();
			            }else{
			                modAlert(json.error);
			            }
			        }
			    });
			});
		};

		var searchStaffCallback = function() {
			$('input[type=checkbox]', $('#searchContent'))
			.unbind('click')
			.bind('click', function() {
				$('input[type=checkbox]', $('#searchContent')).each(function(i) {
					if ($(this).prop('checked')) {
						recipientStaffId.push($(this).val());
					}
				});
			});		
		};

		var bindEvents = function () {
			$('#send').bind('click', function(e) {
				e.preventDefault();

				modConfirm('Do you want to proceed?', function() {
					$('div.has-error').removeClass('has-error');
					$('div.help-block').empty();

					serverProcess({
				        action:'notification/send',
				        data: $('#notification-form').serialize(),
				        show_process:true,
				        callback:function(json){
				            if(json.success){
				            	modAlert(json.message);
				            	$('#notification-form').trigger('reset');
				            	$('#recipientHtml').empty();
				           	} else if(typeof(json.errorFields) == 'object'){
			           			window.highlightErrors(json.errorFields);
				            }else{
				                modAlert(json.error);
				            }
				        }
				    });
				});
			});

			$('#selectRecipient').bind('click', function(e) {
				e.preventDefault();

				recipientStaffId = [];
				$('#recipientList').val('').trigger('change');
				$('#searchStaffContrainer').hide();
				$('#searchContent').empty();
				$('#recipientModal').modal();
			});

			/**
			 * Recipient modal events
			 */
			$('#recipientList').bind('change', function(e) {
				$('#recipientHRList').val('');
				$('#recipientHRList').trigger('change');

				if ($(this).val() == @(Notification::RECIPIENT_SPECIFIC_HR)) {					
					$('#recipientHRList').show();
				} else {
					$('#recipientHRList').hide();
				}
			});

			$('#recipientHRList').bind('change', function(e) {
				$('#recipientHRFilterList').val('');

				if ($(this).val() != '') {
					$('#recipientHRFilterList').show();
				} else {
					$('#recipientHRFilterList').hide();
				}
			});

			$('#recipientHRFilterList').bind('change', function(e) {
				$('#staffsearch').val('');

				if ($(this).val() == @(Notification::RECIPIENT_HR_SPECIFIC_STAFF)) {
					$('#searchStaffContrainer').show();	
				} else {
					$('#searchStaffContrainer').hide();	
				}
			});

			$('#recipientList').trigger('change');
			$('#recipientHRList').trigger('change');
			$('#recipientHRFilterList').trigger('change');

			$('#addRecipient').bind('click', function(e) {
				var data = {
					current_recipient : $('#mfinotificationform-recipient').val(),
					recipientList : $('#recipientList').val(),					
					recipientHRList : $('#recipientHRList').val(),					
					recipientHRFilterList : $('#recipientHRFilterList').val(),					
					recipientStaffId : JSON.stringify(recipientStaffId)	
				}

				serverProcess({
			        action:'notification/addrecipient',
			        data: data,
			        show_process:true,
			        callback:function(json){
			            if(json.success){
			            	$('#recipientHtml').html(json.recipientHtml);
			            	$('#mfinotificationform-recipient').val(json.recipients);

			            	removeRecipientCallback();

			            	$('#recipientModal').modal('hide');
			            }else{
			                modAlert(json.error);
			            }
			        }
			    });
			});

			$('#searchStaff').bind('click', function(e) {
				e.preventDefault();

				recipientStaffId = [];

				serverProcess({
			        action:'notification/searchstaff',
			        data: {
			        	staffKeyword : $('#staffKeyword').val(),
			        	account_id : $('#recipientHRList').val()
			        },
			        show_process:true,
			        callback:function(json){
			            if(json.success){
			            	$('#searchContent').html(json.content);
			            	searchStaffCallback();
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
		}
	}();

	$(document).ready(function() {
		Notification._construct();
	});
})(jQuery);

</script>
