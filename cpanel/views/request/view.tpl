@{
	use common\models\HRStaffUpdate;	
}

@($menu)

<div class="row">
	<div class="col-sm-3">Date</div>
	<div class="col-sm-9">@($request->date())</div>
</div>

<div class="row">
	<div class="col-sm-3">Account</div>
	<div class="col-sm-9">@($request->hrname())</div>
</div>

<div class="row">
	<div class="col-sm-3">Staff</div>
	<div class="col-sm-9">@($request->staffname())</div>
</div>

<div class="row">
	<div class="col-sm-3">Type</div>
	<div class="col-sm-9">@($request->type())</div>
</div>

<div class="row">
	<div class="col-sm-3">Status</div>
	<div class="col-sm-9">@($request->status())</div>
</div>

<h4>Updates</h4>

<div class="row">
	@foreach(['before_attributes' => 'Before', 'after_attributes' => 'Then'] as $key => $label) {
		<div class="col-sm-6">
			<p>@($label)</p>
			<table class="table">
				<thead>
					<tr>
						<th>Field</th>
						<th>Value</th>
					</tr>
				</thead>
				@foreach(json_decode($request->$key) as $field => $value) {
					<tr class="@(($label == 'Before') ? 'text-danger' : 'text-success')">
						<td>@($field)</td>
						<td>@($value)</td>
					</tr>
				}
			</table>
		</div>
	}
</div>

@if($request->status == HRStaffUpdate::STATUS_PENDING) {
	<div class="text-right">
		<a class="btn btn-sm btn-danger update" data-status='@(HRStaffUpdate::STATUS_REJECTED)'><i class="fa fa-close"></i> Reject</a>
		<a class="btn btn-sm btn-success update" data-status='@(HRStaffUpdate::STATUS_APPROVED)'><i class="fa fa-check"></i> Approve</a>
	</div>
}

<script type="text/javascript">
(function($) {
	var Request = function() {
		var bindEvents = function() {
			$('a.update').bind('click', function(e) {
				e.preventDefault();

				var status = $(this).attr('data-status');

				modConfirm('Do you want to proceed?', function() {
					serverProcess({
						action : 'request/update',
						data : {
							status : status,
							id : @($request->id)
						},
						show_process : true,
						callback : function(json) {
							if (json.success) {
								window.location.reload();
							} else {
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
		};
	}();

	$(document).ready(function() {
		Request._construct();
	});
})(jQuery);

</script>

