<?php
namespace common\controllers\cpanel\server;

use common\models\HRUser;
use Yii;
use yii\widgets\ActiveForm;
use common\models\Account;

use common\forms\AccountForm;

use common\helpers\Common;

class AccountController extends Controller
{
	public function actionUpdateMapCoordinates()
    {
        $action = Yii::$app->request->post('action');
        $account_id = Yii::$app->request->post('account_id');

        $form = Common::form("common\\forms\\MapSettingsForm");
        $form->load(Yii::$app->request->post());
        
        $account = Account::findOne($account_id);

        if (!$account) {
            return [
                'success' => FALSE,
                'error' => 'Account not found.',
            ];
        }

        $errors = [];

        if (!$form->validate()) {
             $errors['mapsettings-form'] = ActiveForm::validate($form);
        }

        if (!empty($errors)) {
            return [
                'success' => FALSE,
                'errorFields' => $errors,
            ];
        } else {
            $user            = $account->user;
            $user->longitude = $form->longitude;
            $user->latitude  = $form->latitude;
            $user->save();


            return [
                'success' => TRUE,
                'message' => 'Successfully updated',
            ];
        } 
    }
}