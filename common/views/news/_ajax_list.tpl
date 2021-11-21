@{
    use yii\helpers\Url;
    use common\helpers\Common;
    use yii\widgets\ListView;
}

<div class="table-responsive">
<table class="table">
    <thead>
        <tr>
            <th width="15%">@($sort->link('id'))</th>
            <th width="15%">@($sort->link('title'))</th>
            <th width="20%">Image</th>
            <th>Date</th>
            <th>Category</th>
            <th width="25%">Actions</th>
        </tr>
    </thead>
    @{ 
        echo ListView::widget([
                'dataProvider' => $dataProvider, 
                'itemView' => '@common/views/news/_ajax_list_row.tpl',
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
