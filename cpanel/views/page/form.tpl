@{
	use yii\helpers\Url;
	use yii\helpers\Html;
    use yii\widgets\ActiveForm;
}

@($menu)

		@{
		    $form = ActiveForm::begin([
		        'id' => 'page-form', 
		        'enableClientScript' => true,
		        'fieldConfig' => [
		            'labelOptions' => ['class' => 'col-sm-2 control-label'],
		            'template' => "{label}\n<div class=\"col-sm-10\">{input}{error}</div>",
		        ],
		    ]);
		}
			
			<div class="hide">
				@$form->field($pageForm, 'page_id')->hiddenInput()

				<input type="hidden" name="action" value="@(($pageForm->page_id) ? 'edit' : 'add')">
			</div>

			@$form->field($pageForm, 'name')->textInput(['class' => 'form-control input-sm'])
			@$form->field($pageForm, 'title')->textInput(['class' => 'form-control input-sm'])
			@$form->field($pageForm, 'content')->textArea(['class' => 'form-control input-sm', 'style' => 'height: 500px;'])
		@{ ActiveForm::end(); }

		<div class="clearboth"></div>

		<div class="row">
			<div class="col-lg-12 text-right">
				<button class="btn btn-primary btn-sm" id="save"><i class="fa fa-save"></i> Save</button>
			</div>
		</div>

<script type="text/javascript">
(function($) {
	$('#save')
	.bind('click', function(e) {
		e.preventDefault();

		serverProcess({
	        action:'page/update',
	        data: $('#page-form').serialize(),
	        show_process:true,
	        callback:function(json){
	            if(json.success){
	            	window.location.href = '@(Url::Home())page/edit/' + json.page_id;
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
