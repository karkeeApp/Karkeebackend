<?php
namespace common\controllers\apicarkee\admin;

use common\forms\AdminRoleForm;
use Yii;

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
use common\models\UserFile;
use yii\data\Pagination;

class VendorController extends Controller
{
    
    public function actionSearchByEmail($email)
    {
        $account = Yii::$app->user->identity;
        $user = User::find()->where(['email' => $email])->one();

        if (!$user){
            return [
                'code' => self::CODE_SUCCESS,
                'data' => null
            ];
        }

        return [
            'success' => TRUE,
            'message' => 'Successfully Retrieved.',
            'data' => $user->data(1)
        ];
    }

    public function actionSearchVendorName()
    {
        $account = Yii::$app->user->identity;
        $name = Yii::$app->request->get('name');
        $user = User::find()->where(['vendor_name' => $name])->one();

        if (!$user) return LibHelper::errorMessage("No User Found!",true);

        return [
            'success' => TRUE,
            'message' => 'Successfully Retrieved.',
            'data' => $user->data(1)
        ];
    }

    public function actionCreateVendor()
    {
        $admin = Yii::$app->user->getIdentity();
        $account = $admin->account;
        $params_data = Yii::$app->request->post();

        $form = new UserForm(['scenario' => 'admin-carkee-vendor-add']);
        $form = $this->postLoad($form);
        
        $form->account_id = $admin->account_id;    
        $form->add_1 = $form->company_add_1;    
        $form->add_2 = $form->company_add_2;    
        $form->country = $admin->country;

        if (!$form->validate()){
            $error = self::getFirstError(ActiveForm::validate($form));
            return LibHelper::errorMessage($error['message'], true);
        }
        $useraccount = User::find()->where(['email' => $form->email])->andWhere(['account_id' => $admin->account_id])->one();
        if($useraccount) $user = $useraccount;
        else{
            $user = new User;
            $user->account_id  = $admin->account_id;
        }
        $user->member_type = User::TYPE_VENDOR;
        
        $user->is_vendor   = 1;
        $user->step        = 7;

        $excludeFields = ['member_type','step','user_id','pin_hash','password','password_confirm','status'];
        
        $fields = LibHelper::getFieldKeys($params_data, $excludeFields);
        
        foreach($fields as $field){
            $user->{$field} = $form->{$field};
        }
        $user->status      = User::STATUS_PENDING;

        if (!empty($form->password)) {
            $user->setPassword($form->password);
        }
        $user->setPin('0000'); // default pin

        $user->save();

        return [
            'success' => TRUE,
            'message'   => 'Vendor was created successfully.',
            'data' => $user->data(1)
        ];
    }

    public function actionConvertToVendor($email)
    {

        $user = Yii::$app->user->identity;
        $user = User::find()->where(['email' => $email])->one();

        if (!$user) return LibHelper::errorMessage("No User Found!",true);


        if ($user->is_vendor != 0) return LibHelper::errorMessage('Already a Vendor',true);

        $params_data = Yii::$app->request->post();

        $form = new UserForm(['scenario' => 'admin-carkee-vendor-edit']);
        $form = $this->postLoad($form);
        
        if (!$form->validate()){
            $error = self::getFirstError(ActiveForm::validate($form));
            return LibHelper::errorMessage($error['message'], true);
        }

        $excludeFields = ['user_id','pin_hash','password','password_confirm','status'];
        $fields = LibHelper::getFieldKeys($params_data, $excludeFields);
        
        foreach($fields as $field){
            $user->{$field} = $form->{$field};
        }

        if (!empty($form->password)) {
            $user->setPassword($form->password);
        }

        $user->status      = User::STATUS_PENDING;

        $user->save();
            
        return [
            'success' => TRUE,
            'message' => 'Vendor details Successfully updated.',
            'data' => $user->data(1)
        ];
    }
 
    public function actionVendorView()
    {
        $account = Yii::$app->user->identity;
        $id = Yii::$app->request->get('id');
        $vendor = User::findOne($id);

        if (!$vendor )  return LibHelper::errorMessage("No User Found!",true);

        return [
            'success' => TRUE,
            'message' => 'Successfully Retrieved.',
            'data' => $vendor->data(1)
        ];

    }




    private function vendorList()
    {
        $admin             = Yii::$app->user->identity;
        $account_id        = Yii::$app->request->get('account_id',NULL);
        $type              = Yii::$app->request->get('type',NULL);
        $keyword           = Yii::$app->request->get('keyword',NULL);
        $status            = Yii::$app->request->get('status',NULL);
        $role              = Yii::$app->request->get('role',NULL);

        $page    = Yii::$app->request->get('page', 1);
        $page_size = Yii::$app->request->get('size',10);

        $qry = User::find()->where("1=1");
        
        if(!empty($admin)) $qry->where(['<>','user_id', $admin->user_id]);
        if(!is_null($account_id)) $qry->andWhere(['account_id' => $account_id]);
        if(!is_null($status)) $qry->andWhere(['status' => $status]);
        if(!is_null($type)) $qry->andWhere(['member_type' => $type]);
        if(!is_null($role)) $qry->andWhere(['role' => $role]);
        if(is_null($keyword)) $keyword = Yii::$app->request->get('search',NULL);
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
        if (!$user) return LibHelper::errorMessage('Vendor not found.',true);

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
        return $this->vendorList();
    }
    public function actionList()
    {
        return $this->vendorList();
    }
    public function actionGetAll()
    {
        return $this->vendorList();
    }

    public function actionItemlist($id=0)
    {
        $admin             = Yii::$app->user->identity;
        $account_id        = Yii::$app->request->get('account_id',NULL);
        $type              = Yii::$app->request->get('type',NULL);
        $keyword           = Yii::$app->request->get('keyword',NULL);
        $status            = Yii::$app->request->get('status',NULL);

        $page    = Yii::$app->request->get('page', 1);
        $page_size = Yii::$app->request->get('size',10);

        $user = User::findOne($id);
        if (!$user) return LibHelper::errorMessage('Vendor not found.',true);

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

        $form = new UserForm(['scenario' => 'admin-carkee-vendor-edit']);
        $form = $this->postLoad($form);

        $user = User::findOne($id);
        if (!$user) return LibHelper::errorMessage('Vendor not found.',true);

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
            'code'  => self::CODE_SUCCESS,
            'success' => TRUE,
            'message' => 'Successfully updated.',
            'data'    => $data
        ];
    }
    
    public function actionAttach($id)
    {
        $admin = Yii::$app->user->getIdentity();
        $account = $admin->account;

        $user = User::findOne($id);
        if (!$user) return LibHelper::errorMessage('Vendor not found.',true);
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

        $form = new FileForm(['scenario' => 'admin-carkee-vendor-attach']);
        $form = $this->postLoad($form);
        
        if (!$form->validate()){
            $error = self::getFirstError(ActiveForm::validate($form));
            return LibHelper::errorMessage($error['message'], true);
        }

        //$uploadFile = UploadedFile::getInstanceByName('filename');
        $uploadFile = UploadedFile::getInstance($form, $img_field);

        if (!$uploadFile) {
            return [
                'success' => FALSE,
                'error' => 'Please attach a file.'
            ];
            Yii::$app->end();
        }

        $fileDestination = Yii::$app->params['dir_identity'] . $uploadFile->name;

        if (!is_null($uploadFile) AND count($_FILES) > 0 AND $uploadFile->saveAs($fileDestination)) {
            $file = new UserFile;
            $file->filename = $uploadFile->name;
            $file->type = $form->type;
            $file->user_id = $id;
            $file->account_id = $user->account_id;
            $file->decription = $form->decription;
            $file->save();

            return [
                'code'  => self::CODE_SUCCESS,
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

    public function actionAttachremove($id=0)
    {
        $file = UserFile::findOne($id);

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
            'code'  => self::CODE_SUCCESS,
            'success' => TRUE,
            'message' => 'Successfully removed.'
        ];
    }


    public function actionAddVendor()
    {
        $admin = Yii::$app->user->getIdentity();
        $account = $admin->account;
        $params_data = Yii::$app->request->post();

        $form = new UserForm(['scenario' => 'admin-carkee-vendor-add']);
        $form = $this->postLoad($form);
        
        $form->account_id = $admin->account_id;    

        if (!$form->validate()){
            $error = self::getFirstError(ActiveForm::validate($form));
            return LibHelper::errorMessage($error['message'], true);
        }

        $vendorLogo = UploadedFile::getInstanceByName('img_vendor');

        $user              = new User;
        $user->member_type = User::TYPE_VENDOR;
        $user->account_id  = $admin->account_id;
        $user->step        = 7;


        $excludeFields = ['member_type','step','user_id','pin_hash','password','password_confirm','status'];
        
        $fields = LibHelper::getFieldKeys($params_data, $excludeFields);
        
        foreach($fields as $field){
            $user->{$field} = $form->{$field};
        }

        if (!empty($form->password)) {
            $user->setPassword($form->password);
        }

        if (!empty($vendorLogo)) {
            $newFilename = hash('crc32', $vendorLogo->name) . time() . '.' . $vendorLogo->getExtension();                
            $fileDestination = Yii::$app->params['dir_member'] . $newFilename;    
            if (!$vendorLogo->saveAs($fileDestination)) {
                return [
                    'code'    => self::CODE_ERROR,
                    'message' => 'Error uploading the file'
                ];
            }    
            $user->img_vendor = $newFilename;
        }


        $user->setPin('0000'); // default pin

        $user->save();

        return [
            'code'  => self::CODE_SUCCESS,
            'success' => TRUE,
            'message'   => 'Vendor was successfully added.',
        ];
    }

    public function actionEditVendor($id)
    {
        $admin = Yii::$app->user->getIdentity();
        $account = $admin->account;

        $user = User::findOne($id);
        if (!$user) return LibHelper::errorMessage('Vendor not found.',true);

        $params_data = Yii::$app->request->post();

        $form = new UserForm(['scenario' => 'admin-carkee-vendor-edit']);
        $form = $this->postLoad($form);
        
        if (!$form->validate()){
            $error = self::getFirstError(ActiveForm::validate($form));
            return LibHelper::errorMessage($error['message'], true);
        }

        $vendorLogo = UploadedFile::getInstanceByName('img_vendor');

        $excludeFields = ['user_id','pin_hash','password','password_confirm','status'];
        $fields = LibHelper::getFieldKeys($params_data, $excludeFields);
        
        foreach($fields as $field){
            $user->{$field} = $form->{$field};
        }

        if (!empty($form->password)) {
            $user->setPassword($form->password);
        }

        if (!empty($vendorLogo)) {
            $newFilename = hash('crc32', $vendorLogo->name) . time() . '.' . $vendorLogo->getExtension();                
            $fileDestination = Yii::$app->params['dir_member'] . $newFilename;    
            if (!$vendorLogo->saveAs($fileDestination)) {
                return [
                    'code'    => self::CODE_ERROR,
                    'message' => 'Error uploading the file'
                ];
            }    
            $user->img_vendor = $newFilename;
        }

        $user->save();
            
        return [
            'code'  => self::CODE_SUCCESS,
            'success' => TRUE,
            'message' => 'Vendor details Successfully updated.',
        ];
    }

    public function actionDelete($id)
    {
        $user = User::findOne($id);
        if (!$user) return LibHelper::errorMessage('Vendor not found.',true);

        $user->status = User::STATUS_DELETED;
        $user->save();

        return [
            'code'  => self::CODE_SUCCESS,
            'success' => TRUE,
            'message'   => 'User was successfully deleted.',
        ];
    }

    public function actionHardDelete($id)
    {
        $user = User::findOne($id);
        if (!$user) return LibHelper::errorMessage('Vendor not found.',true);

        $user->delete();

        return [
            'code'  => self::CODE_SUCCESS,
            'success' => TRUE,
            'message'   => 'User was successfully deleted.',
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
            'code'  => self::CODE_SUCCESS,
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
            $user->carkee_level       = $form->carkee_level;
        } else {
            $user->level              = $form->level;
        }

        if (($user->isVendor() OR $user->isCarkeeVendor()) AND empty($user->vendor_name)){
            /**
             * Copy company or fullname
             */
            $user->vendor_name = ($user->isClubOwner()) ? $user->company : $user->fullname;
        }

        $user->save();

        return  [
            'code'  => self::CODE_SUCCESS,
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
            'code'  => self::CODE_SUCCESS,
            'success' => TRUE,
            'message' => 'Successfully updated',
        ];        
    }

    public function actionUpdateEmail($id){
        $user = User::findOne($id);
        if (!$user) return LibHelper::errorMessage('Vendor not found.',true);

        $form = new EmailForm;
        $form = $this->postLoad($form);

        if (!$form->validate()){
            $error = self::getFirstError(ActiveForm::validate($form));
            return LibHelper::errorMessage($error['message'], true);
        }
        
        $user->email = $form->email;
        $user->save();

        return [
            'code'  => self::CODE_SUCCESS,
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
        //$user->username = $form->mobile;
        $user->save();

        return [
            'code'  => self::CODE_SUCCESS,
            'success' => TRUE,
            'message' => 'Successfully updated',
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

        if($user->isApproved() AND $user->isConfirmed()) return LibHelper::errorMessage('Vendor is already approved.',true);                
        
        if(!$account->isRoleSuperAdmin()) if(($user->isConfirmed() OR $user->isPending()) AND $user->account_id != $account->account_id) return LibHelper::errorMessage("Can only change status by Admins of this Club",true);        
        
        $message = null;
        $settings = Settings::find()->one();
        $user_default_expiry = date('Y-m-d', strtotime('+1 year', strtotime($user->member_expire)));
        if(($user->user_settings AND $user->user_settings->is_one_approval > 0) OR ($account->account AND $account->account->is_one_approval) OR $settings->is_one_approval){
            if (!$user->approved_by) {
                $user->approved_by = $account->user_id;  
                $user->confirmed_by = $account->user_id;  
                $message = "Successfully Approved! Vendor's Account Details";
            }
        }else{
            if(!$user->confirmed_by AND $user->confirmed_by != $account->user_id) {
                $user->confirmed_by = $account->user_id;            
                $message = "Successfully Confirmed! Vendor's Account Details";
            }else if (!$user->approved_by AND ($user->confirmed_by != $account->user_id AND $user->approved_by != $account->user_id)) {
                $user->approved_by = $account->user_id;  
                $message = "Successfully Approved! Vendor's Account Details";
            }
        }

        if (!$user->confirmed_by AND $user->approved_by !== $account->user_id) {
            $user->confirmed_by = $account->user_id;
            $user->status      = User::STATUS_APPROVED;   
            $message = "Successfully Approved! Vendor's Account Details";
            $user->save();
        }

        if ($user->approved_by AND $user->confirmed_by){
            $user->status      = User::STATUS_APPROVED;
            $user->approved_at = date('Y-m-d H:i:s');         
            $user->member_expire = $user_default_expiry;

            $title = "One (1) vendor registration had been approved with ID #: ".$user->user_id;
            $desc = strtoupper(($account->account_id > 0 ? $account->account->company : "Karkee"))."'s Member ".($user->fullname ? $user->fullname : $user->firstname)." request for registration is now approved";
            LibHelper::pushNotificationFCM_ToMemberDirector($title, $desc, ($account->account_id > 0 ? $account->account->company : "Karkee"), $account->account_id);
                
        }
        $user->save();
        if($message) 
        {
            return [
                'code'  => self::CODE_SUCCESS,
                'success' => TRUE,
                'message'   => $message
            ];
        }

        return LibHelper::errorMessage("Unable to Approve Member's Account Details",true);
    }

    public function actionReject()
    {
        $id = Yii::$app->request->post('id');
        $account = Yii::$app->user->getIdentity();
        
        $user = User::findOne($id);
        if (!$user) return LibHelper::errorMessage('Vendor not found.',true);

        if ($user->isIncomplete()) return LibHelper::errorMessage('Vendor is already rejected.',true);
        elseif (!$user->isPending()) return LibHelper::errorMessage('Vendor is not in pending status.',true);

        $user->status = User::STATUS_REJECTED;
        $user->save();

        return [
            'code'  => self::CODE_SUCCESS,
            'success' => TRUE,
            'message'   => 'Vendor application was rejected.',
        ];
    }

}
