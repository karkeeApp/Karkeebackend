<?php
namespace cpanel\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use common\models\User;
use common\lib\DateLib;

use common\forms\UserForm;
use common\forms\CreditLimitForm;
use common\forms\PasswordForm;
use common\forms\UserSettingsForm;
use common\forms\EmailForm;
use common\forms\MobileForm;
use common\forms\MapSettingsForm;

class VendorController extends \common\controllers\cpanel\VendorController
{
	public $menu;

    public function behaviors()
    {
        $this->menu = $this->renderPartial('menu.tpl');

        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => parent::userActions(),
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
        
//        return [
//            'access' => [
//                'class' => AccessControl::class,
//                'rules' => [
//                    [
//                        'actions' => ['index', 'add', 'edit', 'view', 'settings'],
//                        'allow' => true,
//                        'roles' => ['@'],
//                    ],
//                ],
//            ],
//        ];
    }

//    public function actionIndex()
//    {
//        $data['menu'] = $this->menu;
//
//        return $this->render('list.tpl', $data);
//    }
//
//     private function _getUser($id)
//     {
//         $data['user'] = User::findOne($id);
//         $data['admin'] = Yii::$app->user->identity;
//
//         extract($data);
//
//         if (!$user OR !$user->isCarkeeVendor()) {
//             throw new \yii\web\HttpException(404, 'User not found.');
//             return;
//         }
//
//         return $data;
//     }
//
//     public function actionView($id=0)
//     {
//         $data = $this->_getUser($id);
//
//         $data['menu'] = $this->renderPartial('/vendor/vendor_menu.tpl', $data);
//
//         $data['age']      = DateLib::getAge($data['user']->birthday);
//         $data['birthday'] = DateLib::dateFormat($data['user']->birthday);
//
//         $data['userForm'] = new UserForm;
//         $data['userForm']->setAttributes($data['user']->attributes, FALSE);
//
//         return $this->render('view.tpl', $data);
//     }
//
//     public function actionAdd()
//     {
//         $data['admin'] = Yii::$app->user->getIdentity();
//         $data['menu'] = $this->renderPartial('menu.tpl', $data);
//         $data['userForm'] = new UserForm(['scenario' => 'admin_add_vendor']);
//
//         return $this->render('form.tpl', $data);
//     }
//
//     public function actionEdit($id=0)
//     {
//         $data = $this->_getUser($id);
//
//         $data['admin'] = Yii::$app->user->getIdentity();
//         $data['menu'] = $this->renderPartial('menu.tpl', $data);
//         $data['userForm'] = new UserForm(['scenario' => 'admin_edit_vendor']);
//         $data['userForm']->setAttributes($data['user']->attributes, FALSE);
//
//         return $this->render('form.tpl', $data);
//     }
//
//     public function actionSettings($id=0)
//     {
//         $data = $this->_getUser($id);
//
//         $data['menu'] = $this->menu;
//         $data['member_menu'] = $this->renderPartial('@common/views/member/member_menu.tpl', $data);
//
//         $data['userSettingsForm'] = new UserSettingsForm;
//         $data['userSettingsForm']->setAttributes($data['user']->attributes, FALSE);
//         /**
//          * Password
//          */
//         $data['passwordForm'] = new PasswordForm;
//
//         /**
//          * Email
//          */
//         $data['emailForm'] = new EmailForm;
//         $data['emailForm']->setAttributes($data['user']->attributes, FALSE);
//
//         /**
//          * Mobile
//          */
//         $data['mobileForm'] = new MobileForm;
//         $data['mobileForm']->setAttributes($data['user']->attributes, FALSE);
//
//         /**
//          * Map Coordinates
//          */
//         $data['mapSettingsForm'] = new MapSettingsForm;
//         $data['mapSettingsForm']->setAttributes($data['user']->attributes, FALSE);
//
//         return $this->render('@common/views/member/vendor_settings.tpl', $data);
//     }
//
//     public function actionDoc()
//     {
//         $account = Yii::$app->user->identity;
//
//         $field   = Yii::$app->request->get('f');
//         $user_id = Yii::$app->request->get('u');
//
//         $user = User::findOne($user_id);
//
//         if (!$user OR $user->account_id != $account->account_id OR !array_key_exists($field, $user->attributes)) {
//             echo "Invalid file";
//             return;
//         }
//
//         try{
//             $dir = Yii::$app->params['dir_member'];
//
//             Yii::$app->response->sendFile($dir . $user->{$field}, $user->{$field}, ['inline' => TRUE]);
//         } catch(Exception $e) {
//             echo $e->getMessage();
//         }
//     }
//
//     public function actionItems($id)
//     {
//         $data = $this->_getUser($id);
//
//         $data['menu'] = $this->renderPartial('@common/views/member/vendor_menu.tpl', $data);
//
//         return $this->render('@common/views/member/item_list.tpl', $data);
//     }
}