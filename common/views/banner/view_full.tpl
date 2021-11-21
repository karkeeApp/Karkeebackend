@if(!$account OR !$bannerimage OR $bannerimage->account_id != $account->account_id) {
    <h3>Page not found</h3>
} else {
    <h3>@($bannerimage->title)</h3>

    <div data-type="full">@($bannerimage->content)</div>
}