@if(!$news) {
    <h3>Page not found</h3>
} else {
    <h3>@($news->title)</h3>

    <div data-type="partial">@($news->content)</div>
}