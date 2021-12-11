<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\forms\UserForm;
use common\models\HRNotification;

class NotificationController extends Controller
{
    public $menu;

    public function behaviors()
    {
        $this->menu = $this->renderPartial('@common/views/account/notification_menu.tpl');

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

    public function actionIndex()
    {
        $data['hr'] = Yii::$app->user->getIdentity();

        $data['menu'] = $this->menu;

        $data['controller'] = 'account';

        return $this->render('@common/views/account/notification.tpl', $data); 
    }

    public function actionView($id=0)
    {
        $data['menu'] = $this->menu;

        $data['notification'] = HRNotification::findOne($id);

        if (!$data['notification']) {
            throw new \yii\web\HttpException(404, 'Notification not found.');
        }

        $hr = Yii::$app->user->getIdentity();
        $account = $hr->account;

        if ($account->account_id != $data['notification']->account_id) {
            throw new \yii\web\HttpException(404, 'Notification not found.');            
        }

        $data['notification']->is_read = 1;
        $data['notification']->save();

        return $this->render('@common/views/account/notification_view.tpl', $data);     
    }
}
