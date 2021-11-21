@if(!$news OR !$news->isCarkeeNews()) {
	<h3>Page not found</h3>
} else {
	@if ($news->image) {
		<div style="margin-bottom: 10px;"><img class="img-responsive" src="@($news->imagelink())" /></div>
	}

	<h3>@($news->title)</h3>

	<div style="text-align: justify;">@($news->content)</div>
}