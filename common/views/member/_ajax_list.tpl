@{
    use yii\helpers\Url;
    use common\helpers\Common;
    use yii\widgets\ListView;
}

<table class="table full-width">
    <thead>
        <tr>
            <th>@($sort->link('id'))</th>
            <th>Photo</th>
            <th>@($sort->link('fullname'))</th>
            <th>@($sort->link('email'))</th>
            <th>Mem.Status</th>
            <th>Expiry</th>
            <th>Appro.At</th>
            <th>Reg.Date</th>
            <th>@($sort->link('status'))</th>
            <th width="10%">Actions</th>
        </tr>
    </thead>
    @{ 
        echo ListView::widget([
                'dataProvider' => $dataProvider, 
                'itemView' => '@common/views/member/_ajax_list_row.tpl',
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
