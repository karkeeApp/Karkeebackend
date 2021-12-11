<?php
namespace cpanel\controllers\server;

use Yii;
use yii\web\Response;
use yii\bootstrap\ActiveForm;
use yii\filters\AccessControl;
use common\forms\AccountForm;
use common\forms\ClubSettingsForm;
use common\models\Account;
use common\models\User;

use common\helpers\Common;
use common\helpers\AccountHelper;

use common\lib\PaginationLib;

class AccountController extends \common\controllers\cpanel\server\AccountController
{
    public function behaviors()
    {
        return [
            'contentNegotiator' => [
                'class' => \yii\filters\ContentNegotiator::class,
                'only' => ['list', 'add', 'update', 'update-map-coordinates', 'updatepassword', 'approve', 'reject', 'delete', 'update-membership'],
                'formats' => [
                    'application/json' => Response::FORMAT_JSON
                ]
            ]
        ];
    }

    public function actionUpdateMembership()
    {
        $admin = Yii::$app->user->getIdentity();
        $action = Yii::$app->request->post('action');
        $account_id = Yii::$app->request->post('account_id');

        $account = Account::findOne($account_id);

        $form = Common::form("common\\forms\\ClubSettingsForm");

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
        }

        $user                     = $account->user;
        $user->carkee_member_type = $form->carkee_member_type;

        if ($user->isCarkeeVendor() AND empty($user->vendor_name)){
            $user->vendor_name = $user->company;
        }

        $user->save();

        return [
            'success' => TRUE,
            'message' => 'Successfully updated',
        ];
    }

    public function actionList()
    {
        $cpage = Yii::$app->request->post('page', 1);
        $keyword = Yii::$app->request->post('keyword', NULL);
        $status = Yii::$app->request->post('status', NULL);
        $filter = Yii::$app->request->post('filter', NULL);

        $qry = Account::find()
        ->innerJoin('user', 'user.user_id = account.user_id')
        ->where('1=1');

        if (isset($keyword)) {
            $qry->andFilterWhere([
                'or', 
                ['LIKE', 'user.company', $keyword],
                ['LIKE', 'user.email', $keyword],
            ]);
        }

        if (is_numeric($status)) {
            $qry->andWhere(['user.status' => $status]);
        }

        $total = $qry->count();
        $qry->orderBy = ['account.account_id' => SORT_DESC];

        $page      = new PaginationLib( $total, $cpage, 10);

        $page->url = '#list';
        if ($filter) $page->url .= '/filter/' . urlencode($filter);

        $data['accounts'] = $qry->limit($page->limit())->offset($page->offset())->all();
        $data['page'] = $page;

        return [
            'success' => TRUE,
            'content' => $this->renderPartial('/account/_ajax_list.tpl', $data),
        ];
    }

    public function actionUpdate()
    {
        $admin = Yii::$app->user->getIdentity();

        $form = Common::form("common\\forms\\AccountForm");

        $form->load(Yii::$app->request->post());
        
        $errors = [];

        if (!$form->validate()) {
             $errors['account-form'] = ActiveForm::validate($form);
        }

        if (!empty($errors)) {
            return [
                'success' => FALSE,
                'errorFields' => $errors,
            ];
        } else {
            /**
             * Save account
             */
            $account = Account::findOne($form->account_id);

            $message = "Successfully updated.";

            if (!$account) { 
                $account = new Account; 
                $message = "Successfully added.";
            }

            foreach($form->attributes as $key => $val) {
                if ($key == 'password') {
                    if (!empty($val)) $account->setPassword($val);
                }elseif (!in_array($key, ['account_id'])) {
                    $account->{$key} = $val;
                }
            }

            $account->save();

            Yii::$app->session->setFlash('success', $message);

            return [
                'success'    => TRUE,
                'message'    => $message,
                'account_id' => $account->account_id,
            ];
        }
    }

    public function actionApprove()
    {
        $account = Yii::$app->user->getIdentity();
        $account_id = Yii::$app->request->post('account_id');

        $account = Account::findOne($account_id);

        if (!$account){
            return [
                'success' => FALSE,
                'error'   => 'Account is not found.',
            ];
        }

        $user = $account->user;

        if ($user->isApproved()) {
            return [
                'success' => FALSE,
                'error'   => 'Account is already approved.',
            ];
        }elseif (!$user->isPending()) {
            return [
                'success' => FALSE,
                'error'   => 'Account is no longer pending.',
            ];
        }

        $user->status = User::STATUS_APPROVED;
        $user->save();

        return [
            'success' => TRUE,
            'message'   => 'Account is approved successfully.',
        ];
    }

    public function actionReject()
    {
        $account = Yii::$app->user->getIdentity();
        $account_id = Yii::$app->request->post('account_id');

        $account = Account::findOne($account_id);

        $user = $account->user;

        if (!$account){
            return [
                'success' => FALSE,
                'error'   => 'Account is not found.',
            ];
        }elseif (!$user->isPending()) {
            return [
                'success' => FALSE,
                'error'   => 'Account is no longer pending.',
            ];
        }

        $user->status = User::STATUS_REJECTED;
        $user->save();

        return [
            'success' => TRUE,
            'message'   => 'Account is rejected successfully.',
        ];
    }

    public function actionDelete()
    {
        $account = Yii::$app->user->getIdentity();
        $account_id = Yii::$app->request->post('account_id');

        $account = Account::findOne($account_id);

        $user = $account->user;

        if (!$account){
            return [
                'success' => FALSE,
                'error'   => 'Account is not found.',
            ];
        }

        $user->status = User::STATUS_DELETED;
        $user->save();

        return [
            'success' => TRUE,
            'message'   => 'Account is deleted successfully.',
        ];
    }
}