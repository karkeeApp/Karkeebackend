@{
    use yii\helpers\Url;
    use common\helpers\Common;
    use yii\widgets\ListView;
}

<div class="table-responsive">
<table class="table">
    <thead>
        <tr>
            <th width="10%">@($sort->link('id'))</th>
            <th width="15%">@($sort->link('title'))</th>
            <th>Description</th>
            <th width="15%">Vendor</th>
            <th width="10%">Status</th>
            <th width="10%">Actions</th>
        </tr>
    </thead>
    @{ 
        echo ListView::widget([
                'dataProvider' => $dataProvider, 
                'itemView' => '@common/views/listing/_ajax_list_row.tpl',
                'itemOptions' => [
                    'tag' => 'tr',
                ],
                'options' => [
                    'tag' => 'tbody',
                    'class' => 'list-wrapper',
                    'id' => 'list-wrapper',
                ],
                'pager' => [
                'firstPageLabel' => 'first',
                'lastPageLabel' => 'last',
                'nextPageLabel' => '>>',
                'prevPageLabel' => '<<',
                'maxButtonCount' => 10,
                ],
                'layout' => "{items}{pager}"
            ] 
        )
    }
</table>
</div>
