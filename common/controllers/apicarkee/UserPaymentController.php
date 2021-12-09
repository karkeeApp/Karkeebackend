<?php
namespace common\controllers\apicarkee;

use common\forms\AdsForm;
use common\forms\AdsRemoveAttachmentForm;
use Yii;
use common\models\Email;

use common\models\Account;
use common\models\BannerManagement;
use common\models\BannerImage;

use common\forms\BannerManagementForm;
use common\forms\BannerImageForm;
use common\forms\UserPaymentAttachmentForm;
use common\forms\UserPaymentForm;
use common\lib\CrudAction;
use common\lib\Helper;
use common\models\Ads;
use common\models\User;
use common\models\UserPayment;
use common\models\UserPaymentAttachment;
use yii\bootstrap\ActiveForm;
use yii\web\UploadedFile;

class UserPaymentController extends Controller
{
    public function actionList()
    {
        $user = Yii::$app->user->identity;
        $payments = UserPayment::find()
                    ->where(['account_id' => 0])
                    ->andWhere(['status' => Ads::STATUS_ACTIVE])
                    ->all();

        if (!$payments){
            return [
                'code'    => self::CODE_ERROR,   
                'message' => 'No Payment found.',
            ];
        }

        $data = [];

        foreach($payments as $payment){
            $data[] = $payment->data($user);
        }

        return [
            'data'        => $data,
            'code'        => self::CODE_SUCCESS,
        ];
    }

    public function actionRemoveAds()
    {
        $user = Yii::$app->user->identity;
        // $id   = Yii::$app->request->post('ads_id');

        // $ads = Ads::findOne($id);

        // if (!$ads){
        //     return [
        //         'code'    => self::CODE_ERROR,   
        //         'message' => 'Ads not found.',
        //     ];
        // }

        $tmp = [];

        foreach($_FILES as $file) {
            $tmp['UserPaymentForm'] = [
                'name'     => ['file' => $file['name']],
                'type'     => ['file' => $file['type']],
                'tmp_name' => ['file' => $file['tmp_name']],
                'error'    => ['file' => $file['error']],
                'size'     => ['file' => $file['size']],
            ];
        }

        $_FILES = $tmp;

        // Logging file upload
        // Yii::info($_FILES,'carkee');
        // -------------------
        
        $form = new UserPaymentForm(['scenario' => 'remove-ads']);
        $form = $this->postLoad($form);

        $uploadFile = UploadedFile::getInstance($form, 'file');
        $form->file = $uploadFile;

        if (!$form->validate()) {
            $errors = ActiveForm::validate($form);
        }

        if (!empty($errors)) {
            return self::getFirstError(ActiveForm::validate($form));
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            if ($uploadFile) {
                $filename = date('Ymd') . '_' . time() . "_{$user->user_id}" . '.' . $uploadFile->getExtension();
                
                $fileDestination = Yii::$app->params['dir_payment'] . $filename;

                if (!$uploadFile->saveAs($fileDestination)) {
                    return [
                        'code'    => self::CODE_ERROR,
                        'message' => 'Error uploading the file'
                    ];
                }
                $form->filename = $filename;
                
                if($user->premium_status = User::PREMIUM_STATUS_FREE){
                    $user->premium_status = User::PREMIUM_STATUS_PENDING;
                    $user->save();
                }
                $form->payment_for = UserPayment::PAYMENT_FOR_ADS;
                $form->name = $user->fullname . " ads removal";
                $payment = UserPayment::create($form,$user->user_id);
                
                $transaction->commit();

                Email::sendEmailNotification(Yii::$app->params['admin.email'], 'KARKEE Event', 'admin-notification', User::adminEmails(), User::subAdminEmails(), User::superAdminEmails(), $params=[
                    'name'       => !empty($user->fullname) ? $user->fullname : $user->firstname,
                    'heading'      => 'The following user has made a payment for ads removal',
                    'email'         => $user->email,
                    'client_email'  => $user->email,
                    'club_email'    => "admin@carkee.sg",
                    'club_name'     => "KARKEE",
                    'club_link'     => "http://cpanel.carkee.sg",
                    'club_logo'     => "http://qa.carkeeapi.carkee.sg/logo-edited.png",
                    'api_link'      => (Yii::$app->params['environment'] == 'production' ? Yii::$app->params['api.carkee.endpoint']['prod'] : Yii::$app->params['api.carkee.endpoint']['dev'])
                ]);

                return [
                    'code'    => self::CODE_SUCCESS,
                    'message' => 'We will check and approved your request very soon',
                    'data'    => $user->data()
                    // 'attachment'    => $ads_rem->filelink(),
                ];
            }
        } catch (\Exception $e) {
            $transaction->rollBack();

            return [
                'code'    => self::CODE_ERROR,   
                'message' => $e->getMessage(),
            ];
        }

        return [
            'code'    => self::CODE_ERROR,
            'message' => 'Invalid Details'
        ];
    }
}
