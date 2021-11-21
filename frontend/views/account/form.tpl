@{
	use yii\helpers\Url;
	use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use common\forms\UserForm;
    use common\models\Country;
}

@($menu)

@{
    $form = ActiveForm::begin([
        'id' => 'user-form', 
        'enableClientScript' => true,
        'fieldConfig' => [
            'labelOptions' => ['class' => 'control-label'],
            'template' => "<div class='col-sm-6 col-md-3'>{label}</div>\n<div class=\"col-sm-6 col-md-9\">{input}{error}</div>",
            'options' => [
            	'class' => 'row',
            ]
        ],
    ]);
}

	<ul class="nav nav-tabs">
		<li class="active"><a data-toggle="tab" href="#personal-information"><i class="fa fa-user"></i> Personal Profile</a></li>
	</ul>

	<div class="tab-content" style="padding-top: 20px;">
		<div id="personal-information" class="tab-pane fade in active">
			<h3><i class="fa fa-user"></i> Personal Information</h3>

		    @$form->field($userForm, 'firstname')->textInput(['class' => 'form-control input-sm'])
			@$form->field($userForm, 'lastname')->textInput(['class' => 'form-control input-sm'])
			@$form->field($userForm, 'citizenship')->dropDownList(Country::all(), ['class' => 'form-control input-sm'])
			@$form->field($userForm, 'birthday')->textInput(['class' => 'form-control input-sm'])
			@$form->field($userForm, 'gender')->dropDownList(UserForm::genders(), ['class' => 'form-control input-sm'])
			@$form->field($userForm, 'current_address')->textInput(['class' => 'form-control input-sm'])
			@$form->field($userForm, 'alternative_address')->textInput(['class' => 'form-control input-sm'])
            @$form->field($userForm, 'own_residential')->dropDownList(UserForm::yesNo(), ['class' => 'form-control input-sm'])
			@$form->field($userForm, 'marital_status')->dropDownList(UserForm::maritals(), ['class' => 'form-control input-sm'])
			@$form->field($userForm, 'children')->dropDownList(UserForm::childrens(), ['class' => 'form-control input-sm'])
            @$form->field($userForm, 'education_level')->dropDownList(UserForm::educationLevels(), ['class' => 'form-control input-sm'])

			@$form->field($userForm, 'home_number')->textInput(['class' => 'form-control input-sm'])

			@$form->field($userForm, 'id_type')->dropDownList(UserForm::idTypes(), ['class' => 'form-control input-sm'])
			@$form->field($userForm, 'id_number')->textInput(['class' => 'form-control input-sm'])

		    <ul class="list-inline pull-right">
		        <li><button type="button" class="btn btn-primary btn-sm next-step" id="save"><i class="fa fa-save"></i> Save</button></li>
		    </ul>
		</div>
	</div>

@{ ActiveForm::end(); }

<script type="text/javascript">
	(function($) {
		$('#save')
				.bind('click', function(e) {
					e.preventDefault();
					serverProcess({
						action:'account/update',
						data: $('#user-form').serialize(),
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
	})(jQuery);

</script>
