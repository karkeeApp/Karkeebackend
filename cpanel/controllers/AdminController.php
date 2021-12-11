<?php
namespace cpanel\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use common\forms\AccountForm;
use common\forms\AccountUserSettingsForm;
use common\forms\AccountUserPasswordForm;
use cpanel\forms\AdminForm;

use common\models\Admin;

class AdminController extends \common\controllers\cpanel\Controller
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
                        'actions' => ['index', 'add', 'edit', 'view', 'settings'],
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
        $data['admin'] = Yii::$app->user->identity;

        return $this->render('list.tpl', $data);
    }

    public function actionAdd()
    {
        $data['menu'] = $this->menu;

        $data['adminForm'] = new AdminForm;

        return $this->render('form.tpl', $data);
    }

    public function actionView($id)
    {
        $data['admin'] = Admin::findOne($id);

        if (!$data['admin']) {
            throw new \yii\web\HttpException(404, 'Admin not found.');
        }

        $data['subTitle'] = ' - Admin Details';
        $data['menu'] = $this->renderPartial('admin_menu.tpl', $data);

        $data['adminForm'] = new AdminForm;
        $data['adminForm']->setAttributes($data['admin']->attributes, FALSE);

        return $this->render('view.tpl', $data);
    }

    public function actionEdit($id=0)
    {
        $data['admin'] = Admin::findOne($id);

        if (!$data['admin']) {
            throw new \yii\web\HttpException(404, 'Admin not found.');
        }

        $data['subTitle'] = ' - Edit Admin';
        $data['menu'] = $this->renderPartial('admin_menu.tpl', $data);

        $data['adminForm'] = new AdminForm;
        $data['adminForm']->setAttributes($data['admin']->attributes, FALSE);

        return $this->render('form.tpl', $data);
    }
}