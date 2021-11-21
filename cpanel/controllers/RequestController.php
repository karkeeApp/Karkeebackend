<?php
namespace cpanel\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use common\models\HRStaffUpdate;

class RequestController extends \common\controllers\cpanel\Controller
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
        $data['menu'] = $this->menu;

        return $this->render('list.tpl', $data);
    }

    public function actionView($id=0)
    {
        $data['menu'] = $this->menu;

        $data['request'] = HRStaffUpdate::findOne($id);

        if (!$data['request']) {
            throw new \yii\web\HttpException(404, 'Request not found.');
        }

        return $this->render('view.tpl', $data);
    }
}
