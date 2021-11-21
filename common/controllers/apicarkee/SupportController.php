<?php
namespace common\controllers\apicarkee;

use common\forms\SupportForm;
use common\models\Support;
use Yii;
use yii\widgets\ActiveForm;
use yii\web\UploadedFile;

use common\forms\InquireForm;

class SupportController extends Controller
{
    public function actionInquire()
    {
        $user = Yii::$app->user->getIdentity();

        $form = new SupportForm(['scenario' => 'inquire']);
        $form = $this->postLoad($form);

        $errors = [];

        if (!$form->validate()) {
            $errors['support-form'] = ActiveForm::validate($form);
        }

        if (!empty($errors)) {
            return self::getFirstError(ActiveForm::validate($form));
        }

        /**
         * Send to admin
         */
        $transaction = Yii::$app->db->beginTransaction();

        try {

            $support = Support::create($form, $user);
            // $support->account_id = 8;
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