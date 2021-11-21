@{
use yii\helpers\Url;
use common\helpers\Common;
use yii\widgets\ListView;
}

<div class="table-responsive" >
    <table class="table">
        <thead>
        <tr>

            <th width="15%">@($sort->link('id'))</th>
            <th width="15%">@($sort->link('name'))</th>
            <th width="25%">Description</th>
            <th width="20%">Image</th>
            <th width="20%">Is Bottom</th>
            <th width="25%">Actions</th>
        </tr>
        </thead>
        @{
        echo ListView::widget([
            'dataProvider' => $dataProvider,
            'itemView' => '@common/views/ads/_ajax_list_row.tpl',
            'itemOptions' => [
                'tag' => 'tr',
            ],
            'options' => [
                'tag' => 'tbody',
                'class' => 'list-wrapper table-responsive',
                'id' => 'list-wrapper',
            ],
            'layout' => "{items}{pager}"
            ])
        }
    </table>
</div>
