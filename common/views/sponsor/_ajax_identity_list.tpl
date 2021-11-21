@{
    use yii\helpers\Url;
    use common\helpers\Common;
}

<div class="table-responsive">
<table class="table">
    <thead>
        <tr>
            <th>Name</th>
            <th>Discription</th>
            <th width="40%">Action</th>
        </tr>
    </thead>

    @if($user->file) {
        @foreach($user->file as $key => $file) {
            <tr>
                <td>@($file->name())</td>
                <td>@($file->decription)</td>
                <td>
                    <div class="btn-group">
                        <a href="@(Url::home())file/identity/@($file->file_id)" class="btn btn-primary btn-sm" title="Download File"><i class="fa fa-download"></i></a>
                        @if(!Common::isStaff()) {
                            <a href="javascript:void(0);" class="btn btn-primary btn-sm removeIdentity" data-id='@($file->file_id)' title="Remove"><i class="fa fa-trash"></i></a>
                        }
                    </div>
                </td>
            </tr>   
        }
    } else {
        <tr>
            <td colspan="99">No files found.</td>
        </tr>
    }
</table> 
</div>