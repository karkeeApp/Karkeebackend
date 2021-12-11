<?php
namespace cpanel\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use common\forms\AccountForm;
use common\forms\UserForm;
use common\forms\AccountUserSettingsForm;
use common\forms\AccountUserPasswordForm;
use common\forms\AccountUserForm;

use common\models\Account;

class AccountController extends \common\controllers\cpanel\AccountController
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
                        'actions' => ['index', 'add', 'edit', 'view', 'settings', 'members', 'loans', 'admins', 'user-add', 'adminadd'],
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

        return $this->render('index.tpl', $data);
    }

    public function actionAdd()
    {
        $data['menu'] = $this->menu;

        $data['accountForm'] = new AccountForm;

        return $this->render('form.tpl', $data);
    }

    public function actionView($id)
    {
        $data['account'] = Account::findOne($id);

        if (!$data['account']) {
            throw new \yii\web\HttpException(404, 'Account not found.');
        }

        $data['user'] = $data['account']->user;

        $data['menu'] = $this->renderPartial('account_menu.tpl', $data);

        $data['userForm'] = new UserForm;
        $data['userForm']->setAttributes($data['user']->attributes, FALSE);

        return $this->render('view.tpl', $data);
    }

    public function actionEdit($id=0)
    {
        $data['account'] = Account::findOne($id);

        if (!$data['account']) {
            throw new \yii\web\HttpException(404, 'Account not found.');
        }

        $data['subTitle'] = ' - Edit Account';
        $data['menu'] = $this->renderPartial('account_menu.tpl', $data);

        $data['accountForm'] = new AccountForm;
        $data['accountForm']->setAttributes($data['account']->attributes, FALSE);

        return $this->render('form.tpl', $data);
    }

    public function actionMembers($id=0)
    {
        $data['account'] = Account::findOne($id);

        if (!$data['account']) {
            throw new \yii\web\HttpException(404, 'Account not found.');
        }

        $data['subTitle'] = '- Account Members';

        $data['menu'] = $this->renderPartial('account_menu.tpl', $data);

        return $this->render('/member/list.tpl', $data);
    }

    public function actionLoans($id=0)
    {
        $data['account'] = Account::findOne($id);

        if (!$data['account']) {
            throw new \yii\web\HttpException(404, 'Account not found.');
        }

        $data['menu'] = $this->renderPartial('account_menu.tpl', $data);

        return $this->render('@common/views/loan/list.tpl', $data);
    }

    public function actionAdmins($id=0)
    {
        $data['account'] = Account::findOne($id);

        if (!$data['account']) {
            throw new \yii\web\HttpException(404, 'Account not found.');
        }

        $data['subTitle'] = '- Account Admins';
        $data['menu'] = $this->renderPartial('account_menu.tpl', $data);

        return $this->render('@common/views/accountadmin/list.tpl', $data);
    }
    
    
}