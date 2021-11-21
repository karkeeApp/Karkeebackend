@{
	use yii\helpers\Url;
}

<ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
	@if($notifications) {
		@foreach($notifications as $notification) {
		    <li>
		        <a href="@(Url::home())notification/@($notification->notification_id)">
					<span>
						<span class="time">@($notification->time())</span><br />
		                <span class="bold">@($notification->title)</span>
					</span>
					<span class="message">@($notification->excerpt())</span>
		        </a>
		    </li>
		}
	}

	<li>
        <div class="text-center">
            <a href="@(Url::home())notification/">
                <strong>See All</strong>
                <i class="fa fa-angle-right"></i>
            </a>
        </div>
    </li>
</ul>