<?php
namespace common\controllers\cpanel\server;

use Yii;
use yii\widgets\ActiveForm;

use common\models\Account;
use common\models\AccountUser;
use common\models\User;

use common\forms\AccountForm;

use common\helpers\Common;
use common\helpers\Helper;
use common\helpers\MyActiveDataProvider;
use common\helpers\Sort;
use common\lib\PaginationLib;

class AccountadminController extends Controller
{
    public function actionUpdateRole()
    {
        $user = Yii::$app->user->identity;

        $form = Common::form("common\\forms\\AdminRoleForm");
        $form->load(Yii::$app->request->post());

        $errors = [];

        if (!$form->validate()) {
            $errors['admin-role-form'] = ActiveForm::validate($form);
        }

        if (!empty($errors)) {
            return  [
                'success' => FALSE,
                'errorFields' => $errors,
            ];
        }

        if (Common::isClub()) {
            $qry = Helper::findAdmin();
        } else {
            $qry = User::find()->where(['account_id' => $account_id]);
        }

        $user = $qry->andWhere(['user_id' => $form->user_id])->one();

        if (!$user) {
            return  [
                'success' => FALSE,
                'error' => 'Account user not found.',
            ];
        }

        $user->role = $form->role;
        $user->save();

        return  [
            'success' => TRUE,
            'message' => 'Successfully updated',
        ];
    }

    public function actionList()
    {
        // $cpage = Yii::$app->request->post('page', 1);
        $account_id = Yii::$app->request->post('account_id', 0);
        $status = Yii::$app->request->post('status', '');
        $keyword = Yii::$app->request->post('keyword', NULL);
        // $filter = Yii::$app->request->post('filter', NULL);

        $_GET['hashUrl'] = '#list/filter/';

        foreach(['status', 'keyword', 'sort', 'page'] as $key) {
            $_GET[$key] = Yii::$app->request->post($key);
        }

        if (empty($_GET['sort'])) $_GET['sort'] = '-id';

        $data['sort'] = new Sort([
            'attributes' => [
                'fullname' => [
                    'desc'    => ['fullname' => SORT_DESC],
                    'asc'     => ['fullname' => SORT_ASC],
                    'default' => SORT_DESC,
                    'label'   => 'Full Name',
                ],
                'email' => [
                    'desc'    => ['email' => SORT_DESC],
                    'asc'     => ['email' => SORT_ASC],
                    'default' => SORT_DESC,
                    'label'   => 'Email',
                ],
                'id' => [
                    'desc'    => ['user_id' => SORT_DESC],
                    'asc'     => ['user_id' => SORT_ASC],
                    'default' => SORT_DESC,
                    'label'   => 'ID',
                ],
            ],
            'defaultOrder' => ['id' => SORT_DESC],
        ]);

        $data['sort']->route = 'accountadmin#list/filter';

        if (Common::isClub()) {
            $qry = Helper::findAdmin();
        } else {
            $qry = User::find()->where(['account_id' => $account_id]);
        }

        if (isset($keyword)) {
            $qry->andFilterWhere([
                'or',
                ['LIKE', 'username', $keyword],
                ['LIKE', 'fullname', $keyword],
                ['LIKE', 'email', $keyword],
            ]);
        }

        $qry->orderBy($data['sort']->orders);

        $data['dataProvider'] = new MyActiveDataProvider([
            'query' => $qry,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return [
            'success' => TRUE,
            'content' => $this->renderPartial('@common/views/accountadmin/_ajax_list.tpl', $data),
        ];
    }

    public function actionDelete()
    {
        $account_id = Yii::$app->request->post('account_id');
        $user_id    = Yii::$app->request->post('id');

        if (Common::isClub()) {
            $user = Yii::$app->user->getIdentity();
            $account = $user->account;
        } else {
            $account = Account::findOne($account_id);
        }

        // if (Common::isClub()) {
        //     $data['user'] = Yii::$app->user->getIdentity();
        // } else {
        //     $data['user'] = AccountUser::findOne($user_id);
        // }

        if (!$account) {
            return  [
                'success' => FALSE,
                'error' => 'Account user not found.',
            ];
        }

        $user = User::findByAccountId($user_id, $account->account_id);

        if (!$user){
            return  [
                'success' => FALSE,
                'error' => 'User not found.',
            ];
        }

        $user->role = NULL;
        $user->save();

        return  [
            'success' => TRUE,
            'message' => 'Successfully deleted',
        ];
    }

    public function actionUpdatepassword()
    {
        $action = Yii::$app->request->post('action');
        $user_id = Yii::$app->request->post('user_id');

        if (Common::isClub()) {
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

        if (Common::isClub()) {
            $user = Yii::$app->user->getIdentity();
            $account = $user->account;
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
        $form->account_id = $account->account_id;

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