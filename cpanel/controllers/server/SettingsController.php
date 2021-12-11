<?php
namespace cpanel\controllers\server;

use Yii;
use yii\web\Controller;
use yii\bootstrap\ActiveForm;
use yii\filters\AccessControl;

use common\models\Settings;

use common\helpers\Common;

class SettingsController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['updatepassword', 'updatesettings',],
                        'allow' => true,
                    ],
                ],
            ],
        ];
    }

    public function actionUpdatesettings()
    {
    	$form = Common::form("common\\forms\\MFISettingsForm");
        $form->load(Yii::$app->request->post());
        
        $errors = [];

        if (!$form->validate()) {
            $errors['settings-form'] = ActiveForm::validate($form);
        }

        if (!empty($errors)) {
            return [
                'success' => FALSE,
                'errorFields' => $errors,
            ];
        } else {
            /**
             * Save settings
             */
            $setting = Settings::get();

            foreach($form->attributes as $key => $val) {
                if (!in_array($key, ['setting_id'])) {
                    $setting->{$key} = $val;
                }
            }
            
            $setting->save();
             
            return [
                'success' => TRUE,
                'message' => 'Successfully updated.',
            ];
        }
    }

    public function actionUpdatepassword()
    {
    	$admin = Yii::$app->user->getIdentity();

    	$form = Common::form("common\\forms\\MFIPasswordForm");
        $form->load(Yii::$app->request->post());

    	$errors = [];

        if (!$form->validate()) {
            $errors['password-form'] = ActiveForm::validate($form);
        }

        if (!empty($errors)) {
            return [
                'success' => FALSE,
                'errorFields' => $errors,
            ];
        } else {
        	
        	$admin->setPassword($form->new);

        	$admin->save();

        	return [
                'success' => TRUE,
                'message' => 'Successfully updated.',
            ];

        }
    }

}