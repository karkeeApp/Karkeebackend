@{
    use yii\helpers\Url;
    use common\helpers\Common;
    use yii\widgets\ListView;
}

<div class="table-responsive">
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Photo</th>
            <th>Member Name</th>
            <th>Company</th>
            <th>Email</th>
            <th>Date Created</th>
            <th>Club</th>
            <th>Type</th>
            <th>Status</th>
            <th width="20%">Actions</th>
        </tr>
    </thead>
    @{ 
        echo ListView::widget([
                'dataProvider' => $dataProvider, 
                'itemView' => '@common/views/vendor/_ajax_list_carkee_member_row.tpl',
                'itemOptions' => [
                    'tag' => 'tr',
                ],
                'options' => [
                    'tag' => 'tbody',
                    'class' => 'list-wrapper',
                    'id' => 'list-wrapper',
                ],
                'layout' => "{items}{pager}"
            ] 
        )
    }
</table>
</div>
