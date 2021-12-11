<?php
namespace cpanel\controllers;

use Yii;
use yii\web\View;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use common\forms\MFINotificationForm;
use common\models\Notification;

class NotificationController extends \common\controllers\cpanel\Controller
{
    public $menu;

    public function behaviors()
    {
        $this->menu = $this->renderPartial('menu.tpl');

        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index', 'add', 'test'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $data['menu'] = $this->menu;

        Yii::$app->view->on(View::EVENT_END_BODY, function () {
            $data = [];

            echo $this->renderPartial('modals.tpl', $data);  
        });
        
        return $this->render('list.tpl', $data);
    }

    public function actionAdd()
    {
        $data['menu'] = $this->menu;

        $data['notificationForm'] = new MFINotificationForm;

        Yii::$app->view->on(View::EVENT_END_BODY, function () {
            $data = [];

            echo $this->renderPartial('modals.tpl', $data);  
        });

        return $this->render('add.tpl', $data);
    }

    public function actionTest()
    {
        $notification = Notification::findOne(2);
        $notification->send();
    }
}