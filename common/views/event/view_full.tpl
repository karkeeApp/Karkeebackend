@if(!$event OR $event->account_id != $user->account_id) {
    <h3>Page not found</h3>
} else {
    <h3>@($event->title)</h3>

    <div data-type="full">@($event->content)</div>
}