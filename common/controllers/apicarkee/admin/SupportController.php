<?php
namespace common\controllers\apicarkee\admin;

use common\forms\SupportForm;
use common\models\Support;
use Yii;
use yii\widgets\ActiveForm;
use yii\web\UploadedFile;
use common\forms\InquireForm;
use common\lib\Helper;

class SupportController extends Controller
{
    public function actionInquire()
    {
        $user = Yii::$app->user->getIdentity();

        $form = new SupportForm(['scenario' => 'inquire']);
        $form = $this->postLoad($form);

        if (!$form->validate()){
            $error = self::getFirstError(ActiveForm::validate($form));
            return Helper::errorMessage($error['message'], true);
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
            
            $error = $e->getMessage();
            return Helper::errorMessage($error,true);
        }
    }
}