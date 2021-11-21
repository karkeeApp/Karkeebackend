@{
	use yii\helpers\Url;
	use yii\helpers\Html;
    use yii\widgets\ActiveForm;
	use common\forms\FileForm;
	use common\forms\UserForm;
	use common\models\User;
}

<div id="make-admin-modal" class="modal fade" role="dialog" style="display: none; z-index: 9999">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><i class="fa fa-paperclip"></i> Add to admin</h4>
			</div>
			<div class="modal-body">
				@{
                    $form = ActiveForm::begin([
                        'id' => 'admin-role-form', 
                        'enableClientScript' => true,
                        'fieldConfig' => [
                            'labelOptions' => ['class' => 'col-sm-2 control-label'],
                            'template' => "{label}\n<div class=\"col-sm-10\">{input}{error}</div>",
                        ],
                    ]);
                }

                <div class="hide">
                    <input type="hidden" name="action" value="admin_add">
                    @$form->field($adminRoleForm, 'user_id')->textInput()
                </div>

                @$form->field($adminRoleForm, 'role')->dropDownList(User::roles(), ['class' => 'form-control input-sm', 'autocomplete' => 'off'])
                
                @{ ActiveForm::end(); }
			</div>
			<div class="modal-footer">
		        <button type="button" class="btn btn-success btn-sm" id="addToAdmin">Save</button>
		        <button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">Close</button>
		    </div>
		</div>

	</div>
</div>