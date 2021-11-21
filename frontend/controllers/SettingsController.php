<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use common\forms\PasswordForm;
use common\forms\EmailForm;
use common\forms\MobileForm;

class SettingsController extends Controller
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
                        'actions' => ['index', 'password', 'email', 'mobile'],
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

        $data['user'] = Yii::$app->user->getIdentity();

        $data['passwordForm'] = new PasswordForm;

        $data['emailForm'] = new EmailForm;
        $data['emailForm']->setAttributes($data['user']->attributes, FALSE);

        $data['mobileForm'] = new MobileForm;
        $data['mobileForm']->setAttributes($data['user']->attributes, FALSE);

        return $this->render('index.tpl', $data);
    }

    public function actionPassword()
    {
        $data['menu'] = $this->menu;

        $data['passwordForm'] = new PasswordForm;

        return $this->render('password.tpl', $data);
    }

    public function actionEmail()
    {
        $data['menu'] = $this->menu;

        $data['user'] = Yii::$app->user->getIdentity();
        $data['emailForm'] = new EmailForm;
        $data['emailForm']->setAttributes($data['user']->attributes, FALSE);

        return $this->render('email.tpl', $data);
    }

    public function actionMobile()
    {
        $data['menu'] = $this->menu;
        
        $data['user'] = Yii::$app->user->getIdentity();
        $data['mobileForm'] = new MobileForm;
        $data['mobileForm']->setAttributes($data['user']->attributes, FALSE);

        return $this->render('mobile.tpl', $data);
    }

}
