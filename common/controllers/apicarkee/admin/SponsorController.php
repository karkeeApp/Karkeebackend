<?php
/**
 * Developer: Abubakar Abdullahi
 * Date: 22/04/2021
 * Time: 4:22 PM
 */

namespace common\controllers\apicarkee\admin;

use common\forms\AdminRoleForm;
use Yii;

use yii\base\BaseObject;
use yii\web\View;
use yii\web\UploadedFile;

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

use common\forms\UserForm;
use common\forms\FileForm;
use common\forms\EducationForm;
use common\forms\EmailForm;
use common\forms\MapSettingsForm;
use common\forms\MobileForm;
use common\forms\PasswordForm;
use common\forms\UserSettingsForm;
use common\models\User;
use common\models\Item;

use common\helpers\Common;
use common\helpers\HRHelper;
use common\helpers\Helper;
use common\helpers\MyActiveDataProvider;
use common\helpers\Sort;
use common\lib\Helper as LibHelper;
use common\lib\PaginationLib;
use common\models\Settings;
use common\models\UserDirector;
use common\models\UserFcmToken;
use common\models\UserFile;
use common\models\UserSocialMedia;
use Google_Client;
use Google_Service_Oauth2;
use Google_Service_Plus;
use yii\data\Pagination;

class SponsorController extends Controller
{
    private function sponsorList()
    {
        $admin             = Yii::$app->user->identity;
        $account_id        = Yii::$app->request->get('account_id',NULL);
        $member_type       = Yii::$app->request->get('type',NULL);
        $carkee_level      = Yii::$app->request->get('sponsor_level',NULL);
        $premium_status    = Yii::$app->request->get('premium_status',NULL);
        $keyword           = Yii::$app->request->get('keyword',NULL);
        $status            = Yii::$app->request->get('status',NULL);
        $role              = Yii::$app->request->get('role',User::ROLE_SPONSORSHIP);

        $page    = Yii::$app->request->get('page', 1);
        $page_size = Yii::$app->request->get('size',10);

        $qry = User::find()->where("1=1");
                    // ->andWhere(['IN','member_type',[User::TYPE_VENDOR,User::TYPE_MEMBER_VENDOR,User::TYPE_CARKEE_VENDOR,User::TYPE_CARKEE_MEMBER_VENDOR]]);

        if(!is_null($account_id)) $qry->andWhere(['account_id' => $account_id]);
        if(!is_null($status)) $qry->andWhere(['status' => $status]);        
        if(!is_null($carkee_level)) $qry->andWhere(['carkee_level' => $carkee_level]);
        if(!is_null($member_type)) $qry->andWhere(['member_type' => $member_type]);
        if(!is_null($premium_status)) $qry->andWhere(['premium_status' => $premium_status]);
        if(!is_null($role)) $qry->andWhere(['role' => $role]);

        if (!is_null($keyword) AND !empty($keyword)){
            $qry->andFilterWhere([
                'or', 
                ['LIKE', 'fullname', $keyword],
                ['LIKE', 'vendor_name', $keyword],
                ['LIKE', 'email', $keyword],
                ['LIKE', 'mobile', $keyword],
            ]);
        }
        
        $totalCount = (int)$qry->count();
        $pages    = new Pagination(['totalCount' => $totalCount, 'defaultPageSize' => $page_size]);        
        $offset   = ($page - 1) * $pages->limit;
        
        $users = $qry->orderBy(['user_id' => SORT_DESC])->offset($offset)->limit($pages->limit)->all();
        
        $data = [];

        foreach($users as $user){
            $data[] = $user->data(1);
        }

        return [
            'data'          => $data,
            // 'current_page'  => $page,
            // 'page_count'    => ceil($pages->totalCount / $pages->pageSize),
            'current_page_size' => count($data),
            'total' => $totalCount,
            'code'  => self::CODE_SUCCESS
        ];

    }

    public function actionAddToAdmin($id)
    {
        /* Make sure user belongs to current account */
        $admin = Yii::$app->user->identity;

        $user = User::findOne($id);
        if (!$user) return LibHelper::errorMessage('Sponsor not found.',true);

        $form = new AdminRoleForm;
        $form = $this->load($form);

        if (!$form->validate()){
            $error = self::getFirstError(ActiveForm::validate($form));
            return LibHelper::errorMessage($error['message'], true);
        }

        $user->role = $form->role;
        $user->save();

        return [
            'success' => TRUE,
            'message' => 'Successfully added to admin',
            'redirect' => Url::home() . 'accountadmin',
        ];
    }

    public function actionIndex()
    {
        return $this->sponsorList();
    }
    public function actionList()
    {
        return $this->sponsorList();
    }
    
    public function actionItemList($id=0)
    {
        $admin             = Yii::$app->user->identity;
        $account_id        = Yii::$app->request->get('account_id',NULL);
        $type              = Yii::$app->request->get('type',NULL);
        $keyword           = Yii::$app->request->get('keyword',NULL);
        $status            = Yii::$app->request->get('status',NULL);

        $page    = Yii::$app->request->get('page', 1);
        $page_size = Yii::$app->request->get('size',10);

        $user = User::findOne($id);
        if (!$user) return LibHelper::errorMessage('Sponsor not found.',true);

        $qry = Item::find()->where(['user_id' => $user->user_id]);
        if($account_id) $qry->andWhere(['account_id' => $account_id]);
        if($status) $qry->andWhere(['status' => $status]);
        if($type) $qry->andWhere(['member_type' => $type]);

        if (isset($keyword)) {
            $qry->andFilterWhere([
                'or', 
                ['LIKE', 'title', $keyword]
            ]);
        }
        
        $totalCount = (int)$qry->count();
        $pages    = new Pagination(['totalCount' => $totalCount, 'defaultPageSize' => $page_size]);        
        $offset   = ($page - 1) * $pages->limit;
        
        $items = $qry->offset($offset)->limit($pages->limit)->all();
        
        $data = [];

        foreach($items as $item){
            $data[] = $item->data(1);
        }

        return [
            'data'          => $data,
            // 'current_page'  => $page,
            // 'page_count'    => ceil($pages->totalCount / $pages->pageSize),
            'current_page_size' => count($data),
            'total' => $totalCount,
            'code'  => self::CODE_SUCCESS
        ];

    }

    public function actionUpdate($id)
    {
        $account = Yii::$app->user->identity;
        $params_data = Yii::$app->request->post();

        $user = User::findOne($id);
        if (!$user) return LibHelper::errorMessage('Sponsor not found.',true);

        $form = new UserForm(['scenario' => 'admin-carkee-sponsor-edit']);
        $form = $this->load($form);

        if (!$form->validate()){
            $error = self::getFirstError(ActiveForm::validate($form));
            return LibHelper::errorMessage($error['message'], true);
        }

        $excludeFields = ['user_id','password','password_confirm','status','vendor_name'];
        
        $fields = LibHelper::getFieldKeys($params_data, $excludeFields);

        foreach($fields as $field){
            $user->{$field} = $form->{$field};
        }

        if (($user->isVendor() OR $user->isCarkeeVendor()) AND empty($user->vendor_name)){
            /**
             * Copy company or fullname
             */
            $user->vendor_name = ($user->isClubOwner()) ? $user->company : $user->fullname;
        }

        $user->save();
        
        $edituser = $user;
        $data = array_merge($edituser->data(1),$edituser->carkeeData(1));

        return [
            'success' => TRUE,
            'message' => 'Successfully updated.',
            'data'    => $data
        ];
    }
    
    public function actionUpdatePassword($id)
    {
        $user = User::findOne($id);
        if (!$user) return LibHelper::errorMessage('Sponsor not found.',true);

        $form = new PasswordForm;
        $form = $this->postLoad($form);

        if (!$form->validate()){
            $error = self::getFirstError(ActiveForm::validate($form));
            return LibHelper::errorMessage($error['message'], true);
        }

        $user->setPassword($form->new);
        $user->save();

        return [
            'success' => TRUE,
            'message' => 'Successfully updated',
        ];        
    }

    public function actionUpdateSettings($id)
    {
        $user = User::findOne($id);
        if (!$user) return LibHelper::errorMessage('Sponsor not found.',true);

        $form = new UserSettingsForm;
        $form = $this->postLoad($form);

        if (!$form->validate()){
            $error = self::getFirstError(ActiveForm::validate($form));
            return LibHelper::errorMessage($error['message'], true);
        }

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

    public function actionUpdateCoordinate($id)
    {
        $user = User::findOne($id);
        if (!$user) return LibHelper::errorMessage('Sponsor not found.',true);

        $form = new MapSettingsForm;
        $form = $this->postLoad($form);

        if (!$form->validate()){
            $error = self::getFirstError(ActiveForm::validate($form));
            return LibHelper::errorMessage($error['message'], true);
        }
        
        $user->longitude = $form->longitude;
        $user->latitude  = $form->latitude;
        $user->save();

        return [
            'success' => TRUE,
            'message' => 'Successfully updated',
        ];        
    }

    public function actionUpdateEmail($id)
    {
        $user = User::findOne($id);
        if (!$user) return LibHelper::errorMessage('Sponsor not found.',true);

        $form = new EmailForm;
        $form = $this->postLoad($form);

        if (!$form->validate()){
            $error = self::getFirstError(ActiveForm::validate($form));
            return LibHelper::errorMessage($error['message'], true);
        }

        $user->email = $form->email;
        $user->save();

        return [
            'success' => TRUE,
            'message' => 'Successfully updated',
        ];        
    }

    public function actionUpdateMobile($id)
    {
        $user = User::findOne($id);
        if (!$user) return LibHelper::errorMessage('Sponsor not found.',true);

        $form = new MobileForm;
        $form = $this->postLoad($form);

        if (!$form->validate()){
            $error = self::getFirstError(ActiveForm::validate($form));
            return LibHelper::errorMessage($error['message'], true);
        }
        
        $user->mobile = $form->mobile;
        $user->username = $form->mobile;
        $user->save();

        return [
            'success' => TRUE,
            'message' => 'Successfully updated',
        ];        
    }

    public function actionAttach($id)
    {
        $user = User::findOne($id);
        if (!$user) return LibHelper::errorMessage('Sponsor not found.',true);

        $form = new FileForm;
        $form = $this->postLoad($form);

        if (!$form->validate()){
            $error = self::getFirstError(ActiveForm::validate($form));
            return LibHelper::errorMessage($error['message'], true);
        }

        $img_field = 'filename';
        $tmp = [];
        if(!empty($_FILES)){
            foreach($_FILES as $file) {
                $tmp['NewsForm'] = [
                    'name'     => [$img_field => $file['name']],
                    'type'     => [$img_field => $file['type']],
                    'tmp_name' => [$img_field => $file['tmp_name']],
                    'error'    => [$img_field => $file['error']],
                    'size'     => [$img_field => $file['size']],
                ];
            }

            $_FILES = $tmp;
        }

        $uploadFile = UploadedFile::getInstance($form, $img_field);

        if (!$uploadFile) return LibHelper::errorMessage('Please attach a file.',true);

        $fileDestination = Yii::$app->params['dir_identity'] . $uploadFile->name;

        if (!is_null($uploadFile) AND count($_FILES) > 0 AND $uploadFile->saveAs($fileDestination)) {
            $file = new UserFile;
            $file->filename = $uploadFile->name;
            $file->type = $form->type;
            $file->user_id = $user->user_id;
            $file->account_id = $user->account_id;
            $file->decription = $form->decription;
            $file->save();

            return [
                'success' => TRUE,
                'message' => 'Successfully uploaded.'
            ];
        } 

        return LibHelper::errorMessage('Error uploading the file.',true);        
    }

    public function actionAttachRemove($id)
    {

        $file = UserFile::findOne($id);

        if (!$file) return LibHelper::errorMessage('File not found.',true);   

        $file->delete();

        return [
            'success' => TRUE,
            'message' => 'Successfully removed.'
        ];
    }

    public function actionApprove()
    {
        $id = Yii::$app->request->post('id');
        $account = Yii::$app->user->getIdentity();

        $user = User::findOne($id);
        if (!$user) return LibHelper::errorMessage('Vendor not found.',true);

        // if ($user->isApproved()) return LibHelper::errorMessage('Vendor is already approved.',true);
        // elseif (!$user->isPending()) return LibHelper::errorMessage('Vendor is not in pending status.',true);        
        // elseif($user->approved_by AND $user->approved_by == $account->user_id) return LibHelper::errorMessage('You already approved this. Please let someone be the checker.',true);

        // if(!$account->isAdministrator()) return LibHelper::errorMessage("Can't Update Status! You don't have the required permission to apply changes.",true);

        if(!$account->isAdministrator()) return LibHelper::errorMessage("Can't Update Status! You don't have the required permission to apply changes.",true);

        if($user->isApproved() AND $user->isConfirmed()) return LibHelper::errorMessage('User is already approved.',true);                
        
        if(!$account->isRoleSuperAdmin()) if(($user->isConfirmed() OR $user->isPending()) AND $user->account_id != $account->account_id) return LibHelper::errorMessage("Can only change status by Admins of this Club",true);        
        

        $message = null;
        $settings = Settings::find()->one();

        if(($user->user_settings AND $user->user_settings->is_one_approval > 0) OR ($account->account AND $account->account->is_one_approval) OR $settings->is_one_approval){
            if (!$user->approved_by) {
                $user->approved_by = $account->user_id;  
                $user->confirmed_by = $account->user_id;  
                $message = "Successfully Approved! Sponsor's Account Details";
            }
        }else{
            if(!$user->confirmed_by AND $user->confirmed_by != $account->user_id) {
                $user->confirmed_by = $account->user_id;            
                $message = "Successfully Confirmed! Sponsor's Account Details";
            }else if (!$user->approved_by AND ($user->confirmed_by != $account->user_id AND $user->approved_by != $account->user_id)) {
                $user->approved_by = $account->user_id;  
                $message = "Successfully Approved! Sponsor's Account Details";
            }
        }
        $user->save();

        if ($user->approved_by AND $user->confirmed_by){
            $user->status      = User::STATUS_APPROVED;
            $user->approved_at = date('Y-m-d H:i:s');
            $user->save();
        }
        
        //if($message)  {}
        return [
            'success' => TRUE,
            'message'   => $message
        ];
       // return LibHelper::errorMessage("Unable to Approve Sponsor's Account Details",true);
    }

    public function actionReject()
    {
        $account = Yii::$app->user->getIdentity();
        $user_id = Yii::$app->request->post('user_id');

        $user = User::findOne($user_id);
        if (!$user) return LibHelper::errorMessage('Sponsor not found.',true);

        if ($user->isIncomplete()) return LibHelper::errorMessage('Sponsor is already rejected.',true);
        elseif (!$user->isPending()) return LibHelper::errorMessage('Sponsor is not in pending status.',true);

        $user->status = User::STATUS_INCOMPLETE;
        $user->save();

        return [
            'success' => TRUE,
            'message'   => 'Sponsor application was rejected.',
        ];
    }

    public function actionAddSponsor()
    {
        $admin = Yii::$app->user->getIdentity();
        //$account = Yii::$app->user->identity;
        $account = $admin->account_id;

        $form = new UserForm(['scenario' => 'account_add_sponsor']);
        $form = $this->postLoad($form);

        $form->account_id = $admin->account_id;

        if (!$form->validate()){
            $error = self::getFirstError(ActiveForm::validate($form));
            return LibHelper::errorMessage($error['message'], true);
        }

        $params_data = Yii::$app->request->post();

        /**
         * Save user
         */
        $user              = new User;
        $user->member_type = User::TYPE_MEMBER;
        $user->account_id  = $admin->account_id;
        $user->step        = 7;

        $excludeFields = ['user_id','pin_hash','password','password_confirm','status'];
        $fields = LibHelper::getFieldKeys($params_data, $excludeFields);

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

    public function actionEditSponsor($id)
    {
        $admin = Yii::$app->user->getIdentity();
        $user = User::findOne($id);
        if (!$user) return LibHelper::errorMessage('Sponsor not found.',true);

        $form = new UserForm(['scenario' => 'account_edit_sponsor']);
        $form = $this->postLoad($form);

        if (!$form->validate()){
            $error = self::getFirstError(ActiveForm::validate($form));
            return LibHelper::errorMessage($error['message'], true);
        }

        $params_data = Yii::$app->request->post();
        
        $excludeFields = ['user_id','pin_hash','password','password_confirm','status'];
        $fields = LibHelper::getFieldKeys($params_data, $excludeFields);

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

    public function actionDelete($id)
    {
        $account = Yii::$app->user->getIdentity();

        $user = User::findOne($id);
        if (!$user) return LibHelper::errorMessage('Sponsor not found.',true);

        $user->status = User::STATUS_DELETED;
        $user->save();

        return [
            'success' => TRUE,
            'message'   => 'User was successfully deleted.',
        ];
    }

    public function actionHardDelete($id)
    {
        $account = Yii::$app->user->getIdentity();

        $user = User::findOne($id);
        if (!$user) return LibHelper::errorMessage('Sponsor not found.',true);

        $user->delete();

        return [
            'success' => TRUE,
            'message'   => 'User was successfully deleted.',
        ];
    }


    /* Sponsor Level*/
    public function actionNormal($id) {
        $account = Yii::$app->user->getIdentity();

        $user = User::findOne($id);
        if (!$user) return LibHelper::errorMessage('Sponsor not found.',true);

        $user->role = User::ROLE_SPONSORSHIP;
        $user->level = User::LEVEL_NORMAL;
        $user->carkee_level= User::LEVEL_NORMAL;
        $user->save();

        return [
            'success' => TRUE,
            'message' => 'Successfully',
        ];
    }
    public function actionSilver($id) {
        $account = Yii::$app->user->getIdentity();

        $user = User::findOne($id);
        if (!$user) return LibHelper::errorMessage('Sponsor not found.',true);

        $user->role = User::ROLE_SPONSORSHIP;
        $user->level = User::LEVEL_SILVER;
        $user->carkee_level= User::LEVEL_SILVER;
        $user->save();

        return [
            'success' => TRUE,
            'message' => 'Successfully',
        ];
    }
    public function actionGold($id) {
        $account = Yii::$app->user->getIdentity();

        $user = User::findOne($id);
        if (!$user) return LibHelper::errorMessage('Sponsor not found.',true);

        $user->role = User::ROLE_SPONSORSHIP;
        $user->level = User::LEVEL_GOLD;
        $user->carkee_level=  User::LEVEL_GOLD;
        $user->save();

        return [
            'success' => TRUE,
            'message' => 'Successfully',
        ];
    }
    public function actionPlatinum($id) {
        $account = Yii::$app->user->getIdentity();

        $user = User::findOne($id);
        if (!$user) return LibHelper::errorMessage('Sponsor not found.',true);

        $user->role = User::ROLE_SPONSORSHIP;
        $user->level = User::LEVEL_PLATINUM;
        $user->carkee_level= User::LEVEL_PLATINUM;
        $user->save();

        return [
            'success' => TRUE,
            'message' => 'Successfully',
        ];
    }

    public function actionDiamond($id) {
        $account = Yii::$app->user->getIdentity();

        $user = User::findOne($id);
        if (!$user) return LibHelper::errorMessage('Sponsor not found.',true);

        $user->role = User::ROLE_SPONSORSHIP;
        $user->level = User::LEVEL_DIAMOND;
        $user->carkee_level= User::LEVEL_DIAMOND;
        $user->save();

        return [
            'success' => TRUE,
            'message' => 'Successfully',
        ];
    }

    public function actionRemoveLevel($id) {
        $account = Yii::$app->user->getIdentity();

        $user = User::findOne($id);
        if (!$user) return LibHelper::errorMessage('Sponsor not found.',true);

        $user->role = User::ROLE_USER;
        $user->level= 0;
        $user->carkee_level= 0;
        $user->save();

        return [
            'success' => TRUE,
            'message' => 'Successfully',
        ];
    }
}