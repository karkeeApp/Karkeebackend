@{
	use common\helpers\Common;
}

@($menu)

<h3>@($notification->title)</h3>
<span>Date : @($notification->date())</span>

<br />
<br />

<pre>@($notification->message)</pre>

