<?php
namespace cpanel\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use common\forms\MFISettingsForm;
use common\forms\MFIPasswordForm;

use common\models\Settings;

class SettingsController extends \common\controllers\cpanel\Controller
{
    public $menu;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        $this->menu = $this->renderPartial('menu.tpl');

        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $data['menu'] = $this->menu;

        $data['settings'] = Settings::get();
        
        $data['mfiSettingsForm'] = new MFISettingsForm;
        $data['mfiSettingsForm']->setAttributes($data['settings']->attributes, FALSE);

        $data['mfiPasswordForm'] = new MFIPasswordForm;

        return $this->render('settings.tpl', $data);
    }

    
}
