<div class="row mb10">
	<label class="col-sm-4">Title</label>
	<div class="col-sm-8">
		<div class="input-sm form-control" style="height: auto; min-height: 30px;">@($notification->title)</div>
	</div>
</div>

<div class="row mb10">
	<label class="col-sm-4">Recipient</label>
	<div class="col-sm-8">
		<div class="input-sm form-control" style="height: auto; min-height: 30px; max-height: 100px; overflow: auto;">@($notification->recipient())</div>
	</div>
</div>

<div class="row mb10">
	<label class="col-sm-4">Message</label>
	<div class="col-sm-8">
		<div class="input-sm form-control" style="height: auto; min-height: 30px; height: 250px; overflow: auto;">@(nl2br($notification->message))</div>
	</div>
</div>
