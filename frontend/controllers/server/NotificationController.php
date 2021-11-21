<?php
namespace frontend\controllers\server;

use Yii;
use yii\web\Controller;
use yii\bootstrap\ActiveForm;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\forms\UserForm;
use common\models\User;
use common\models\UserNotification;
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
        $user_id = Yii::$app->request->post('user_id', 0);

        /**
         * Validate if staff belong to current account
         */
        if (Common::isMFI()) {
            $user = User::findOne($user_id);
        } elseif (Common::isHR()) {    
            $user = HRHelper::staff($user_id, FALSE);
        } else {
            $user = Yii::$app->user->getIdentity();
        }

        if (!$user) {
            Common::json([
                'success' => TRUE,
                'content' => 'User not found.',
            ]);
            return;
        }


        /**
         * get list
         */
        $cpage = Yii::$app->request->post('page', 1);

        $qry = UserNotification::find()->where(['user_id' => $user->user_id]);

        $total = $qry->count();

        $page = new PaginationLib( $total, $cpage, 10);
        $page->url = '#list';

        $qry->orderBy(['notification_id' => SORT_DESC]);

        $data['notifications'] = $qry->limit($page->limit())->offset($page->offset())->all();
        $data['page'] = $page;

        $data['controller'] = (Common::isStaff()) ? 'account' : 'staff';

        Common::json([
            'success' => TRUE,
            'content' => $this->renderPartial('@common/views/user/_ajax_notification.tpl', $data),
        ]);
    }
}
