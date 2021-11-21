@{
use yii\helpers\Url;
use common\helpers\Common;
use yii\widgets\ListView;
}

<div class="table-responsive">
    <table class="table">
        <thead>
        <tr>
            <th width="20%">Renewal Fee</th>
            <th width="20%">Actions</th>
        </tr>
        </thead>
        @{
        echo ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => '@common/views/settings/_ajax_list_row.tpl',
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
