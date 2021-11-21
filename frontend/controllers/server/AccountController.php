<?php
namespace frontend\controllers\server;

use Yii;
use yii\web\Controller;
use yii\bootstrap\ActiveForm;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\forms\UserForm;
use common\models\User;
use common\helpers\Common;
use common\lib\PaginationLib;

class AccountController extends \common\controllers\server\StaffController
{
    public $menu;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['attach', 'update', 'identitylist', 'history', 'notifications', 'saveeducation', 'educationlist'],
                        'allow' => true,
                    ],
                ],
            ],
        ];
    }

    public function actionUpdate()
    {
        $action = Yii::$app->request->post('action');
        $form = Common::form("common\\forms\\UserForm");
        $form->load(Yii::$app->request->post());

        $errors = [];

        $user = Yii::$app->user->getIdentity();


        if (!$user) {
            Common::json([
                'success' => FALSE,
                'error' => 'User not found.',
            ]);
            Yii::$app->end();
        }

        if (!$form->validate()) {
            $errors['user-form'] = ActiveForm::validate($form);
        }

        if (!empty($errors)) {
            Common::json([
                'success' => FALSE,
                'errorFields' => $errors,
            ]);
        } else {
            /**
             * Save user
             */
            if ($user->editProfile($form, $action)) {
                $json = [
                    'success' => TRUE,
                    'message' => 'Successfully updated.',
                ];
            } else {
                $json = [
                    'success' => FALSE,
                    'error' => 'Something went wrong. Please contact webmaster.',
                ];
            }

            Common::json($json);
        }
    }

}
