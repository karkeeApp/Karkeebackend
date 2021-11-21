@{
use yii\helpers\Url;
use common\helpers\Common;
use yii\widgets\ListView;
}

<div class="table-responsive">
    <table class="table">
        <thead>
        <tr>

            <th width="5%">@($sort->link('id'))</th>
            <th width="10%">@($sort->link('user_id'))</th>
            <th width="10%">@($sort->link('name'))</th>
            <th width="15%">Amount</th>
            <th width="15%">Description</th>
            <th width="20%">Image</th>
            <th width="10%">Status</th>
            <th width="25%">Actions</th>
        </tr>
        </thead>
        @{
        echo ListView::widget([
            'dataProvider' => $dataProvider,
            'itemView' => '@common/views/userpayment/_ajax_list_row.tpl',
            'itemOptions' => [
                'tag' => 'tr',
            ],
            'options' => [
                'tag' => 'tbody',
                'class' => 'list-wrapper',
                'id' => 'list-wrapper',
            ],
            'layout' => "{items}{pager}"
            ])
        }
    </table>
</div>
