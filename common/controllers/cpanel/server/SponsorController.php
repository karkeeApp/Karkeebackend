<?php
/**
 * Developer: Abubakar Abdullahi
 * Date: 22/04/2021
 * Time: 4:22 PM
 */

namespace common\controllers\cpanel\server;

use Yii;

use yii\base\BaseObject;
use yii\web\View;
use yii\web\UploadedFile;

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

use common\forms\UserForm;
use common\forms\FileForm;
use common\forms\EducationForm;

use common\models\User;
use common\models\Item;

use common\helpers\Common;
use common\helpers\HRHelper;
use common\helpers\Helper;
use common\helpers\MyActiveDataProvider;
use common\helpers\Sort;
use common\lib\PaginationLib;
use common\models\Settings;
use common\models\UserFile;

class SponsorController extends Controller
{
    public function actionAddToAdmin()
    {
        /* Make sure user belongs to current account */
        $admin = Yii::$app->user->identity;

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

        $user = User::findOne($form->user_id);

        if (!$user OR $user->account_id != $admin->account_id) {
            return  [
                'success' => FALSE,
                'error' => 'Account user not found.',
            ];
        }

        $user->role = $form->role;
        $user->save();

        return [
            'success' => TRUE,
            'message' => 'Successfully added to admin',
            'redirect' => Url::home() . 'accountadmin',
        ];
    }

    public function actionList()
    {
        $account_id        = Yii::$app->request->post('account_id',NULL);
        $type              = Yii::$app->request->post('type',NULL);
        $keyword           = Yii::$app->request->post('keyword',NULL);
        $status            = Yii::$app->request->post('status',NULL);

        // $account_id = !empty($account_id) ? $account_id : NULL;
        // $type = !empty($type) ? $type : NULL;
        // $keyword = !empty($keyword) ? $keyword : NULL;
        // $status = !empty($status) ? $status : NULL;

        $_GET['hashUrl'] = 'member#list/filter/';

        foreach(['account_id','type','status', 'keyword', 'sort', 'page'] as $key) {
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
                'id' => [
                    'desc'    => ['user_id' => SORT_DESC],
                    'asc'     => ['user_id' => SORT_ASC],
                    'default' => SORT_DESC,
                    'label'   => 'ID',
                ],
                'email' => [
                    'desc'    => ['email' => SORT_DESC],
                    'asc'     => ['email' => SORT_ASC],
                    'default' => SORT_DESC,
                    'label'   => 'Email',
                ],
                'status' => [
                    'desc'    => ['status' => SORT_DESC],
                    'asc'     => ['status' => SORT_ASC],
                    'default' => SORT_DESC,
                    'label'   => 'Status',
                ],
            ],
            'defaultOrder' => ['id' => SORT_DESC],
        ]);

        $data['sort']->route = 'member#list/filter';

        if (Common::isClub()) {
            $qry = Common::findUser();
            $qry->andWhere(['member_type' => User::USER_TYPE_MEMBER]);
        } else {
            $qry = User::find()->where('1=1');

            if (is_numeric($account_id)) {
                /**
                 * Filter by club member
                 */
                $qry->andWhere(['account_id' => $account_id]);
            }

            // if (Common::isClub()) $qry->andWhere(['member_type' => User::USER_TYPE_MEMBER]);
            // else if ($type) $qry->andWhere(['member_type' => $type]);
            if (is_numeric($type)) $qry->andWhere(['member_type' => $type]);
        }

        if (isset($keyword)) {
            $qry->andFilterWhere([
                'or',
                ['LIKE', 'fullname', $keyword],
                ['LIKE', 'vendor_name', $keyword],
                ['LIKE', 'email', $keyword],
                ['LIKE', 'mobile', $keyword],
            ]);
        }

        // if (is_numeric($type)) $qry->andWhere([(Common::isClub() ? 'member_type' : 'carkee_member_type') => $type]);
        if (is_numeric($status)) $qry->andWhere(['status' => $status]);
        $qry->andWhere(['role' => User::ROLE_SPONSORSHIP]);
        $qry->orderBy($data['sort']->orders);

        Yii::$app->session['memberQry'] = $qry;

        $data['dataProvider'] = new MyActiveDataProvider([
            'query' => $qry,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        $data['type'] = $type;
        $data['account_id'] = $account_id;
        $tpl = (Common::isClub()) ? '_ajax_list.tpl' : '_ajax_list_carkee_member.tpl';

        return [
            'success' => TRUE,
            'content' => $this->renderPartial('@common/views/sponsor/' . $tpl, $data),
        ];
    }

    public function actionItemlist($id=0)
    {
        $cpage = Yii::$app->request->post('page', 1);
        $keyword = Yii::$app->request->post('keyword', NULL);
        $account_id = Yii::$app->request->post('account_id', NULL);
        $type = Yii::$app->request->post('type', NULL);
        $status = Yii::$app->request->post('status', NULL);
        // $column_orders_obj = Yii::$app->request->post('column_orders', NULL);
        $filter = Yii::$app->request->post('filter', NULL);

        $data['user']    = User::findOne($id);
        $data['account'] = Yii::$app->user->identity;

        extract($data);

        if (!$user OR $user->account_id != $account->account_id) {
            return [
                'success' => FALSE,
                'error' => 'User not found.'
            ];
        }

        $qry = Item::find()->where(['user_id' => $user->user_id]);

        if (isset($keyword)) {
            $qry->andFilterWhere([
                'or',
                ['LIKE', 'title', $keyword],
            ]);
        }

        if (is_numeric($status)) {
            $qry->andWhere(['status' => $status]);
        }
        // if(!empty($column_orders_obj)){
        //     $column_orders = json_decode($column_orders_obj);
        //     $col_orders = [];

        //     foreach($column_orders as $col_key => $col_value) $col_orders = [$col_key => ($col_value == 'desc'? SORT_DESC : SORT_ASC)];

        //     $qry->orderBy($col_orders);
        // }
        $total = $qry->count();

        $page = new PaginationLib( $total, $cpage, 10);
        $page->url = '#list';

        $data['items'] = $qry->limit($page->limit())->offset($page->offset())->all();
        $data['page'] = $page;
        $data['type'] = $type;
        $data['account_id'] = $account_id;
        return [
            'success' => TRUE,
            'content' => $this->renderPartial('@common/views/sponsor/_ajax_item_list.tpl', $data),
        ];
    }

    public function actionUpdate()
    {
        $action = Yii::$app->request->post('action');
        $user_id = Yii::$app->request->post('user_id');

        $form = Common::form("common\\forms\\UserForm");
        $form->load(Yii::$app->request->post());

        $errors = [];

        if (Common::isCpanel()) {
            $user = User::findOne($user_id);
        } else {
            $user = Yii::$app->user->getIdentity();
        }

        /**
         * Only owner of user is allowed to update
         */
        $account_id = Common::identifyAccountID();

        if (!$user) {
            return  [
                'success' => FALSE,
                'error' => 'User not found.',
            ];
        } elseif($user->account_id != $account_id) {
            return  [
                'success' => FALSE,
                'error' => 'Permission denied. User does not belong to you.',
            ];
        }

        if (!$form->validate()) {
            $errors['user-form'] = ActiveForm::validate($form);
        }

        if (!empty($errors)) {
            return  [
                'success' => FALSE,
                'errorFields' => $errors,
            ];
        } else {
            /**
             * Save user
             */
            $fields = ['fullname', 'nric', 'birthday', 'gender', 'profession', 'company', 'annual_salary', 'about', 'country', 'postal_code', 'add_1', 'add_2', 'unit_no', 'contact_person', 'emergency_no', 'emergency_code', 'relationship'];

            foreach($fields as $field){
                $user->{$field} = $form->{$field};
            }

            $user->save();

            return [
                'success' => TRUE,
                'message' => 'Successfully updated.',
            ];
        }
    }

    public function actionUpdatesettings()
    {
        $user_id = Yii::$app->request->post('user_id', 0);

        if (Common::isCpanel()) {
            $user = User::findOne($user_id);
        } elseif(Common::isClub()) {
            $user = Common::clubUser($user_id);
        } else {
            $user = Yii::$app->user->getIdentity();
        }

        if (!$user) {
            return [
                'success' => FALSE,
                'error' => 'User not found.',
            ];
            Yii::$app->end();
        }

        $form = Common::form("common\\forms\\UserSettingsForm");

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
        } else {
            if (Common::isCpanel()){
                $user->carkee_member_type = $form->carkee_member_type;
                $user->carkee_level       = $form->carkee_level;
            } else {
                $user->member_type = $form->member_type;
                $user->level       = $form->level;
            }

            if (($user->isVendor() OR $user->isCarkeeVendor()) AND empty($user->vendor_name)){
                /**
                 * Copy company or fullname
                 */
                $user->vendor_name = ($user->isClubOwner()) ? $user->company : $user->fullname;
            }

            $user->save();

            return  [
                'success' => TRUE,
                'message' => 'Successfully updated',
            ];
        }
    }

    public function actionUpdatepassword()
    {
        $user_id = Yii::$app->request->post('user_id', 0);

        if (Common::isCpanel()) {
            $user = User::findOne($user_id);
        } elseif(Common::isClub()) {
            $user = Common::clubUser($user_id);
        } else {
            $user = Yii::$app->user->getIdentity();
        }

        if (!$user) {
            return [
                'success' => FALSE,
                'error' => 'User not found.',
            ];
            Yii::$app->end();
        }

        $form = Common::form("common\\forms\\PasswordForm");

        $form->load(Yii::$app->request->post());

        $errors = [];

        if (!$form->validate()) {
            $errors['password-form'] = ActiveForm::validate($form);
        }

        if (!empty($errors)) {
            return [
                'success' => FALSE,
                'errorFields' => $errors,
            ];
        } else {
            $user->setPassword($form->new);
            $user->save();

            return [
                'success' => TRUE,
                'message' => 'Successfully updated',
            ];
        }
    }

    public function actionUpdateCoordinate()
    {
        $user_id = Yii::$app->request->post('user_id', 0);

        if (Common::isCpanel()) {
            $user = User::findOne($user_id);
        } elseif(Common::isClub()) {
            $user = Common::clubUser($user_id);
        } else {
            $user = Yii::$app->user->getIdentity();
        }

        if (!$user) {
            return [
                'success' => FALSE,
                'error' => 'User not found.',
            ];
        }

        $form = Common::form("common\\forms\\MapSettingsForm");

        $form->load(Yii::$app->request->post());

        $errors = [];

        if (!$form->validate()) {
            $errors['map-settings-form'] = ActiveForm::validate($form);
        }

        if (!empty($errors)) {
            return [
                'success' => FALSE,
                'errorFields' => $errors,
            ];
        } else {
            $user->longitude = $form->longitude;
            $user->latitude  = $form->latitude;
            $user->save();

            return [
                'success' => TRUE,
                'message' => 'Successfully updated',
            ];
        }
    }

    public function actionUpdateemail()
    {
        $user_id = Yii::$app->request->post('user_id', 0);

        if (Common::isCpanel()) {
            $user = User::findOne($user_id);
        } elseif(Common::isClub()) {
            $user = Common::clubUser($user_id);
        } else {
            $user = Yii::$app->user->getIdentity();
        }


        if (!$user) {
            return [
                'success' => FALSE,
                'error' => 'User not found.',
            ];
            Yii::$app->end();
        }

        $form = Common::form("common\\forms\\EmailForm");

        $form->load(Yii::$app->request->post());

        $errors = [];

        if (!$form->validate()) {
            $errors['email-form'] = ActiveForm::validate($form);
        }

        if (!empty($errors)) {
            return [
                'success' => FALSE,
                'errorFields' => $errors,
            ];
        } else {
            $user->email = $form->email;
            $user->save();

            return [
                'success' => TRUE,
                'message' => 'Successfully updated',
            ];
        }
    }

    public function actionUpdatemobile()
    {
        $user_id = Yii::$app->request->post('user_id', 0);

        if (Common::isCpanel()) {
            $user = User::findOne($user_id);
        } elseif(Common::isClub()) {
            $user = Common::clubUser($user_id);
        } else {
            $user = Yii::$app->user->getIdentity();
        }

        if (!$user) {
            return [
                'success' => FALSE,
                'error' => 'User not found.',
            ];
            Yii::$app->end();
        }

        $form = Common::form("common\\forms\\MobileForm");

        $form->load(Yii::$app->request->post());

        $errors = [];

        if (!$form->validate()) {
            $errors['mobile-form'] = ActiveForm::validate($form);
        }

        if (!empty($errors)) {
            return [
                'success' => FALSE,
                'errorFields' => $errors,
            ];
        } else {
            $user->mobile = $form->mobile;
            $user->username = $form->mobile;
            $user->save();

            return [
                'success' => TRUE,
                'message' => 'Successfully updated',
            ];
        }
    }

    public function actionAttach()
    {
        $user_id = Yii::$app->request->post('user_id', 0);

        if (Common::isCpanel()) {
            $user = User::findOne($user_id);
        } elseif (Common::isClub()) {
            $user = Common::clubUser($user_id);
        } else {
            $user = Yii::$app->user->getIdentity();
        }

        /**
         * Validate if staff belong to current account
         */
        if (!$user) {
            return [
                'success' => TRUE,
                'content' => 'User not found.',
            ];
            return;
        }

        $form = Common::form("common\\forms\\FileForm");
        $form->load(Yii::$app->request->post());

        $errors = [];

        if (!$form->validate()) {
            $errors['file-form'] = ActiveForm::validate($form);
        }

        if (!empty($errors)) {
            return [
                'success' => FALSE,
                'errorFields' => $errors,
            ];
        } else {

            //$uploadFile = UploadedFile::getInstanceByName('filename');
            $uploadFile = UploadedFile::getInstance($form, 'filename');

            if (!$uploadFile) {
                return [
                    'success' => FALSE,
                    'error' => 'Please attach a file.'
                ];
                Yii::$app->end();
            }

            $fileDestination = Yii::$app->params['dir_identity'] . $uploadFile->name;

            if ($uploadFile->saveAs($fileDestination)) {
                $file = new UserFile;
                $file->filename = $uploadFile->name;
                $file->type = $form->type;
                $file->user_id = $user_id;
                $file->account_id = $user->account_id;
                $file->decription = $form->decription;
                $file->save();

                return [
                    'success' => TRUE,
                    'message' => 'Successfully uploaded.'
                ];
            } else {
                return [
                    'success' => FALSE,
                    'error' => 'Error uploading the file.'
                ];
                Yii::$app->end();
            }
        }
    }

    public function actionAttachremove()
    {
        $file_id = Yii::$app->request->post('file_id', 0);

        $file = UserFile::findOne($file_id);

        if (!$file) {
            return [
                'success' => FALSE,
                'error' => 'File not found.'
            ];
            return;
        }

        if (Yii::$app->id == 'app-backend') {
            $account = Yii::$app->user->getIdentity();

            if ($file->account_id != $account->account_id) {
                return [
                    'success' => FALSE,
                    'error' => 'File not found.'
                ];
                return;
            }
        } elseif (Yii::$app->id == 'app-frontend') {
            $user = Yii::$app->user->getIdentity();

            if ($file->user_id != $user->user_id) {
                return [
                    'success' => FALSE,
                    'error' => 'File not found.'
                ];
                return;
            }
        }

        $file->delete();

        return [
            'success' => TRUE,
            'message' => 'Successfully removed.'
        ];
    }

    public function actionApprove()
    {
        $account = Yii::$app->user->getIdentity();
        $user_id = Yii::$app->request->post('user_id');

        $user = User::findOne($user_id);

        if (!$user OR $user->account_id != $account->account_id){
            return [
                'success' => FALSE,
                'error'   => 'User is not found.',
            ];
        }

        if ($user->isApproved()) {
            return [
                'success' => FALSE,
                'error'   => 'User is already approved.',
            ];
        }elseif (!$user->isPending()) {
            return [
                'success' => FALSE,
                'error'   => 'User is not in pending status.',
            ];
        } elseif(Yii::$app->params['environment'] == 'production' AND $user->confirmed_by AND $user->confirmed_by == $account->user_id){
            return [
                'success' => FALSE,
                'error'   => 'You already approved this. Please let someone be the checker.',
            ];
        }

        if(!$account->isAdministrator()){
            return [
                'success' => FALSE,
                'message'   => "Can't Update Sponsor Status! You don't have the required permission to apply changes.",
                'error'   => "Can't Update Sponsor Status! You don't have the required permission to apply changes."
            ];
        }

        $message = null;
        $settings = Settings::find()->one();

        if(($user->user_settings AND $user->user_settings->is_one_approval > 0) OR $account->is_one_approval OR $settings->is_one_approval){
        
            if (!$user->approved_by) {
                $user->approved_by = $account->user_id;    
                $user->confirmed_by = $account->user_id;
    
                $message = 'Successfully approved.';
            }
        }else{

            if (!$user->confirmed_by) {
                $user->confirmed_by = $account->user_id;
                $message = 'Successfully approved.';
            } elseif(!$user->approved_by) {
                $user->approved_by = $account->user_id;
                $message = 'Successfully confirmed.';
            }
        }

        if ($user->approved_by AND $user->confirmed_by){
            $user->status      = User::STATUS_APPROVED;
            $user->approved_at = date('Y-m-d H:i:s');
        }

        $user->save();

        if($message) 
        {
            return [
                'success' => TRUE,
                'message'   => $message
            ];
        }
        return [
            'success' => FALSE,
            'message'   => "Unable to Approve Sponsor's Details"
        ];
    }

    public function actionReject()
    {
        $account = Yii::$app->user->getIdentity();
        $user_id = Yii::$app->request->post('user_id');

        $user = User::findOne($user_id);

        if (!$user OR $user->account_id != $account->account_id){
            return [
                'success' => FALSE,
                'error'   => 'User is not found.',
            ];
        }

        if ($user->isIncomplete()) {
            return [
                'success' => FALSE,
                'error'   => 'User is already rejected.',
            ];
        }elseif (!$user->isPending()) {
            return [
                'success' => FALSE,
                'error'   => 'User is not in pending status.',
            ];
        }

        $user->status = User::STATUS_INCOMPLETE;
        $user->save();

        return [
            'success' => TRUE,
            'message'   => 'User was successfully rejected.',
        ];
    }

    public function actionAddSponsor()
    {
        $admin = Yii::$app->user->getIdentity();
        //$account = Yii::$app->user->identity;
        $account = $admin->account_id;

        $form = new UserForm(['scenario' => 'account_add_sponsor']);
        $form->load(Yii::$app->request->post());
        $form->account_id = $admin->account_id;

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
            $user              = new User;
            $user->member_type = User::TYPE_MEMBER;
            $user->account_id  = $admin->account_id;
            $user->step        = 7;

            $fields = [ 'role','email','vendor_name', 'vendor_description', 'country', 'postal_code', 'unit_no', 'add_1', 'telephone_code', 'telephone_no', 'status', 'mobile_code', 'mobile', 'about', 'founded_date'];

            foreach($fields as $field) {
                $user->{$field} = $form->{$field};
            }

            if (!empty($form->password)) {
                $user->setPassword($form->password);
            }
            $user->setPin('0000'); // default pin

            $user->save();

            return [
                'success' => TRUE,
                'message' => 'Successfully added.',
                'user_id' => $user->user_id,
            ];
        }
    }

    public function actionEditSponsor()
    {
        $admin = Yii::$app->user->getIdentity();
        $user_id = Yii::$app->request->post('user_id');
        $account = $admin->account;

        $user = User::findOne($user_id);

        if (!$user){
            return  [
                'success' => FALSE,
                'error' => 'User not found.',
            ];
        }

        $form = new UserForm(['scenario' => 'account_edit_sponsor']);
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
            $fields = ['role','email', 'vendor_name', 'vendor_description', 'country', 'postal_code', 'unit_no', 'add_1', 'telephone_code', 'telephone_no', 'status', 'mobile_code', 'mobile', 'about', 'founded_date'];

            foreach($fields as $field) {
                $user->{$field} = $form->{$field};
            }

            if (!empty($form->password)) {
                $user->setPassword($form->password);
            }

            $user->save();

            return [
                'success' => TRUE,
                'message' => 'Successfully updated.',
            ];
        }
    }

    public function actionDelete()
    {
        $account = Yii::$app->user->getIdentity();
        $user_id = Yii::$app->request->post('user_id');

        if (Common::isCpanel()) {
            $user = User::findOne($user_id);
        } elseif(Common::isClub()) {
            $user = Common::clubUser($user_id);
        } else {
            $user = Yii::$app->user->getIdentity();
        }

        if (!$user){
            return [
                'success' => FALSE,
                'error'   => 'User is not found.',
            ];
        }

        $user->status = User::STATUS_DELETED;
        $user->save();

        return [
            'success' => TRUE,
            'message'   => 'User was successfully deleted.',
        ];
    }


    /* Sponsor Level*/
    public function actionNormal() {
        $account = Yii::$app->user->getIdentity();
        $user_id = Yii::$app->request->post('user_id');

        if (Common::isCpanel()) {
            $user = User::findOne($user_id);
        } elseif(Common::isClub()) {
            $user = Common::clubUser($user_id);
        } else {
            $user = Yii::$app->user->getIdentity();
        }

        $user = User::findOne($user_id);

        if (!$user){ //  OR $user->account_id != $account->account_id){
            return [
                'success' => FALSE,
                'error'   => 'User is not found.',
            ];
        }

        $user->level = User::LEVEL_NORMAL;
        $user->save();

        return [
            'success' => TRUE,
            'message' => 'Successfully',
        ];
    }
    public function actionSilver() {
        $account = Yii::$app->user->getIdentity();
        $user_id = Yii::$app->request->post('user_id');

        if (Common::isCpanel()) {
            $user = User::findOne($user_id);
        } elseif(Common::isClub()) {
            $user = Common::clubUser($user_id);
        } else {
            $user = Yii::$app->user->getIdentity();
        }

        $user = User::findOne($user_id);

        if (!$user){ //  OR $user->account_id != $account->account_id){
            return [
                'success' => FALSE,
                'error'   => 'User is not found.',
            ];
        }

        $user->level = User::LEVEL_SILVER;
        $user->save();

        return [
            'success' => TRUE,
            'message' => 'Successfully',
        ];
    }
    public function actionGold() {
        $account = Yii::$app->user->getIdentity();
        $user_id = Yii::$app->request->post('user_id');

        if (Common::isCpanel()) {
            $user = User::findOne($user_id);
        } elseif(Common::isClub()) {
            $user = Common::clubUser($user_id);
        } else {
            $user = Yii::$app->user->getIdentity();
        }

        $user = User::findOne($user_id);

        if (!$user){ //  OR $user->account_id != $account->account_id){
            return [
                'success' => FALSE,
                'error'   => 'User is not found.',
            ];
        }

        $user->level = User::LEVEL_GOLD;
        $user->save();

        return [
            'success' => TRUE,
            'message' => 'Successfully',
        ];
    }
    public function actionPlatinum() {
        $account = Yii::$app->user->getIdentity();
        $user_id = Yii::$app->request->post('user_id');

        if (Common::isCpanel()) {
            $user = User::findOne($user_id);
        } elseif(Common::isClub()) {
            $user = Common::clubUser($user_id);
        } else {
            $user = Yii::$app->user->getIdentity();
        }

        $user = User::findOne($user_id);

        if (!$user){ //  OR $user->account_id != $account->account_id){
            return [
                'success' => FALSE,
                'error'   => 'User is not found.',
            ];
        }

        $user->level = User::LEVEL_PLATINUM;
        $user->save();

        return [
            'success' => TRUE,
            'message' => 'Successfully',
        ];
    }

    public function actionDiamond() {
        $account = Yii::$app->user->getIdentity();
        $user_id = Yii::$app->request->post('user_id');

        if (Common::isCpanel()) {
            $user = User::findOne($user_id);
        } elseif(Common::isClub()) {
            $user = Common::clubUser($user_id);
        } else {
            $user = Yii::$app->user->getIdentity();
        }

        $user = User::findOne($user_id);

        if (!$user){ //  OR $user->account_id != $account->account_id){
            return [
                'success' => FALSE,
                'error'   => 'User is not found.',
            ];
        }

        $user->level = User::LEVEL_DIAMOND;
        $user->save();

        return [
            'success' => TRUE,
            'message' => 'Successfully',
        ];
    }

    public function actionRemoveLevel() {
        $account = Yii::$app->user->getIdentity();
        $user_id = Yii::$app->request->post('user_id');

        if (Common::isCpanel()) {
            $user = User::findOne($user_id);
        } elseif(Common::isClub()) {
            $user = Common::clubUser($user_id);
        } else {
            $user = Yii::$app->user->getIdentity();
        }

        $user = User::findOne($user_id);

        if (!$user){ //  OR $user->account_id != $account->account_id){
            return [
                'success' => FALSE,
                'error'   => 'User is not found.',
            ];
        }

        $user->role = User::ROLE_USER;
        $user->save();

        return [
            'success' => TRUE,
            'message' => 'Successfully',
        ];
    }
}