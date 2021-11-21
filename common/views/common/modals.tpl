<div id="msgBox" class="modal fade" role="dialog" style="display: none; z-index: 99999">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">@(Yii::t('app', 'Message'))</h4>
			</div>
			<div class="modal-body">

			</div>
			<div class="modal-footer">
		        <button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">@(Yii::t('app', 'Close'))</button>
		    </div>
		</div>

	</div>
</div>

<div id="msgBoxConfirm" class="modal fade" role="dialog" style="display: none; z-index: 99999">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Message</h4>
			</div>
			<div class="modal-body">

			</div>
			<div class="modal-footer">
		        <button type="button" class="btn btn-success yes btn-sm">Yes</button>
		        <button type="button" class="btn btn-primary no btn-sm">No</button>
		    </div>
		</div>

	</div>
</div>

<div id="msgBoxProcessing" class="modal fade" role="dialog" style="display: none; z-index: 99999">
	<div class="modal-dialog" style="width: 400px">
		<div class="modal-content">
			<div class="modal-body">
				@(Yii::t('app', 'Processing!')) <i class="fa fa-spinner fa-spin"></i>
			</div>
		</div>

	</div>
</div>
