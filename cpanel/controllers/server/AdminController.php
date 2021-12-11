<?php
namespace cpanel\controllers\server;

use Yii;
use yii\web\Response;
use yii\bootstrap\ActiveForm;
use yii\filters\AccessControl;
// use common\forms\AccountForm;
use common\models\Admin;

use common\helpers\Common;
use common\helpers\AccountHelper;

use common\lib\PaginationLib;

class AdminController extends \common\controllers\cpanel\server\Controller
{
    public function behaviors()
    {
        return [
            'contentNegotiator' => [
                'class' => \yii\filters\ContentNegotiator::class,
                'only' => ['list', 'add', 'update', 'updatepassword'],
                'formats' => [
                    'application/json' => Response::FORMAT_JSON
                ]
            ]
        ];
    }

    public function actionList()
    {
        $cpage = Yii::$app->request->post('page', 1);
        $status = Yii::$app->request->post('status', '');
        $keyword = Yii::$app->request->post('keyword', NULL);
        $filter = Yii::$app->request->post('filter', NULL);

        $qry = Admin::find()->where('1=1');

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

        $data['admins'] = $qry->limit($page->limit())->offset($page->offset())->all();
        $data['page'] = $page;

        return [
            'success' => TRUE,
            'content' => $this->renderPartial('/admin/_ajax_list.tpl', $data),
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

        $form = Common::form("cpanel\\forms\\AdminForm");
        $form->load(Yii::$app->request->post());

        $errors = [];

        if (!$form->validate()) {
             $errors['admin-form'] = ActiveForm::validate($form);
        }

        if (!empty($errors)) {
            return [
                'success' => FALSE,
                'errorFields' => $errors,
            ];
        } else {
            $admin = Admin::create($form);

            $message = 'Successfully ' . (($form->admin_id) ? 'updated' : 'added') .  '.';
            
            Yii::$app->session->setFlash('success', $message);

            return [
                'admin_id'   => $admin->admin_id,
                'success' => TRUE,
                'message' => $message,
            ];
        }
    }
}