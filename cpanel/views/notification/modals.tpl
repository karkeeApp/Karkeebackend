@{
	use yii\helpers\Url;
	use yii\helpers\Html;
    use yii\widgets\ActiveForm;

    use common\helpers\Common;

    use common\models\Notification;
}

<div id="viewModal" class="modal fade" role="dialog" style="display: none; z-index: 9999">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><i class="fa fa-bell"></i> Notification</h4>
			</div>
			<div class="modal-body">
			</div>
			<div class="modal-footer">
		        <button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">Close</button>
		    </div>
		</div>

	</div>
</div>

<div id="recipientModal" class="modal fade" role="dialog" style="display: none; z-index: 9999">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><i class="fa fa-address-book"></i> Search Recipient</h4>
			</div>
			<div class="modal-body form-inline">
				<div class="row mb10">
					
					<div class="col-sm-3"><label>Sent to :</label></div>

					<div class="col-sm-9">
						@(Html::dropDownList('recipientList', '', ['' => 'Select recipient'] + Notification::recipients(), ['id' => 'recipientList', 'class' => 'form-control input-sm']) )

						@(Html::dropDownList('recipientHRList', '', ['' => 'Select Company'] + Notification::recipientHR(), ['id' => 'recipientHRList', 'class' => 'form-control input-sm']) )

						@(Html::dropDownList('recipientHRFilterList', '', ['' => 'Select'] + Notification::recipientHRFilter(), ['id' => 'recipientHRFilterList', 'class' => 'form-control input-sm']) )
					</div>
				</div>

				<div class="row" id="searchStaffContrainer">
					<div class="col-sm-3"><label>Search staff :</label></div>
					<div class="col-sm-9">
						<div class="mb10"><input type="text" id="staffKeyword" name="staffKeyword" class="form-control input-sm"> <button class="btn btn-primary btn-sm" id="searchStaff"><i class="fa fa-search"></i></button>

						<div id="searchContent" style="max-height: 200px; overflow: auto; "></div>

					</div>					
				</div>
			</div>
			<div class="modal-footer">
		        <button type="button" class="btn btn-success btn-sm" id="addRecipient">Add</button>
		        <button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">Close</button>
		    </div>
		</div>

	</div>
</div>

