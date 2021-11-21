@{
    use yii\helpers\Url;
    use common\helpers\Common;
    use yii\widgets\ListView;
}

<table class="table full-width">
    <thead>
        <tr>
            <th>@($sort->link('id'))</th>
            <th>Payment Proof</th>
            <th>Log Card</th>
            <th>@($sort->link('fullname'))</th>
            <th>@($sort->link('email'))</th>
            <th>Created</th>
            <th>@($sort->link('status'))</th>
            <th width="10%">Actions</th>
        </tr>
    </thead>
    @{ 
        echo ListView::widget([
                'dataProvider' => $dataProvider, 
                'itemView' => '@common/views/member/_ajax_list_renewal_row.tpl',
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
