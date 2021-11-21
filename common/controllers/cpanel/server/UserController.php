<?php
namespace common\controllers\cpanel\server;

use Yii;
use yii\widgets\ActiveForm;

use common\models\Account;
use common\models\AccountUser;

use common\forms\AccountForm;

use common\helpers\Common;
use common\helpers\HRHelper;

use common\lib\PaginationLib;

class UserController extends Controller
{
    public function actionList()
    {
        $cpage = Yii::$app->request->post('page', 1);
        $account_id = Yii::$app->request->post('account_id', 0);
        $status = Yii::$app->request->post('status', '');
        $keyword = Yii::$app->request->post('keyword', NULL);
        $filter = Yii::$app->request->post('filter', NULL);

        if (Common::isHR()) {
            $qry = HRHelper::findHr();
        } else {
            $qry = AccountUser::find()->where(['account_id' => $account_id]);
        }

        if (isset($keyword)) {
            $qry->andFilterWhere([
                'or', 
                ['LIKE', 'username', $keyword],
                ['LIKE', 'email', $keyword],
            ]);
        }

        $total = $qry->count();

        $page = new PaginationLib( $total, $cpage, 10);

        $page->url = '#list';
        if ($filter) $page->url .= '/filter/' . urlencode($filter);

        $data['users'] = $qry->limit($page->limit())->offset($page->offset())->all();
        $data['page'] = $page;

        return [
            'success' => TRUE,
            'content' => $this->renderPartial('@common/views/user/_ajax_list.tpl', $data),
        ];
    }

    public function actionUpdatepassword()
    {
        $action = Yii::$app->request->post('action');
        $user_id = Yii::$app->request->post('user_id');

        if (Common::isHR()) {
            $data['user'] = Yii::$app->user->getIdentity();
        } else {
            $data['user'] = AccountUser::findOne($user_id);
        }

        if (!$data['user']) {
            return  [
                'success' => FALSE,
                'error' => 'Account user not found.',
            ];
        }

        $form = Common::form("common\\forms\\AccountUserPasswordForm");
        $form->load(Yii::$app->request->post());

        $errors = [];

        if (!$form->validate()) {
             $errors['password-form'] = ActiveForm::validate($form);
        }

        if (!empty($errors)) {
            return  [
                'success' => FALSE,
                'errorFields' => $errors,
            ];
        } else {
            $data['user']->setPassword($form->new);
            $data['user']->save();

            return  [
                'success' => TRUE,
                'message' => 'Successfully updated',
            ];
        }
    }

    public function actionUpdate()
    {
        $action = Yii::$app->request->post('action');
        $account_id = Yii::$app->request->post('account_id');

        if (Common::isHR()) {
            $user = Yii::$app->user->getIdentity();
            $account = $hr->account;
        } else {
            $account = Account::findOne($account_id);
        }

        if (!$account) {
            return  [
                'success' => FALSE,
                'error' => 'Account not found.',
            ];
        }

        $form = Common::form("common\\forms\\AccountUserForm");
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
            $user = AccountUser::create($form, $account);

            $message = 'Successfully ' . (($form->user_id) ? 'updated' : 'added') .  '.';
            
            Yii::$app->session->setFlash('success', $message);

            return [
                'user_id'   => $user->user_id,
                'success' => TRUE,
                'message' => $message,
            ];
        }
    }
}