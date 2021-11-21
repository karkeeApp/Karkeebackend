<?php
namespace common\controllers\cpanel\server;

use Yii;
use yii\web\UploadedFile;
use yii\bootstrap\ActiveForm;

use common\helpers\MyActiveDataProvider;
use common\helpers\Sort;

use common\models\Watchdog;
use common\helpers\Common;
use common\lib\PaginationLib;

class LogController extends Controller
{
	public function actionList()
    {
        $user    = Yii::$app->user->identity;

        $data['page']    = Yii::$app->request->post('page', 1);
        $data['keyword'] = Yii::$app->request->post('keyword', NULL);
        $data['filter']  = Yii::$app->request->post('filter', NULL);

        $_GET['hashUrl'] = '#list/filter/';

        foreach(['status', 'keyword', 'sort', 'page'] as $key) {
            $_GET[$key] = Yii::$app->request->post($key);
        }

        if (empty($_GET['sort'])) $_GET['sort'] = '-created_at';

        $data['sort'] = new Sort([
            'attributes' => [
                'created_at' => [
                    'desc'    => ['created_at' => SORT_DESC],
                    'asc'     => ['created_at' => SORT_ASC],
                    'default' => SORT_DESC,
                    'label'   => 'Date',
                ],
            ],
            'defaultOrder' => ['created_at' => SORT_DESC],
        ]);

        $data['sort']->route = 'log#list/filter';

        $qry = Watchdog::find()->where('1=1');

        if (Common::isCpanel()) {
            $qry->andWhere(['account_id' => 0]);
        }

        if ($data['keyword']) {
            $qry->andFilterWhere([
                'or', 
                ['LIKE', 'message', $data['keyword']],
                ['LIKE', 'variables', $data['keyword']],
            ]);
        }

        $qry->orderBy($data['sort']->orders);

        $data['dataProvider'] = new MyActiveDataProvider([
            'query' => $qry,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return [
            'success' => TRUE,
            'content' => $this->renderPartial('@common/views/log/_ajax_list.tpl', $data),
        ];
    }
}