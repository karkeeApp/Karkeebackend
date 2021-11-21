<?php
namespace common\controllers\server;

use Yii;

use yii\base\BaseObject;
use yii\web\View;
use yii\web\UploadedFile;

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use common\lib\Helper;
use common\forms\UserForm;
use common\forms\FileForm;
use common\forms\EducationForm;

use common\models\User;
use common\models\Renewal;
use common\models\Item;

use common\helpers\Common;
use common\helpers\HRHelper;
//use common\helpers\Helper;
use common\helpers\MyActiveDataProvider;
use common\helpers\Sort;
use common\lib\Helper as LibHelper;
use common\lib\PaginationLib;
use common\models\MemberExpiry;
use common\models\Settings;


class MemberController extends Controller
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

    // public function actionList()
    // {
    //     $account_id        = Yii::$app->request->post('account_id',NULL);
    //     $type              = Yii::$app->request->post('type',NULL);
    //     $keyword           = Yii::$app->request->post('keyword', NULL);
    //     $status            = Yii::$app->request->post('status', NULL);
        
    //     $_GET['hashUrl'] = 'member#list/filter/';

    //     foreach(['account_id','type','status', 'keyword', 'sort', 'page'] as $key) {
    //         $_GET[$key] = Yii::$app->request->post($key);
    //     }

    //     if (empty($_GET['sort'])) $_GET['sort'] = '-id';

    //     $data['sort'] = new Sort([
    //         'attributes' => [
    //             'fullname' => [
    //                 'desc'    => ['fullname' => SORT_DESC],
    //                 'asc'     => ['fullname' => SORT_ASC],
    //                 'default' => SORT_DESC,
    //                 'label'   => 'Full Name',
    //             ],
    //             'id' => [
    //                 'desc'    => ['user_id' => SORT_DESC],
    //                 'asc'     => ['user_id' => SORT_ASC],
    //                 'default' => SORT_DESC,
    //                 'label'   => 'ID',
    //             ],
    //             'email' => [
    //                 'desc'    => ['email' => SORT_DESC],
    //                 'asc'     => ['email' => SORT_ASC],
    //                 'default' => SORT_DESC,
    //                 'label'   => 'Email',
    //             ],
    //             'status' => [
    //                 'desc'    => ['status' => SORT_DESC],
    //                 'asc'     => ['status' => SORT_ASC],
    //                 'default' => SORT_DESC,
    //                 'label'   => 'Status',
    //             ],
    //         ],
    //         'defaultOrder' => ['id' => SORT_DESC],
    //     ]);

    //     $data['sort']->route = 'member#list/filter';

    //     if (Common::isClub()) {
    //         $qry = Common::findUser();    
    //         $qry->andWhere(['member_type' => User::USER_TYPE_MEMBER]);            
    //     } else {
    //         $qry = User::find()->where('1=1');

    //         if (is_numeric($account_id)) {
    //             /**
    //              * Filter by club member
    //              */
    //             $qry->andWhere(['account_id' => $account_id]);    
    //         }

    //         // if (Common::isClub()) $qry->andWhere(['member_type' => User::USER_TYPE_MEMBER]);
    //         // else $qry->andWhere(['member_type' => User::TYPE_CARKEE_MEMBER]);

    //         if (is_numeric($type)) $qry->andWhere(['member_type' => $type]);
    //     }

    //     if (isset($keyword)) {
    //         $qry->andFilterWhere([
    //             'or', 
    //             ['LIKE', 'fullname', $keyword],
    //             ['LIKE', 'vendor_name', $keyword],
    //             ['LIKE', 'email', $keyword],
    //             ['LIKE', 'mobile', $keyword],
    //         ]);
    //     }

    //     // if (is_numeric($type)) $qry->andWhere([(Common::isClub() ? 'member_type' : 'carkee_member_type') => $type]);
    //     if (is_numeric($status)) $qry->andWhere(['status' => $status]);
                
    //     $qry->orderBy($data['sort']->orders);

    //     Yii::$app->session['memberQry'] = $qry;
        
    //     $data['dataProvider'] = new MyActiveDataProvider([
    //         'query' => $qry,
    //         'pagination' => [
    //             'pageSize' => 10,
    //         ],
    //     ]);
    //     $data['type'] = $type;
    //     $data['account_id'] = $account_id;
    //     $tpl = (Common::isClub()) ? '_ajax_list.tpl' : '_ajax_list_carkee_member.tpl';            

    //     return [
    //         'success' => TRUE,
    //         'content' => $this->renderPartial('@common/views/member/' . $tpl, $data),
    //     ];
    // }

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
            $qry = Common::findUser($account_id);    
            $qry->andWhere(['member_type' => User::USER_TYPE_MEMBER]);
            // $qry->andWhere(['NOT IN', 'status', [User::STATUS_DELETED]]);
            // $qry->andWhere(['NOT IN', 'status', [User::STATUS_PENDING]]);
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
            'content' => $this->renderPartial('@common/views/member/' . $tpl, $data),
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
            'content' => $this->renderPartial('@common/views/member/_ajax_item_list.tpl', $data),
        ];
    }

    public function actionRenewalList()
    {
        $account_id        = Yii::$app->request->post('account_id', NULL);
        $keyword           = Yii::$app->request->post('keyword', NULL);
        $status            = Yii::$app->request->post('status', NULL);
        
        $_GET['hashUrl'] = '#list/filter/';

        foreach(['status', 'keyword', 'sort', 'page'] as $key) {
            $_GET[$key] = Yii::$app->request->post($key);
        }

        if (empty($_GET['sort'])) $_GET['sort'] = '-id';

        $data['sort'] = new Sort([
            'attributes' => [
                'fullname' => [
                    'desc'    => ['user.fullname' => SORT_DESC],
                    'asc'     => ['user.fullname' => SORT_ASC],
                    'default' => SORT_DESC,
                    'label'   => 'Full Name',
                ],
                'id' => [
                    'desc'    => ['id' => SORT_DESC],
                    'asc'     => ['id' => SORT_ASC],
                    'default' => SORT_DESC,
                    'label'   => 'ID',
                ],
                'email' => [
                    'desc'    => ['user.email' => SORT_DESC],
                    'asc'     => ['user.email' => SORT_ASC],
                    'default' => SORT_DESC,
                    'label'   => 'Email',
                ],
                'status' => [
                    'desc'    => ['renewal.status' => SORT_DESC],
                    'asc'     => ['renewal.status' => SORT_ASC],
                    'default' => SORT_DESC,
                    'label'   => 'Status',
                ],
            ],
            'defaultOrder' => ['id' => SORT_DESC],
        ]);

        $data['sort']->route = 'member#list/filter';

        $qry = Renewal::find()
            ->innerJoin('user', 'user.user_id = renewal.user_id')
            ->where(['renewal.account_id' => $account_id]);

        if (isset($keyword)) {
            $qry->andFilterWhere([
                'or', 
                ['LIKE', 'user.fullname', $keyword],
                ['LIKE', 'user.email', $keyword],
            ]);
        }

        if (is_numeric($status)) $qry->andWhere(['renewal.status' => $status]);
                
        $qry->orderBy($data['sort']->orders);
        
        $data['dataProvider'] = new MyActiveDataProvider([
            'query' => $qry,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return [
            'success' => TRUE,
            'content' => $this->renderPartial('@common/views/member/_ajax_list_renewal.tpl', $data),
        ];
    }

    // public function actionItemlist($id=0)
    // {
    //     $cpage = Yii::$app->request->post('page', 1);
    //     $keyword = Yii::$app->request->post('keyword', NULL);
    //     $status = Yii::$app->request->post('status', NULL);
    //     $account_id        = Yii::$app->request->post('account_id',NULL);
    //     $type              = Yii::$app->request->post('type',NULL);
    //     // $column_orders_obj = Yii::$app->request->post('column_orders', NULL);
    //     $filter = Yii::$app->request->post('filter', NULL);

    //     $data['user']    = User::findOne($id);
    //     $data['account'] = Yii::$app->user->identity;

    //     extract($data);

    //     if (!$user OR $user->account_id != $account->account_id) {
    //         return [
    //             'success' => FALSE,
    //             'error' => 'User not found.'
    //         ];
    //     }

    //     $qry = Item::find()->where(['user_id' => $user->user_id]);

    //     if (isset($keyword)) {
    //         $qry->andFilterWhere([
    //             'or', 
    //             ['LIKE', 'title', $keyword],
    //         ]);
    //     }

    //     if (is_numeric($status)) {
    //         $qry->andWhere(['status' => $status]);
    //     }
    //     // if(!empty($column_orders_obj)){  
    //     //     $column_orders = json_decode($column_orders_obj);
    //     //     $col_orders = []; 
            
    //     //     foreach($column_orders as $col_key => $col_value) $col_orders = [$col_key => ($col_value == 'desc'? SORT_DESC : SORT_ASC)];                
            
    //     //     $qry->orderBy($col_orders);
    //     // }
    //     $total = $qry->count();

    //     $page = new PaginationLib( $total, $cpage, 10);
    //     $page->url = '#list';

    //     $data['items'] = $qry->limit($page->limit())->offset($page->offset())->all();
    //     $data['page'] = $page;
    //     $data['type'] = $type;
    //     $data['account_id'] = $account_id;
    //     return [
    //         'success' => TRUE,
    //         'content' => $this->renderPartial('@common/views/member/_ajax_item_list.tpl', $data),
    //     ];
    // }

    public function actionUpdate()
    {
        $action = Yii::$app->request->post('action');
        $user_id = Yii::$app->request->post('user_id');
        // dd(Yii::$app->request->post());
        $params_data = Yii::$app->request->post()['UserForm'];
        
        $form = Common::form("common\\forms\\UserForm");
        $form->load(Yii::$app->request->post());
        // dd($form);
        

        // dd($_FILES);
        $errors = [];
        // $account_id = Common::identifyAccountID();

        // if (Common::isCpanel()) {
        //     $user = User::find()->where(['user_id' => $user_id])->andWhere(['account_id' => 0])->one();
        // } else if(!empty($user_id)) {
            
        //     $user = User::find()->where(['user_id' => $user_id])->andWhere(['account_id' => $account_id])->one();
        // } else {
        //     $user = Yii::$app->user->getIdentity();
        // }

        $account = Yii::$app->user->getIdentity();

        if (Common::isCpanel()) {
            $user = User::findOne($user_id);
        } elseif(Common::isClub()) {
            $user = Common::clubUser($user_id);
        } else {
            $user = Yii::$app->user->getIdentity();
        }

        /**
         * Only owner of user is allowed to update
         */

        if (!$user) {
            return  [
                'success' => FALSE,
                'error' => 'User not found.',
            ];
        } elseif($user->account_id != $account->account_id) {
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
            $transaction = Yii::$app->db->beginTransaction();

            try { 
                /**
                 * Save user
                 */
                // $fields = ['fullname', 'nric', 'birthday', 'gender', 'profession', 'company', 'annual_salary', 'about', 'country', 'postal_code', 'add_1', 'add_2', 'unit_no', 'contact_person', 'emergency_no', 'emergency_code', 'relationship'];
                
                $excludeFields = ['user_id','action','_csrf-common' ];
                $fields = LibHelper::getFieldKeys($params_data, $excludeFields);
                
                foreach($fields as $field){
                    $user->{$field} = $form->{$field};
                }
                $uploadFile = UploadedFile::getInstance($form, 'transfer_screenshot');
                
                
                if(!is_null($uploadFile)){
                    $filename = hash('crc32', $uploadFile->name) . time() .time() . '.' . $uploadFile->getExtension();
                    $renewal = $user->getRenewals()->one();
                    if(!is_null($renewal)){
                        $fileDestination = Yii::$app->params['dir_renewal'] . $filename;
                        if ($uploadFile->saveAs($fileDestination)) {
                            $renewal->filename = $filename;
                            $renewal->save();
                        }
                    } else{
                        $fileDestination = Yii::$app->params['dir_member'] . $filename;
                        if ($uploadFile->saveAs($fileDestination)) {
                            $user->transfer_screenshot = $filename;
                        }
                    }
                }
                $user->save();
                $transaction->commit();
                return [
                    'success' => TRUE,
                    'message' => 'Successfully updated.',
                ];
            } catch (\Exception $e) {
                $transaction->rollBack();
    
                return [
                    'success' => TRUE,
                    'error' => $e->getMessage(),
                ];
            } 
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
                //$user->carkee_member_type = $form->carkee_member_type;
                $user->carkee_level       = $form->carkee_level;
                $user->role               = User::ROLE_SPONSORSHIP;
            } else {
                //$user->member_type = $form->member_type;
                $user->level       = $form->level;
                $user->role        = User::ROLE_SPONSORSHIP;
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

    public function actionRenewalApprove()
    {
        $account = Yii::$app->user->getIdentity();
        $id = Yii::$app->request->post('id');
        // dd($id);
        $renewal = Renewal::findOne($id);

        if (!$renewal OR $renewal->account_id != $account->account_id){
            return [
                'success' => FALSE,
                'error'   => 'Record is not found.',
            ];
        }

        if ($renewal->isApproved()) {
            return [
                'success' => FALSE,
                'error'   => 'Already approved.',
            ];
        }elseif (!$renewal->isPending()) {
            return [
                'success' => FALSE,
                'error'   => 'Record is no longer pending.',
            ];
        }

        $renewal->updated_by = $account->user_id;
        $renewal->status     = Renewal::STATUS_APPROVED;
        
        if($renewal->user->account_id == 9 || $renewal->user->account_id == 2){
            $year = date('Y');
            // dd($year);
            $next_jan = "$year" . "-01-31";
            // dd($next_jan);
            $renewal->user->member_expire = date('Y-m-d',strtotime('+1 year', strtotime($next_jan) ));
            // dd($test);
        }  else{
            $renewal->user->member_expire = date('Y-m-d', strtotime('+1 year', strtotime(date('Y-m-d'))));
        }
        $renewal->user->approved_at = date('Y-m-d H:i:s');
        $renewal->user->status = User::STATUS_APPROVED;
        $renewal->user->save();

        $member_expiry = MemberExpiry::find()->where(['renewal_id' => $renewal->id])->one();
        if($member_expiry){
            $member_expiry->status = MemberExpiry::STATUS_PAID;
            $member_expiry->save();

            $renewal->expiry_id = $member_expiry->id;
        }
        
        $renewal->save();
        
        return [
            'success' => TRUE,
            'message'   => 'Successfully approved.',
        ];
    }

    public function actionRenewalReject()
    {
        $account = Yii::$app->user->getIdentity();
        $id = Yii::$app->request->post('id');

        $renewal = Renewal::findOne($id);

        if (!$renewal OR $renewal->account_id != $account->account_id){
            return [
                'success' => FALSE,
                'error'   => 'Record is not found.',
            ];
        }

        if ($renewal->isApproved()) {
            return [
                'success' => FALSE,
                'error'   => 'Already approved.',
            ];
        }elseif (!$renewal->isPending()) {
            return [
                'success' => FALSE,
                'error'   => 'Record is no longer pending.',
            ];
        }

        $renewal->updated_by = $account->user_id;
        $renewal->status     = Renewal::STATUS_REJECTED;
        $renewal->save();

        $renewal->user->tatus= User::STATUS_REJECTED;
        $renewal->user->save();

        return [
            'success' => TRUE,
            'message'   => 'Successfully rejected.',
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
                'message'   => "Can't Update Status! You don't have the required permission to apply changes.",
                'error'   => "Can't Update Status! You don't have the required permission to apply changes."
            ];
        }
        
        $message = null;
        $settings = Settings::find()->one();

        if(($user->user_settings AND $user->user_settings->is_one_approval > 0) OR ($account->account AND $account->account->is_one_approval) OR $settings->is_one_approval){
            if (!$user->approved_by) {
                $user->approved_by = $account->user_id;  
                $user->confirmed_by = $account->user_id;  
                $message = "Successfully Approved! Member's Account Details";
            }
        }else{
            if(!$user->confirmed_by AND $user->confirmed_by != $account->user_id) {
                $user->confirmed_by = $account->user_id;        
                $message = "Successfully Confirmed! Member's Account Details";
            }else if (!$user->approved_by AND ($user->confirmed_by != $account->user_id AND $user->approved_by != $account->user_id)) {
                $user->approved_by = $account->user_id;  
                $message = "Successfully Approved! Member's Account Details";
            }
        }

        if ($user->approved_by AND $user->confirmed_by){
            $user->status      = User::STATUS_APPROVED;
            $user->approved_at = date('Y-m-d H:i:s');


            $title = "One (1) membership registration had been approved with ID #: ".$user->user_id;
            $desc = strtoupper(($account->account_id > 0 ? $account->account->company : "Karkee"))."'s Member ".($user->fullname ? $user->fullname : $user->firstname)." request for registration is now approved";
            LibHelper::pushNotificationFCM_ToMemberDirector($title, $desc, ($account->account_id > 0 ? $account->account->company : "Karkee"), $account->account_id);
                
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
            'message'   => "Unable to Approve Member's Account Details"
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

        $title = "One (1) membership registration had been rejected with ID #: ".$user->user_id;
        $desc = strtoupper(($account->account_id > 0 ? $account->account->company : "Karkee"))."'s Member ".($user->fullname ? $user->fullname : $user->firstname)."'s request for registration is rejected!";
        LibHelper::pushNotificationFCM_ToMemberDirector($title, $desc, ($account->account_id > 0 ? $account->account->company : "Karkee"), $account->account_id);

        return [
            'success' => TRUE,
            'message'   => 'User was successfully rejected.',
        ];
    }

    public function actionAddVendor()
    {
        $admin = Yii::$app->user->getIdentity();
        $account = $admin->account;

        $form = Common::form("common\\forms\\UserForm");
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
            $user->member_type = User::TYPE_VENDOR;
            $user->account_id  = $admin->account_id;
            $user->step        = 7;

            $fields = ['email', 'vendor_name', 'vendor_description', 'country', 'postal_code', 'unit_no', 'add_1', 'telephone_code', 'telephone_no', 'status', 'mobile_code', 'mobile', 'about', 'founded_date'];

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

    public function actionEditVendor()
    {
        $admin = Yii::$app->user->getIdentity();
        $user_id = Yii::$app->request->post('user_id');
        $account = $admin->account;

        $user = User::findOne($user_id);

        if (!$user OR $user->account_id != $account->account_id){
            return  [
                'success' => FALSE,
                'error' => 'User not found.',
            ];
        }

        $form = Common::form("common\\forms\\UserForm");
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
            $fields = ['email', 'vendor_name', 'vendor_description', 'country', 'postal_code', 'unit_no', 'add_1', 'telephone_code', 'telephone_no', 'status', 'mobile_code', 'mobile', 'about', 'founded_date'];

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

    public function actionSetExpiry()
    {
        $account = Yii::$app->user->getIdentity();
        $user_id = Yii::$app->request->post('user_id');
        $member_expiry = Yii::$app->request->post('member_expiry');

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

        $user->member_expire = $member_expiry;
        $user->save();

        return [
            'success' => TRUE,
            'message'   => 'Member Expiry Successfully Set!',
        ];
    }



    public function actionPendingapproval()
    {
        $user    = Yii::$app->user->identity;
        // $data['page']   = Yii::$app->request->post('page', 1);
        $data['keyword'] = Yii::$app->request->post('keyword', NULL);
        // $data['filter']  = Yii::$app->request->post('filter', NULL);

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

        $qry = User::find()->where(['account_id' => Common::isClub() ? $user->account_id : 0]);

        $qry->andWhere(['status' => User::STATUS_PENDING]);

        if (isset($data['keyword'])) {
            $qry->andFilterWhere([
                'or',
                ['LIKE', 'title', $data['keyword']],
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
            'content' => $this->renderPartial('@common/views/member/_ajax_list_pending.tpl', $data),
        ];
    }

    public function actionDeleted()
    {
        $user    = Yii::$app->user->identity;
        // $data['page']   = Yii::$app->request->post('page', 1);
        $data['keyword'] = Yii::$app->request->post('keyword', NULL);
        // $data['filter']  = Yii::$app->request->post('filter', NULL);

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

        $qry = User::find()->where(['account_id' => Common::isClub() ? $user->account_id : 0]);

        $qry->andWhere(['status' => User::STATUS_DELETED]);
        $qry->andWhere(['member_type' => User::USER_TYPE_MEMBER]);

        if (isset($data['keyword'])) {
            $qry->andFilterWhere([
                'or',
                ['LIKE', 'title', $data['keyword']],
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
            'content' => $this->renderPartial('@common/views/member/_ajax_deleted_list.tpl', $data),
        ];
    }



    public function actionEditDocs($id)
    {      
        $user = User::findOne($id);
        $params_data = Yii::$app->request->post();
        
        $form = new UserForm(['scenario'=>'edit_documents']);
        $form->load($params_data);
 
        $account = Yii::$app->user->getIdentity();

        if (Common::isCpanel()) {
            $user = User::findOne($id);
        } elseif(Common::isClub()) {
            $user = Common::clubUser($id);
        } else {
            $user = Yii::$app->user->getIdentity();
        }
    
        if (!$user) {
            return  [
                'success' => FALSE,
                'error' => 'User not found.',
            ];
        }
        
        $form->img_nric = UploadedFile::getInstance($form,'img_nric');
        $form->img_insurance = UploadedFile::getInstance($form,'img_insurance');
        $form->img_authorization = UploadedFile::getInstance($form,'img_authorization');
        $form->img_log_card = UploadedFile::getInstance($form,'img_log_card');

        // $img_nric = UploadedFile::getInstances($form,'img_nric');
        // $img_insurance = UploadedFile::getInstances($form,'img_insurance');
        // $img_authorization = UploadedFile::getInstances($form,'img_authorization');
        // $img_log_card = UploadedFile::getInstances($form,'img_log_card');

        // $form->img_nric = $img_nric[0];
        // $form->img_insurance = $img_insurance[0];
        // $form->img_authorization = $img_authorization[0];
        // $form->img_log_card = $img_log_card[0];

        if (!$form->validate()){
            $error = self::getFirstError(ActiveForm::validate($form));
            return Helper::errorMessage($error['message'], true);
        }

        $transaction = Yii::$app->db->beginTransaction();


        try {
            $dir = Yii::$app->params['dir_member'];
            // foreach ($uploadedFiles[$img_nric] as $key => $file) {
            if(!empty($form->img_nric)){
                $filename_noext = date('Ymd') . '_' . time() . "_{$form->img_nric->name}". '.' . $form->img_nric->extension;

                $form->img_nric->saveAs($dir . $filename_noext);                   
            }
            
            if(!empty($form->img_insurance)){
                $filename_noext = date('Ymd') . '_' . time() . "_{$form->img_insurance->name}". '.' . $form->img_insurance->extension;

                $form->img_insurance->saveAs($dir . $filename_noext);                   
            }
            
            if(!empty($form->img_authorization)){
                $filename_noext = date('Ymd') . '_' . time() . "_{$form->img_authorization->name}". '.' . $form->img_authorization->extension;

                $form->img_authorization->saveAs($dir . $filename_noext);                   
            }
            if(!empty($form->img_log_card)){
                $filename_noext = date('Ymd') . '_' . time() . "_{$form->img_log_card->name}". '.' . $form->img_log_card->extension;

                $form->img_log_card->saveAs($dir . $filename_noext);                   
            }
            
            if (!empty($form->img_log_card) && !empty($form->img_authorization) && !empty($form->img_insurance) && !empty($form->img_nric)) {
                
                $user->img_nric = $form->img_nric;
                $user->img_insurance = $form->img_insurance;
                $user->img_authorization = $form->img_authorization;
                $user->img_log_card = $form->img_log_card;
                
                $user->save();
            }
            if($form->img_log_card){
                $filename_noext = date('Ymd') . '_' . time() . "_{$form->img_log_card->name}". '.' . $form->img_log_card->extension;

                $form->img_log_card->saveAs($dir . $filename_noext);                   
            }

            

            $transaction->commit();

            return [
                'success' => TRUE,
                'message' => 'Successfully Created User Documents.',
                'data'    => $user->data()
            ];
           
        } catch (\Exception $e) {
            $transaction->rollBack();
            return [
                'code'    => self::CODE_ERROR,   
                'message' => $e->getMessage(),
            ];
        }
    }


    public function actionDeleteNric()
    {
        $account = Yii::$app->user->identity;
        $id    = Yii::$app->request->post('id');

        $user = User::findOne($id);
        $user->img_nric = 'NULL';
        @unlink(Yii::$app->params['dir_member'].$user->img_nric());
                
        $user->save();

        return [
            'success' => TRUE,
            'message' => 'Successfully deleted.',
        ];
    }
    
    public function actionDeleteInsImg()
    {
        $account = Yii::$app->user->identity;
        $id    = Yii::$app->request->post('id');

        $user = User::findOne($id);
        $user->img_insurance = 'NULL';
        @unlink(Yii::$app->params['dir_member'].$user->img_insurance());
                
        $user->save();

        return [
            'success' => TRUE,
            'message' => 'Successfully deleted.',
        ];
    }


    public function actionDeleteAuthImg()
    {
        $account = Yii::$app->user->identity;
        $id    = Yii::$app->request->post('id');

        $user = User::findOne($id);
        $user->img_authorization = 'NULL';
        @unlink(Yii::$app->params['dir_member'].$user->img_authorization());
                
        $user->save();

        return [
            'success' => TRUE,
            'message' => 'Successfully deleted.',
        ];
    }


    public function actionDeleteLogCard()
    {
        $account = Yii::$app->user->identity;
        $id    = Yii::$app->request->post('id');

        $user = User::findOne($id);
        $user->img_log_card = 'NULL';
        @unlink(Yii::$app->params['dir_member'].$user->img_log_card());
                
        $user->save();

        return [
            'success' => TRUE,
            'message' => 'Successfully deleted.',
        ];
    }




}
