<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\forms\UserForm;
use common\models\User;
use common\models\UserNotification;

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
                        'actions' => ['index', 'view'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    

    public function actionIndex($id=0)
    {
        $data['user'] = Yii::$app->user->getIdentity();
        $data['menu'] = $this->renderPartial('@frontend/views/account/notification_menu.tpl', $data);
        $data['controller'] = 'staff';

        return $this->render('@common/views/user/notification.tpl', $data);     
    }

    public function actionView($id=0)
    {
        $data['notification'] = UserNotification::findOne($id);

        if (!$data['notification']) {
            throw new \yii\web\HttpException(404, 'Notification not found.');
        }

        $user = Yii::$app->user->getIdentity();

        if ($user->user_id != $data['notification']->user_id) {
            throw new \yii\web\HttpException(404, 'Notification not found.');            
        }

        $data['notification']->is_read = 1;
        $data['notification']->save();

        return $this->render('@common/views/user/notification_view.tpl', $data);     
    }
}
