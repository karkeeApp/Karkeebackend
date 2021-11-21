@{
    use yii\helpers\Url;
    use common\helpers\Common;
    use yii\widgets\ListView;
}

<div class="table-responsive">
<table class="table full-width">
    <thead>
        <tr>
            <th>@($sort->link('id'))</th>
            <th>Photo</th>
            <th>@($sort->link('fullname'))</th>
            <th width="10%">Actions</th>
        </tr>
    </thead>
    @{ 
        echo ListView::widget([
                'dataProvider' => $dataProvider, 
                'itemView' => '@common/views/event/_ajax_attendees_list_row.tpl',
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


