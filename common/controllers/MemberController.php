<?php
namespace common\controllers;

use common\lib\DateLib;
use Yii;
use yii\web\View;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use common\forms\UserForm;
use common\forms\CreditLimitForm;
use common\forms\PasswordForm;
use common\forms\EmailForm;
use common\forms\MobileForm;
use common\forms\FileForm;
use common\forms\UserSettingsForm;
use common\forms\MapSettingsForm;
use common\forms\AdminRoleForm;

use common\models\User;
use common\models\HRStaffUpdate;
use common\models\Account;
use common\models\UserLog;

use common\helpers\Common;
use common\helpers\HRHelper;
use common\helpers\UserHelper;
use common\models\Renewal;
use yii\imagine\Image;
use yii\helpers\FileHelper;

class MemberController extends Controller
{
    public function actionIndex()
    {
        $data['menu'] = $this->menu;

        Yii::$app->view->on(View::EVENT_END_BODY, function () {
            global $data;

            $data['adminRoleForm'] = new AdminRoleForm;

            echo $this->renderPartial('@common/views/member/modals.tpl', $data);  
        });
        
        return $this->render('@common/views/member/index.tpl', $data);
    }

    public function actionRenewalAttachment()
    {
        $loginUser = Yii::$app->user->identity;
        $id        = Yii::$app->request->get('u');
        $size    = Yii::$app->request->get('size', 'medium');
        $field   = Yii::$app->request->get('f');
        $renewal   = Renewal::findOne($id);

        if (!in_array($size, ['small', 'medium', 'large'])){
            throw new \yii\web\HttpException(404, 'Invalid size.');
        }
        
        try{
            $dir = Yii::$app->params['dir_renewal'];
            $subDir = $dir . "{$size}/";

            /**
             * Load default profile
             */
            if (empty($renewal->{$field})) $renewal->{$field} = 'default-profile.png';
            $filename = $renewal->{$field};

            if (!file_exists($subDir)) FileHelper::createDirectory($subDir);

            $mimeType = mime_content_type($dir . $filename);

            $info = getimagesize ($dir . $filename);

            if ($info AND preg_match("/image/", $mimeType)) {
                $originalPath = $dir . $filename;
                $thumbPath    = $subDir . $filename;

                $mimeType = mime_content_type($originalPath);

                if (!file_exists($thumbPath)) {
                    if ($size == 'small') {
                        Image::resize($originalPath, 100, 100)->save($thumbPath, ['quality' => 100]);;
                    } elseif ($size == 'medium'){
                        Image::resize($originalPath, 600, 600)->save($thumbPath, ['quality' => 100]);;
                    } else {
                        Image::resize($originalPath, 1024, 1024)->save($thumbPath, ['quality' => 100]);;
                    }
                }

                Yii::$app->response->sendFile($thumbPath, NULL, ['inline' => TRUE]);
            }else if(preg_match("/image/", $mimeType)){
                Yii::$app->response->sendFile('../../default_file.png', NULL, ['inline' => TRUE]);
            } else {
                Yii::$app->response->sendFile($dir . $filename, NULL, ['inline' => TRUE]);
            }
        } catch(\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function actionRenewal()
    {
        $data['account'] = Yii::$app->user->identity;

        return $this->render('@common/views/member/renewal_list.tpl', $data);
    }

    public function actionPendingapproval()
    {
        $data['account'] = Yii::$app->user->identity;

        return $this->render('@common/views/member/pendingapproval_list.tpl', $data);
    }

    public function actionDeleted()
    {
        $data['account'] = Yii::$app->user->identity;

        return $this->render('@common/views/member/deleted_list.tpl', $data);
    }

    public function actionAddVendor()
    {
        $data['menu'] = $this->menu;
        $data['staff_menu'] = '';

        $data['userForm'] = new UserForm(['scenario' => 'account_add_vendor']);

        return $this->render('@common/views/member/vendor_form.tpl', $data);        
    }

    public function actionEditVendor($id)
    {
        $data['menu'] = $this->menu;
        $data['staff_menu'] = '';

        $data = $this->_getUser($id);

        $data['menu'] = $this->renderPartial('@common/views/member/member_menu.tpl', $data);

        $data['userForm'] = new UserForm(['scenario' => 'account_edit_vendor']);
        $data['userForm']->setAttributes($data['user']->attributes, FALSE);

        return $this->render('@common/views/member/vendor_form.tpl', $data);        
    }

    private function _getUser($id)
    {
        $data['user'] = User::findOne($id);
        $data['account'] = Yii::$app->user->identity;
        $account = Yii::$app->user->identity;
        extract($data);

        if (!$user OR (Common::isClub() AND $user->account_id != $account->account_id)) {
            throw new \yii\web\HttpException(404, 'User not found.');
            return;
        }

        return $data;
    }

	public function actionView($id=0)
    {
        $data = $this->_getUser($id);
        $logged_user = Yii::$app->user->identity;
        $data['logged_user'] = $logged_user;
        $data['menu'] = $this->renderPartial('@common/views/member/member_menu.tpl', $data);

        $data['age']      = DateLib::getAge($data['user']->birthday);
        $data['birthday'] = DateLib::dateFormat($data['user']->birthday);

        $data['userForm'] = new UserForm;
        $data['userForm']->setAttributes($data['user']->attributes, FALSE);
        // $user = $data['user'];
        // $users = $user->getRenewals()->andWhere(['status' => 2])->all();
        // foreach($users as $key => $user){
        //     dd($user); 
        // }
        return $this->render('@common/views/member/view.tpl', $data);
    }

    public function actionSummary($id=0)
    {
        $data = $this->_getUser($id);

        if (Common::isStaff()) {
            $data['menu'] = $this->renderPartial('summary_menu.tpl');
        } else {
            $data['menu'] = $this->renderPartial('@common/views/member/member_menu.tpl', $data);
        }

        return $this->render('@common/views/member/summary.tpl', $data);
    }

    public function actionEdit($id=0)
    {
        // global $data;

        $data = $this->_getUser($id);
        $logged_user = Yii::$app->user->identity;
        $data['logged_user'] = $logged_user;
        $data['menu'] = $this->renderPartial('@common/views/member/member_menu.tpl', $data);

        

        $data['userForm'] = new UserForm(['scenario' => 'admin_edit_member']);
        $data['userForm']->setAttributes($data['user']->attributes, FALSE);
        // dd($data['userForm']);
        

        return $this->render('@common/views/member/form.tpl', $data);  
    }

    public function actionEditDocs($id=0)
    {
        global $data;

        $data = $this->_getUser($id);
        $logged_user = Yii::$app->user->identity;
        $data['logged_user'] = $logged_user;
        $data['menu'] = $this->renderPartial('@common/views/member/member_menu.tpl', $data);

        
        $data['userForm'] = new UserForm(['scenario' => 'edit_documents']);
        $data['userForm']->setAttributes($data['user']->attributes, FALSE);
        // dd($data['userForm']);
        
        return $this->render('@common/views/member/document.tpl', $data);  
    }

    public function actionSettings($id=0)
    {
        $data = $this->_getUser($id);

        $data['menu'] = $this->menu;
        $data['member_menu'] = $this->renderPartial('@common/views/member/member_menu.tpl', $data);

        $data['userSettingsForm'] = new UserSettingsForm;
        $data['userSettingsForm']->setAttributes($data['user']->attributes, FALSE);

        /** 
         * Password
         */
        $data['passwordForm'] = new PasswordForm;

        /** 
         * Email
         */
        $data['emailForm'] = new EmailForm;
        $data['emailForm']->setAttributes($data['user']->attributes, FALSE);

        /** 
         * Mobile
         */
        $data['mobileForm'] = new MobileForm;
        $data['mobileForm']->setAttributes($data['user']->attributes, FALSE);

        /**
         * Map Coordinates
         */
        $data['mapSettingsForm'] = new MapSettingsForm;
        $data['mapSettingsForm']->setAttributes($data['user']->attributes, FALSE);

        return $this->render('@common/views/member/settings.tpl', $data);
    }

    public function actionDoc()
    {
        $account = Yii::$app->user->identity; 

        $field   = Yii::$app->request->get('f');
        $user_id = Yii::$app->request->get('u');
        $size    = Yii::$app->request->get('size', 'medium');

        $user = User::findOne($user_id);

        if (!$user OR !array_key_exists($field, $user->attributes)) {
            throw new \yii\web\HttpException(404, 'File not found.');
        } elseif(Common::isClub() AND $user->account_id != $account->account_id) {
            throw new \yii\web\HttpException(404, 'File not found.');
        }

        if (!in_array($size, ['small', 'medium', 'large'])){
            throw new \yii\web\HttpException(404, 'Invalid size.');
        }

        try{

            $dir = Yii::$app->params['dir_member'];
            $subDir = $dir . "{$size}/";

            if (!file_exists($subDir)) FileHelper::createDirectory($subDir);

            if (in_array($field, ['img_profile', 'img_vendor', 'company_logo','club_logo','brand_guide']) AND empty($user->{$field})) $user->{$field} = 'default-profile.png';

            $filename = $user->{$field};

            $mimeType = mime_content_type($dir . $filename);

            $info = getimagesize ($dir . $filename);

            if ($info AND preg_match("/image/", $mimeType)) {
                $originalPath = $dir . $filename;
                $thumbPath    = $subDir . $filename;

                $mimeType = mime_content_type($originalPath);

                if (!file_exists($thumbPath)) {
                    if ($size == 'small') {
                        Image::resize($originalPath, 100, 100)->save($thumbPath, ['quality' => 100]);;
                    } elseif ($size == 'medium'){
                        Image::resize($originalPath, 600, 600)->save($thumbPath, ['quality' => 100]);;
                    } else {
                        Image::resize($originalPath, 1024, 1024)->save($thumbPath, ['quality' => 100]);;
                    }
                }

                Yii::$app->response->sendFile($thumbPath, NULL, ['inline' => TRUE]);
            }elseif(preg_match("/image/", $mimeType)){
                Yii::$app->response->sendFile('../../default_file.png', NULL, ['inline' => TRUE]);
            } else {
                Yii::$app->response->sendFile($dir . $filename, NULL, ['inline' => TRUE]);
            }
        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }

    public function actionItems($id)
    {
        $data = $this->_getUser($id);

        $data['menu'] = $this->renderPartial('@common/views/member/vendor_menu.tpl', $data);

        return $this->render('@common/views/member/item_list.tpl', $data);        
    }

    public function actionDownload()
    {
        $memberQry = Yii::$app->session['memberQry'];

        $file = 'Members-' . time();

        \moonland\phpexcel\Excel::export([
            'models' => $memberQry->all(),
            'setFirstTitle' => 'Members',
            'asAttachment'  => true,
            'autoSize'      => true,
            'columns' => [
                'user_id',
                'fullname',
                'email',
                [
                    'attribute' => 'mobile',
                    'header' => 'Contact Number',
                    'format' => 'text',
                    'value' => function($model) {
                        return "($model->mobile_code)" .  $model->mobile;
                    },
                ],
                [
                    'attribute' => 'status',
                    'header' => 'Status',
                    'format' => 'text',
                    'value' => function($model) {
                        return $model->status();
                    },
                ],
                [
                    'attribute' => 'created_at',
                    'header' => 'Join Date',
                    'format' => 'date'
                ],
            ],
        ]);
    }

    public function actionDownloadRenewal()
    {
        $account = Yii::$app->user->identity; 

        $file = 'Renewal-' . time();
        $renewals = Renewal::find()->where(['account_id' => $account->account_id])->orderBy(['id' => SORT_DESC])->all();
        \moonland\phpexcel\Excel::export([
            'models' => $renewals,
            'setFirstTitle' => 'Renewals',
            'asAttachment'  => true,
            'autoSize'      => true,
            'columns' => [
                'user_id',
                [
                    'attribute' => 'full_name',
                    'header' => 'Full Name',
                    'format' => 'text',
                    'value' => function($model) {
                        return $model->getUser()->one()->fullname;
                    },
                ],
                [
                    'attribute' => 'email',
                    'header' => 'Email',
                    'format' => 'text',
                    'value' => function($model) {
                        return $model->getUser()->one()->email;
                    },
                ],
                [
                    'attribute' => 'mobile',
                    'header' => 'Contact Number',
                    'format' => 'text',
                    'value' => function($model) {
                        return "(". $model->getUser()->one()->mobile_code . ")" .  $model->getUser()->one()->mobile;
                    },
                ],
                [
                    'attribute' => 'status',
                    'header' => 'Status',
                    'format' => 'text',
                    'value' => function($model) {
                        return $model->status();
                    },
                ],
                [
                    'attribute' => 'created_at',
                    'header' => 'Expiry Date',
                    'format' => 'text',
                    'value' => function($model){
                        return $model->getUser()->one()->mem_expiry();
                    }
                ],
            ],
        ]);
    }

    public function actionLog()
    {
        $account = Yii::$app->user->identity; 

        $field   = Yii::$app->request->get('f');
        $user_id = Yii::$app->request->get('u');
        $size    = Yii::$app->request->get('size', 'medium');

        $user = User::findOne($user_id);

        if (!$user OR !array_key_exists($field, $user->attributes)) {
            throw new \yii\web\HttpException(404, 'File not found.');
        } elseif(Common::isClub() AND $user->account_id != $account->account_id) {
            throw new \yii\web\HttpException(404, 'File not found.');
        }

        if (!in_array($size, ['small', 'medium', 'large'])){
            throw new \yii\web\HttpException(404, 'Invalid size.');
        }

        try{

            $dir = Yii::$app->params['dir_member'];
            $subDir = $dir . "{$size}/";

            if (!file_exists($subDir)) FileHelper::createDirectory($subDir);

            if (in_array($field, ['img_profile', 'img_vendor', 'company_logo','club_logo','brand_guide']) AND empty($user->{$field})) $user->{$field} = 'default-profile.png';

            // $filename = $user->{$field};
            $filename = Yii::$app->request->get('t');

            $mimeType = mime_content_type($dir . $filename);

            $info = getimagesize ($dir . $filename);

            if ($info AND preg_match("/image/", $mimeType)) {
                $originalPath = $dir . $filename;
                $thumbPath    = $subDir . $filename;

                $mimeType = mime_content_type($originalPath);

                if (!file_exists($thumbPath)) {
                    if ($size == 'small') {
                        Image::resize($originalPath, 100, 100)->save($thumbPath, ['quality' => 100]);;
                    } elseif ($size == 'medium'){
                        Image::resize($originalPath, 600, 600)->save($thumbPath, ['quality' => 100]);;
                    } else {
                        Image::resize($originalPath, 1024, 1024)->save($thumbPath, ['quality' => 100]);;
                    }
                }

                Yii::$app->response->sendFile($thumbPath, NULL, ['inline' => TRUE]);
            }elseif(preg_match("/image/", $mimeType)){
                Yii::$app->response->sendFile('../../default_file.png', NULL, ['inline' => TRUE]);
            } else {
                Yii::$app->response->sendFile($dir . $filename, NULL, ['inline' => TRUE]);
            }
        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }

    public function actionInsertData()
    {
        // only superadmin
        // 1 time only
        $account = Yii::$app->user->identity; 
        $users = User::find();
        $users->where([
            'account_id' => $account->account_id
        ]);
        $users = $users->all();
        foreach($users as $user){
            if(!is_null($user->img_log_card)){
                $user_log = [
                    'user_id' => $user->user_id,
                    'type' => 1, //member
                    'log_card' => $user->img_log_card,
                ];
                UserLog::create($user_log);
            }

            foreach($user->getRenewals()->all() as $key => $renewal){
                if(!is_null($renewal->log_card)){

                    $user_log = [
                        'user_id' => $user->user_id,
                        'renewal_id' => $renewal->id,
                        'type' => 2, //renewal
                        'log_card' => $renewal->log_card,
                    ];
                    UserLog::create($user_log);
                }
            }
        }

        return 'done';
        
    }
}