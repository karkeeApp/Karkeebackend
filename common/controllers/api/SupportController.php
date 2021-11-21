<?php
namespace common\controllers\api;

use common\forms\SupportForm;
use common\models\Support;
use Yii;
use yii\base\BaseObject;
use yii\widgets\ActiveForm;
use yii\web\UploadedFile;

use common\forms\InquireForm;
use common\helpers\Common;
use common\models\User;

class SupportController extends Controller
{
    public function actionInquire()
    {
    	$user = Yii::$app->user->getIdentity();
        // $user_id = $cuser->user_id;
        $form = new SupportForm(['scenario' => 'inquire']);
        $form = $this->postLoad($form);

        $errors = [];

        if (!$form->validate()) {
            $errors['support-form'] = ActiveForm::validate($form);
        }

        if (!empty($errors)) {
            return self::getFirstError(ActiveForm::validate($form));
        }

        // $user = Common::clubUser($user_id);
        // $user = User::find()->where(['user_id' => $cuser->user_id])->andWhere(['account_id' => $form->account_id])->one();

        if (!$user OR $user->account_id == 0){
            return [
                'success' => FALSE,
                'error'   => 'User is not found.',
            ];
        }
        /**
         * Send to admin
         */

        $transaction = Yii::$app->db->beginTransaction();

        try {

            $support = Support::create($form, $user);
            // $support->account_id = 9;
            $support->save();

            $transaction->commit();
            return [
                'code' => self::CODE_SUCCESS,
                'message' => 'Successfully added',
                'data' => $support->data(),
            ];

        } catch (\Exception $e) {
            $transaction->rollBack();

            return [
                'code' => self::CODE_ERROR,
                'message' => $e->getMessage(),
            ];
        }
    }
}