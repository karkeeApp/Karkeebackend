@if(!$events OR !$events->isCarkeeNews()) {
	<h3>Page not found</h3>
} else {
	@if ($events->image) {
		<div style="margin-bottom: 10px;"><img class="img-responsive" src="@($events->imagelink())" /></div>
	}

	<h3>@($events->title)</h3>

	<div style="text-align: justify;">@($events->content)</div>
}