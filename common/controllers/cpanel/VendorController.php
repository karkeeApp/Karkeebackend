<?php
namespace common\controllers\cpanel;

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

use common\helpers\Common;
use common\helpers\HRHelper;
use common\helpers\UserHelper;

use yii\imagine\Image;
use yii\helpers\FileHelper;

class VendorController extends Controller
{
    public function actionIndex()
    {
        $data['menu'] = $this->menu;

        Yii::$app->view->on(View::EVENT_END_BODY, function () {
            global $data;

            $data['adminRoleForm'] = new AdminRoleForm;

            echo $this->renderPartial('@common/views/vendor/modals.tpl', $data);  
        });

        return $this->render('@common/views/vendor/index.tpl', $data);
    }

    public function actionAddVendor()
    {
        $data['menu'] = $this->menu;
        $data['staff_menu'] = '';

        $data['userForm'] = new UserForm(['scenario' => 'account_add_vendor']);

        return $this->render('@common/views/vendor/vendor_form.tpl', $data);        
    }

    public function actionEditVendor($id)
    {
        $data['menu'] = $this->menu;
        $data['staff_menu'] = '';

        $data = $this->_getUser($id);

        $data['menu'] = $this->renderPartial('@common/views/vendor/member_menu.tpl', $data);

        $data['userForm'] = new UserForm(['scenario' => 'account_edit_vendor']);
        $data['userForm']->setAttributes($data['user']->attributes, FALSE);

        return $this->render('@common/views/vendor/vendor_form.tpl', $data);        
    }

    private function _getUser($id)
    {
        $data['user'] = User::findOne($id);
        $data['account'] = Yii::$app->user->identity;

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

        $data['menu'] = $this->renderPartial('@common/views/vendor/member_menu.tpl', $data);

        $data['age']      = DateLib::getAge($data['user']->birthday);
        $data['birthday'] = DateLib::dateFormat($data['user']->birthday);

        $data['userForm'] = new UserForm;
        $data['userForm']->setAttributes($data['user']->attributes, FALSE);

        return $this->render('@common/views/vendor/view.tpl', $data);
    }

    public function actionSummary($id=0)
    {
        $data = $this->_getUser($id);

        if (Common::isStaff()) {
            $data['menu'] = $this->renderPartial('summary_menu.tpl');
        } else {
            $data['menu'] = $this->renderPartial('@common/views/vendor/member_menu.tpl', $data);
        }

        return $this->render('@common/views/vendor/summary.tpl', $data);
    }

    public function actionEdit($id=0)
    {
        global $data;

        $data = $this->_getUser($id);

        $data['menu'] = $this->renderPartial('@common/views/vendor/member_menu.tpl', $data);

        

        $data['userForm'] = new UserForm(['scenario' => 'admin_edit_member']);
        $data['userForm']->setAttributes($data['user']->attributes, FALSE);

        return $this->render('@common/views/vendor/form.tpl', $data);  
    }

    public function actionSettings($id=0)
    {
        $data = $this->_getUser($id);

        $data['menu'] = $this->menu;
        $data['member_menu'] = $this->renderPartial('@common/views/vendor/member_menu.tpl', $data);

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

        return $this->render('@common/views/vendor/settings.tpl', $data);
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

            if (in_array($field, ['img_profile', 'img_vendor','company_logo','club_logo','brand_guide']) AND empty($user->{$field})) $user->{$field} = 'default-profile.png';

            $filename = $user->{$field};

            $info = getimagesize ($dir . $filename);

            if ($info) {
                $originalPath = $dir . $filename;
                $thumbPath    = $subDir . $filename;

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
            } else {
                Yii::$app->response->sendFile('../../default_file.png', NULL, ['inline' => TRUE]);
            }
        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }

    public function actionItems($id)
    {
        $data = $this->_getUser($id);

        $data['menu'] = $this->renderPartial('@common/views/vendor/vendor_menu.tpl', $data);

        return $this->render('@common/views/vendor/item_list.tpl', $data);        
    }

    public function actionDownload()
    {
        $memberQry = Yii::$app->session['vendorQry'];

        $file = 'Vendors-' . time();

        \moonland\phpexcel\Excel::export([
            'models' => $memberQry->all(),
            'setFirstTitle' => 'Vendors',
            'asAttachment'  => true,
            'autoSize'      => true,
            'columns' => [
                'user_id',
                'fullname',
                'email',
                'company',
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
}