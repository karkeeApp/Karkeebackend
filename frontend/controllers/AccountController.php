<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\forms\UserForm;
use common\models\User;
use common\helpers\Common;

class AccountController extends \common\controllers\StaffController
{
    public $menu;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index', 'history', 'notifications', 'summary'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->actionView();
    }

    private function _getUser($id)
    {
        $data['user'] = Yii::$app->user->getIdentity();

        return $data;
    }

    public function actionEdit($id = 0)
    {
        $data = $this->_getUser($id);
        $data['controller'] = (Common::isStaff()) ? 'account' : 'staff';
        $data['menu'] = $this->renderPartial('@frontend/views/account/staff_menu.tpl', $data);
        $data['userForm'] = new UserForm;
        $data['userForm']->setAttributes($data['user']->attributes, FALSE);

        return $this->render('@frontend/views/account/form.tpl', $data);
    }
}
