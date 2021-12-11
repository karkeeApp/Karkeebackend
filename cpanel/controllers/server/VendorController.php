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

class VendorController extends \common\controllers\cpanel\server\VendorController
{
    public function behaviors()
    {   
        return [
            'contentNegotiator' => [
                'class' => \yii\filters\ContentNegotiator::class,
                'only' => [
                    'list', 'add-vendor', 'edit-vendor', 'update', 'loans', 'updatepassword', 'updateemail',
                    'updatemobile', 'updatesettings', 'approve', 'reject', 'itemlist', 'delete'
                ],
                'formats' => [
                    'application/json' => \yii\web\Response::FORMAT_JSON
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

//    public function actionList()
//    {
//        $cpage = Yii::$app->request->post('page', 1);
//        $keyword = Yii::$app->request->post('keyword', NULL);
//        $type = Yii::$app->request->post('type', NULL);
//        $status = Yii::$app->request->post('status', NULL);
//        $filter = Yii::$app->request->post('filter', NULL);
//
//        $qry = User::find()->where('1=1');
//
//        $qry->andWhere(['IN', 'carkee_member_type',  [User::TYPE_CLUB_OWNER_VENDOR, User::TYPE_CARKEE_MEMBER_VENDOR]]);
//
//        if (isset($keyword)) {
//            $qry->andFilterWhere([
//                'or',
//                ['LIKE', 'firstname', $keyword],
//                ['LIKE', 'lastname', $keyword],
//                ['LIKE', 'vendor_name', $keyword],
//                ['LIKE', 'email', $keyword],
//                ['LIKE', 'mobile', $keyword],
//            ]);
//        }
//
//        if (is_numeric($type)) {
//            $qry->andWhere(['member_type' => $type]);
//        }
//
//        if (is_numeric($status)) {
//            $qry->andWhere(['status' => $status]);
//        }
//
//        $total = $qry->count();
//        $qry->orderBy = ['user.user_id' => SORT_DESC];
//
//        $page = new PaginationLib( $total, $cpage, 10);
//
//        $page->url = '#list';
//        if ($filter) $page->url .= '/filter/' . urlencode($filter);
//
//        $data['users']      = $qry->limit($page->limit())->offset($page->offset())->all();
//        $data['page']       = $page;
//
//        return [
//            'success' => TRUE,
//            'content' => $this->renderPartial('/vendor/_ajax_list.tpl', $data),
//        ];
//    }
//
//    public function actionItemlist($id=0)
//    {
//        $cpage = Yii::$app->request->post('page', 1);
//        $keyword = Yii::$app->request->post('keyword', NULL);
//        $status = Yii::$app->request->post('status', NULL);
//        $filter = Yii::$app->request->post('filter', NULL);
//
//        $data['user']    = User::findOne($id);
//        $data['account'] = Yii::$app->user->identity;
//
//        extract($data);
//
//        if (!$user OR $user->account_id != $account->account_id) {
//            return [
//                'success' => FALSE,
//                'error' => 'User not found.'
//            ];
//        }
//
//        $qry = Item::find()->where(['user_id' => $user->user_id]);
//
//        if (isset($keyword)) {
//            $qry->andFilterWhere([
//                'or',
//                ['LIKE', 'title', $keyword],
//            ]);
//        }
//
//        if (is_numeric($status)) {
//            $qry->andWhere(['status' => $status]);
//        }
//
//        $total = $qry->count();
//
//        $page = new PaginationLib( $total, $cpage, 10);
//        $page->url = '#list';
//
//        $data['items'] = $qry->limit($page->limit())->offset($page->offset())->all();
//        $data['page'] = $page;
//
//        return [
//            'success' => TRUE,
//            'content' => $this->renderPartial('@common/views/member/_ajax_item_list.tpl', $data),
//        ];
//    }
//
//    public function actionUpdate()
//    {
//        $action = Yii::$app->request->post('action');
//        $user_id = Yii::$app->request->post('user_id');
//
//        $form = Common::form("common\\forms\\UserForm");
//        $form->load(Yii::$app->request->post());
//
//        $errors = [];
//
//        $user = User::findOne($user_id);
//
//        if (!$user) {
//            return  [
//                'success' => FALSE,
//                'error' => 'User not found.',
//            ];
//        }
//
//        if (!$form->validate()) {
//            $errors['user-form'] = ActiveForm::validate($form);
//        }
//
//        if (!empty($errors)) {
//            return  [
//                'success' => FALSE,
//                'errorFields' => $errors,
//            ];
//        } else {
//            $fields = ['email', 'vendor_name', 'vendor_description', 'country', 'postal_code', 'unit_no', 'add_1', 'telephone_code', 'telephone_no', 'status', 'mobile_code', 'mobile', 'about', 'founded_date'];
//
//            foreach($fields as $field) {
//                $user->{$field} = $form->{$field};
//            }
//
//            if (!empty($form->password)) {
//                $user->setPassword($form->password);
//            }
//
//            $user->save();
//
//            return [
//                'success' => TRUE,
//                'message' => 'Successfully updated.',
//            ];
//        }
//    }
//
//    public function actionUpdatesettings()
//    {
//        $user_id = Yii::$app->request->post('user_id', 0);
//
//        if (Common::isCpanel()) {
//            $user = User::findOne($user_id);
//        } elseif(Common::isHR()) {
//            $user = HRHelper::staff($user_id, FALSE);
//        } else {
//            $user = Yii::$app->user->getIdentity();
//        }
//
//        if (!$user) {
//            return [
//                'success' => FALSE,
//                'error' => 'User not found.',
//            ];
//            Yii::$app->end();
//        }
//
//        $form = Common::form("common\\forms\\UserSettingsForm");
//
//        $form->load(Yii::$app->request->post());
//
//        $errors = [];
//
//        if (!$form->validate()) {
//            $errors['settings-form'] = ActiveForm::validate($form);
//        }
//
//        if (!empty($errors)) {
//            return [
//                'success' => FALSE,
//                'errorFields' => $errors,
//            ];
//        } else {
//            $user->member_type = $form->member_type;
//            $user->save();
//
//            return  [
//                'success' => TRUE,
//                'message' => 'Successfully updated',
//            ];
//        }
//    }
//
//    public function actionUpdatepassword()
//    {
//        $user_id = Yii::$app->request->post('user_id', 0);
//
//        if (Common::isCpanel()) {
//            $user = User::findOne($user_id);
//        } elseif(Common::isHR()) {
//            $user = HRHelper::staff($user_id, FALSE);
//        } else {
//            $user = Yii::$app->user->getIdentity();
//        }
//
//        if (!$user) {
//            return [
//                'success' => FALSE,
//                'error' => 'User not found.',
//            ];
//            Yii::$app->end();
//        }
//
//        $form = Common::form("common\\forms\\PasswordForm");
//
//        $form->load(Yii::$app->request->post());
//
//        $errors = [];
//
//        if (!$form->validate()) {
//             $errors['password-form'] = ActiveForm::validate($form);
//        }
//
//        if (!empty($errors)) {
//            return [
//                'success' => FALSE,
//                'errorFields' => $errors,
//            ];
//        } else {
//            $user->setPassword($form->new);
//            $user->save();
//
//            return [
//                'success' => TRUE,
//                'message' => 'Successfully updated',
//            ];
//        }
//    }
//
//    public function actionUpdateemail()
//    {
//        $user_id = Yii::$app->request->post('user_id', 0);
//
//        if (Common::isCpanel()) {
//            $user = User::findOne($user_id);
//        } elseif(Common::isHR()) {
//            $user = HRHelper::staff($user_id, FALSE);
//        } else {
//            $user = Yii::$app->user->getIdentity();
//        }
//
//
//        if (!$user) {
//            return [
//                'success' => FALSE,
//                'error' => 'User not found.',
//            ];
//            Yii::$app->end();
//        }
//
//        $form = Common::form("common\\forms\\EmailForm");
//
//        $form->load(Yii::$app->request->post());
//
//        $errors = [];
//
//        if (!$form->validate()) {
//             $errors['email-form'] = ActiveForm::validate($form);
//        }
//
//        if (!empty($errors)) {
//            return [
//                'success' => FALSE,
//                'errorFields' => $errors,
//            ];
//        } else {
//            $user->email = $form->email;
//            $user->save();
//
//            return [
//                'success' => TRUE,
//                'message' => 'Successfully updated',
//            ];
//        }
//    }
//
//    public function actionUpdatemobile()
//    {
//        $user_id = Yii::$app->request->post('user_id', 0);
//
//        if (Common::isCpanel()) {
//            $user = User::findOne($user_id);
//        } elseif(Common::isHR()) {
//            $user = HRHelper::staff($user_id, FALSE);
//        } else {
//            $user = Yii::$app->user->getIdentity();
//        }
//
//        if (!$user) {
//            return [
//                'success' => FALSE,
//                'error' => 'User not found.',
//            ];
//            Yii::$app->end();
//        }
//
//        $form = Common::form("common\\forms\\MobileForm");
//
//        $form->load(Yii::$app->request->post());
//
//        $errors = [];
//
//        if (!$form->validate()) {
//             $errors['mobile-form'] = ActiveForm::validate($form);
//        }
//
//        if (!empty($errors)) {
//            return [
//                'success' => FALSE,
//                'errorFields' => $errors,
//            ];
//        } else {
//            $user->mobile = $form->mobile;
//            $user->username = $form->mobile;
//            $user->save();
//
//            return [
//                'success' => TRUE,
//                'message' => 'Successfully updated',
//            ];
//        }
//    }
//
//        public function actionApprove()
//    {
//        $account = Yii::$app->user->getIdentity();
//        $user_id = Yii::$app->request->post('user_id');
//
//        $user = User::findOne($user_id);
//
//        if (!$user OR $user->account_id != $account->account_id){
//            return [
//                'success' => FALSE,
//                'error'   => 'User is not found.',
//            ];
//        }
//
//        if ($user->isApproved()) {
//            return [
//                'success' => FALSE,
//                'error'   => 'User is already approved.',
//            ];
//        }elseif (!$user->isPending()) {
//            return [
//                'success' => FALSE,
//                'error'   => 'User is not in pending status.',
//            ];
//        }
//
//        $user->status = User::STATUS_APPROVED;
//        $user->save();
//
//        return [
//            'success' => TRUE,
//            'message'   => 'User was successfully approved.',
//        ];
//    }
//
//    public function actionReject()
//    {
//        $account = Yii::$app->user->getIdentity();
//        $user_id = Yii::$app->request->post('user_id');
//
//        $user = User::findOne($user_id);
//
//        if (!$user OR $user->account_id != $account->account_id){
//            return [
//                'success' => FALSE,
//                'error'   => 'User is not found.',
//            ];
//        }
//
//        if ($user->isIncomplete()) {
//            return [
//                'success' => FALSE,
//                'error'   => 'User is already rejected.',
//            ];
//        }elseif (!$user->isPending()) {
//            return [
//                'success' => FALSE,
//                'error'   => 'User is not in pending status.',
//            ];
//        }
//
//        $user->status = User::STATUS_INCOMPLETE;
//        $user->save();
//
//        return [
//            'success' => TRUE,
//            'message'   => 'User was successfully rejected.',
//        ];
//    }
//
//    public function actionAdd()
//    {
//        $admin = Yii::$app->user->getIdentity();
//
//        $form = Common::form("common\\forms\\UserForm");
//        $form->load(Yii::$app->request->post());
//        $form->account_id = $admin->account_id;
//
//        $errors = [];
//
//        if (!$form->validate()) {
//             $errors['user-form'] = ActiveForm::validate($form);
//        }
//
//        if (!empty($errors)) {
//            return [
//                'success' => FALSE,
//                'errorFields' => $errors,
//            ];
//        } else {
//            /**
//             * Save user
//             */
//            $user              = new User;
//            $user->member_type = User::TYPE_CARKEE_VENDOR;
//            $user->status      = User::STATUS_APPROVED;
//            $user->account_id  = $admin->account_id;
//            $user->step        = 7;
//
//            $fields = ['email', 'vendor_name', 'vendor_description', 'country', 'postal_code', 'unit_no', 'add_1', 'telephone_code', 'telephone_no', 'status'];
//
//            foreach($fields as $field) {
//                $user->{$field} = $form->{$field};
//            }
//
//            $user->setPassword($form->password);
//            $user->setPin('0000'); // default pin
//
//            $user->save();
//
//            return [
//                'success' => TRUE,
//                'message' => 'Successfully added.',
//                'user_id' => $user->user_id,
//            ];
//        }
//    }
//
//    public function actionDelete()
//    {
//        $account = Yii::$app->user->getIdentity();
//        $user_id = Yii::$app->request->post('user_id');
//
//        $user = User::findOne($user_id);
//
//        if (!$user){
//            return [
//                'success' => FALSE,
//                'error'   => 'User is not found.',
//            ];
//        }
//
//        $user->status = User::STATUS_DELETED;
//        $user->save();
//
//        return [
//            'success' => TRUE,
//            'message'   => 'User was successfully deleted.',
//        ];
//    }
}
