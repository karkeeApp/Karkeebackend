@{
    use yii\helpers\Url;
    use common\helpers\Common;
}

<h4><i class="fa fa-home"></i> Home</h4>

<hr />

@if($logs) {
    <h4 class="pull-left">Latest Logs</h4>
    <div class="pull-right"><a href="@(Url::home())log">View All</a></div>
    
    <div class="clearboth table-responsive">
        <table class="table">
            <tr>
                <td>Date</td>
                <td>Message</td>
            </tr>

            @foreach($logs as $log) {
                <tr>
                    <td>@(Common::systemDateFormat($log->created_at))</td>
                    <td>@($log->message())</td>
                </tr>
            }
        </table>
    </div>
}
