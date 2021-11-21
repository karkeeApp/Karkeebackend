<?php
namespace backend\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use common\models\User;

use common\helpers\Common;
use common\helpers\HRHelper;

class AccountadminController extends \common\controllers\AccountadminController
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
                        'actions' => parent::userActions(),
                        'allow' => true,
                    ],
                ],
            ],
        ];
    }

    private function menu($data)
    {
        return $this->renderPartial('menu.tpl', $data);
    }
    
    public function actionIndex()
    {
        return $this->actionList();
    }

    public function actionList($id=0)
    {
        $user = Yii::$app->user->getIdentity();
        $data['account'] = $user->account;    

        if (!$data['account']) {
            throw new \yii\web\HttpException(404, 'Account not found.');
        }

        $data['subTitle'] = '(Users)';

        $data['menu'] = $this->menu($data);

        return $this->render('list.tpl', $data);
    }
}