<?php
namespace cpanel\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use common\models\User;

use common\forms\UserForm;
use common\forms\CreditLimitForm;
use common\forms\PasswordForm;
use common\forms\EmailForm;
use common\forms\MobileForm;

class AccountadminController extends \common\controllers\cpanel\AccountadminController
{
	public $menu;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index', 'add', 'edit', 'view', 'settings',],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }   
    
}