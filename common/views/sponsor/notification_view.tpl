@{
	use common\helpers\Common;
}

<h3>@($notification->title)</h3>
<span>Date : @($notification->date())</span>

<br />
<br />

<pre>@($notification->message)</pre>

