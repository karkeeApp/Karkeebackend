<?php
namespace backend\controllers\server;

use Yii;
use yii\web\Controller;
use yii\bootstrap\ActiveForm;
use yii\filters\AccessControl;
use common\models\HRNotification;
use common\helpers\Common;
use common\lib\PaginationLib;

class NotificationController extends Controller
{
    public $menu;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['list'],
                        'allow' => true,
                    ],
                ],
            ],
        ];
    }

    public function actionList()
    {
        $hr = Yii::$app->user->getIdentity();
        $account = $hr->account;

        $cpage = Yii::$app->request->post('page', 1);

        $qry = HRNotification::find()->where(['hr_id' => $hr->hr_id]);

        $total = $qry->count();

        $page = new PaginationLib( $total, $cpage, 10);
        $page->url = '#list';

        $qry->orderBy(['notification_id' => SORT_DESC]);

        $data['notifications'] = $qry->limit($page->limit())->offset($page->offset())->all();
        $data['page'] = $page;

        $data['controller'] = 'account';

        return [
            'success' => TRUE,
            'content' => $this->renderPartial('@common/views/account/_ajax_notification.tpl', $data),
        ];
    }

}
