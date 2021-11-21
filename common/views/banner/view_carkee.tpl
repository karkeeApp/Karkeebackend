@if(!$bannerimage OR !$bannerimage->isCarkeeBanner()) {
	<h3>Page not found</h3>
} else {
	@if ($bannerimage->filename) {
		<div style="margin-bottom: 10px;"><img class="img-responsive" src="@($bannerimage->imagelink())" /></div>
	}

	<h3>@($bannerimage->title)</h3>

	<div style="text-align: justify;">@($bannerimage->content)</div>
}