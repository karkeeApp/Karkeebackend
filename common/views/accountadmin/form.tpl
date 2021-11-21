@{
	use yii\helpers\Url;
	use yii\helpers\Html;
    use yii\widgets\ActiveForm;

    use common\forms\accountUserForm;
    use common\models\AccountUser;
    use common\helpers\Common;
}


@($menu)


<div class="row">
	<div class="col-sm-6">

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

<div class="hide">
	@$form->field($accountUserForm, 'user_id')->hiddenInput()

	<input type="hidden" name="action" value="@(($accountUserForm->user_id) ? 'edit' : 'add')">
</div>

<div class="container">

	@$form->field($accountUserForm, 'username')->textInput(['class' => 'form-control input-sm'])
	@$form->field($accountUserForm, 'email')->textInput(['class' => 'form-control input-sm'])
	@$form->field($accountUserForm, 'password')->textInput(['class' => 'form-control input-sm'])
	@$form->field($accountUserForm, 'role')->dropDownList(['' => 'Select role'] + AccountUser::roles(), ['class' => 'form-control input-sm'])
	@$form->field($accountUserForm, 'status')->dropDownList(AccountUser::statuses(), ['class' => 'form-control input-sm'])
		
</div>
@{ ActiveForm::end(); }

<script type="text/javascript">
(function($) {
	$('#save')
	.bind('click', function(e) {
		e.preventDefault();

		var data = $('#user-form').serialize();

		@if(Common::isCpanel()){
			@if(isset($account)) {
				data += '&account_id=@($account->account_id)';
			} elseif(isset($user)) {
				data += '&account_id=@($user->account_id)';
			}
		}

		serverProcess({
	        action:'accountadmin/update',
	        data: data,
	        show_process:true,
	        callback:function(json){
	            if(json.success){
	            	modAlert(json.message);
	            	
	            	@if(!isset($user)) {
	            		window.location.href = '@(Url::Home())accountadmin/edit/' + json.user_id;
	           		}
	           	} else if(typeof(json.errorFields) == 'object'){
           			window.highlightErrors(json.errorFields);
	            }else{
	                modAlert(json.error);
	            }
	        }
	    });
	});
})(jQuery);

</script>