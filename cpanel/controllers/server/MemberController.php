<?php
namespace cpanel\controllers\server;

use Yii;
use yii\web\Controller;
use yii\bootstrap\ActiveForm;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use common\forms\UserForm;

use common\models\User;
use common\models\Loan;
use common\helpers\Common;
use common\helpers\HRHelper;
use common\lib\PaginationLib;

class MemberController extends \common\controllers\cpanel\server\MemberController
{
    public function behaviors()
    {   
        return [
            'contentNegotiator' => [
                'class' => \yii\filters\ContentNegotiator::class,
                'only' => [
                    'list', 'add-vendor', 'update', 'loans', 'updatepassword', 'updateemail', 
                    'updatemobile', 'updatesettings', 'approve', 'reject', 'itemlist', 'delete', 
                    'renewal-list', 'renewal-approve', 'renewal-reject','pendingapproval',
                    'update-coordinate','sponsor','restore', 'set-expiry'
                ],
                'formats' => [
                    'application/json' => yii\web\Response::FORMAT_JSON
                ]
            ],
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => parent::userActions(),
                        'allow' => true,
                    ],
                ],
            ],
        ];       
    }

    public function actionAdd()
    {
        $account = Yii::$app->user->getIdentity();

        $form = Common::form("common\\forms\\UserForm");
        $form->load(Yii::$app->request->post());
        
        $errors = [];

        if (!$form->validate()) {
             $errors['user-form'] = ActiveForm::validate($form);
        }

        if (!empty($errors)) {
            return [
                'success' => FALSE,
                'errorFields' => $errors,
            ];
        } else {
            /**
             * Save user
             */
            $user = HRHelper::findStaff()->andWhere(['user_id' => $form->user_id])->one();
             
            if (!$user) $user = new User;

            foreach($form->attributes as $key => $val) {
                if ($key == 'password') {
                    if (!empty($val)) $user->setPassword($val);
                }elseif (!in_array($key, ['user_id'])) {
                    $user->{$key} = $val;
                }
            }
            
            if (!$form->user_id) {
                $user->generateAuthKey();
                $user->generatePasswordResetToken();
                $user->account_id = $account->account_id;
            }

            $user->save();
             
            return [
                'success' => TRUE,
                'message' => 'Successfully added.',
                'user_id' => $user->user_id,
            ];
        }
    }
}
