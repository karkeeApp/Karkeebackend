@{
    use yii\helpers\Url;
    use yii\widgets\ListView;
}

<div class="table-responsive">
<table class="table">
    <thead>
        <tr>
            <th width="15%">@($sort->link('id'))</th>
            <th>Photo</th>
            <th width="15%">@($sort->link('fullname'))</th>
            <th width="15%">@($sort->link('email'))</th>
            <th>Role</th>
            <th width="25%">Actions</th>
        </tr>
    </thead>
    @{ 
        echo ListView::widget([
                'dataProvider' => $dataProvider, 
                'itemView' => '@common/views/accountadmin/_ajax_list_row.tpl',
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
