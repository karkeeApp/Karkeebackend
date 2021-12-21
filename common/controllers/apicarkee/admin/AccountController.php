<?php
namespace common\controllers\apicarkee\admin;

use Yii;
use yii\widgets\ActiveForm;
use common\models\Account;
use common\models\Email;

use common\forms\AccountForm;
use common\forms\AccountSecurityQuestionsForm;
use common\forms\AccountSettingsForm;
use common\helpers\Common;
use common\helpers\Helper;
use common\lib\CrudAction;
use common\lib\Helper as LibHelper;
use common\models\AccountMembership;
use common\models\AccountSecurityQuestions;
use common\models\Document;
use common\models\Settings;
use common\models\User;
use Exception;
use yii\data\Pagination;
use yii\web\UploadedFile;

class AccountController extends Controller
{
    public function actionDoc()
    {
        $doc_id   = Yii::$app->request->get('u');
        $document = Document::findOne($doc_id);

        if (!$document) return LibHelper::errorMessage("User is not found",true);

        try{
            $dir = Yii::$app->params['dir_member'];
            $file = $dir . $document->filename;
            if (!file_exists($file)) $document->filename = 'default-profile.png';

            return Yii::$app->response->sendFile($dir . $document->filename, $document->filename, ['inline' => TRUE]);
        } catch(\Exception $e) {
            
            $error = $e->getMessage();
            return LibHelper::errorMessage($error,true);
        }
    }   
    private function accountList(){
        
        $page    = Yii::$app->request->get('page', 1);
        $page_size = Yii::$app->request->get('size',10);

        $club_code= Yii::$app->request->get('club_code',NULL);
        $account_id= Yii::$app->request->get('account_id',NULL);
        $type      = Yii::$app->request->get('type',NULL);
        $keyword   = Yii::$app->request->get('keyword',NULL);
        $status    = Yii::$app->request->get('status',NULL);
        $premium_status = Yii::$app->request->get('premium_status',NULL);
        $club_code = Yii::$app->request->get('club_code',NULL);
        $role      = Yii::$app->request->get('role',NULL);

        $qry = Account::find()->where('1=1');

        if(!is_null($club_code) AND !empty($club_code)) $qry->andWhere(['club_code' => $club_code]);
        if(!is_null($account_id)) $qry->andWhere(['account_id' => $account_id]);
        if(!is_null($status)) $qry->andWhere(['status' => $status]);
        if(!is_null($premium_status)) $qry->andWhere("user_id IN (SELECT user.user_id FROM user WHERE user.premium_status = {$premium_status} AND user.status NOT IN (".User::STATUS_DELETED.",".User::STATUS_REJECTED."))");
        if(!is_null($type)) $qry->andWhere("user_id IN (SELECT user.user_id FROM user WHERE user.member_type = {$type} AND user.status NOT IN (".User::STATUS_DELETED.",".User::STATUS_REJECTED."))");
        if(!is_null($role)) $qry->andWhere("user_id IN (SELECT user.user_id FROM user WHERE user.role = {$role} AND user.status NOT IN (".User::STATUS_DELETED.",".User::STATUS_REJECTED."))");

        if (!is_null($keyword) AND !empty($keyword)) {
            $qry->andFilterWhere([
                'or',
                ['LIKE', 'company', $keyword],
                ['LIKE', 'contact_name', $keyword],
                ['LIKE', 'company_full_name', $keyword],
                ['LIKE', 'email', $keyword],
                ['LIKE', 'address', $keyword]
            ]);
        }

        $totalCount = (int)$qry->count();
        $pages    = new Pagination(['totalCount' => $totalCount, 'defaultPageSize' => $page_size]);        
        $offset   = ($page - 1) * $pages->limit;
        
        $accounts = $qry->orderBy(['account_id' => SORT_DESC])->offset($offset)->limit($pages->limit)->all();
        
        $data = [];

        foreach($accounts as $account){
            $data[] = $account->data();
        }

        return [
            'data'          => $data,
            'current_page'  => $page,
            'page_count'    => ceil($pages->totalCount / $pages->pageSize),
            'current_page_size' => $pages->pageSize,
            'total' => $totalCount,
            'code'  => self::CODE_SUCCESS
        ];
    }

    private function accountMemberList(){
        
        $page    = Yii::$app->request->get('page', 1);
        $page_size = Yii::$app->request->get('size',10);

        $account_id= Yii::$app->request->get('account_id',NULL);
        $type      = Yii::$app->request->get('type',NULL);
        $keyword   = Yii::$app->request->get('keyword',NULL);
        $status    = Yii::$app->request->get('status',NULL);
        $premium_status = Yii::$app->request->get('premium_status');
        $club_code = Yii::$app->request->get('club_code',NULL);
        $role      = Yii::$app->request->get('role',NULL);

        $qry = AccountMembership::find()->where("1=1");

        if(!is_null($club_code) AND !empty($club_code)) $qry->andWhere(['club_code' => $club_code]);
        if(!is_null($account_id)) $qry->andWhere(['account_id' => $account_id]);
        if(!is_null($status)) $qry->andWhere(['status' => $status]);
        if(!is_null($premium_status)) $qry->andWhere("user_id IN (SELECT user.user_id FROM user WHERE user.premium_status = {$premium_status} AND user.status NOT IN (".User::STATUS_DELETED.",".User::STATUS_REJECTED."))");
        if(!is_null($type)) $qry->andWhere("user_id IN (SELECT user.user_id FROM user WHERE user.member_type = {$type} AND user.status NOT IN (".User::STATUS_DELETED.",".User::STATUS_REJECTED."))");
        if(!is_null($role)) $qry->andWhere("user_id IN (SELECT user.user_id FROM user WHERE user.role = {$role} AND user.status NOT IN (".User::STATUS_DELETED.",".User::STATUS_REJECTED."))");

        // if(!is_null($keyword) AND !empty($keyword)){
        //     $qry->andWhere("account_id IN (SELECT account.account_id FROM account WHERE account.company LIKE '%".$keyword."%' OR account.contact_name LIKE '%".$keyword."%' OR account.company_full_name LIKE '%".$keyword."%' OR account.email LIKE '%".$keyword."%' OR account.address LIKE '%".$keyword."%')")
        //         ->andWhere("user_id IN (SELECT user.user_id FROM user WHERE user.fullname LIKE '%".$keyword."%' OR user.email LIKE '%".$keyword."%')");
        // }
        
        $totalCount = (int)$qry->count();
        $pages    = new Pagination(['totalCount' => $totalCount, 'defaultPageSize' => $page_size]);        
        $offset   = ($page - 1) * $pages->limit;
        
        $accountmems = $qry->orderBy(['id' => SORT_DESC])->offset($offset)->limit($pages->limit)->all();
        
        $data = [];
        foreach($accountmems as $accountmem) $data[] = $accountmem->data();
        
        return [
            'data'          => $data,
            'current_page'  => $page,
            'page_count'    => ceil($pages->totalCount / $pages->pageSize),
            'current_page_size' => $pages->pageSize,
            'total' => $totalCount,
            'code'  => self::CODE_SUCCESS
        ];
    }

	public function actionIndex()
    {
        return $this->accountList();
    }

	public function actionListAccountMembership()
    {
        return $this->accountMemberList();
    }

    public function actionList(){
        return $this->accountList();
    }

    public function actionListOptions(){
        $page    = Yii::$app->request->get('page', 1);
        $page_size = Yii::$app->request->get('size',10);

        $qry = Account::find()->where(['status' => Account::STATUS_APPROVED]);

        $totalCount = (int)$qry->count();
        $pages    = new Pagination(['totalCount' => $totalCount, 'defaultPageSize' => $page_size]);        
        $offset   = ($page - 1) * $pages->limit;
        
        $accounts = $qry->orderBy(['account_id' => SORT_DESC])->offset($offset)->limit($pages->limit)->all();

        foreach($accounts as $account){
            $data[] = [
                'account_id' => $account->account_id,
                'company' => $account->company,
                'logo_url' => $account->logoUrl()
            ];
        }

        return [
            'data'          => $data,
            'current_page'  => $page,
            'page_count'    => ceil($pages->totalCount / $pages->pageSize),
            'current_page_size' => $pages->pageSize,
            'total' => $totalCount,
            'code'  => self::CODE_SUCCESS
        ];
    }

    public function actionCreate()
    {
        $admin = Yii::$app->user->getIdentity();
        $form = new AccountForm(['scenario'=>'admin-carkee-add']);
        $form = $this->postLoad($form);
        $form->status = Account::STATUS_PENDING;

        $tmp = [];
        if(!empty($_FILES)){
            foreach($_FILES as $file) {
                $tmp['AccountForm'] = [
                    'name'     => ['file' => $file['name']],
                    'type'     => ['file' => $file['type']],
                    'tmp_name' => ['file' => $file['tmp_name']],
                    'error'    => ['file' => $file['error']],
                    'size'     => ['file' => $file['size']],
                ];
            }

            $_FILES = $tmp;
        }

        if (!empty($_FILES['AccountForm'])) $form->file = UploadedFile::getInstance($form, 'file');
        if (!empty($form->file)) $form->logo = hash('crc32', $form->file->name) . time() . '.' . $form->file->extension;

        if (!$form->validate()){
            $error = self::getFirstError(ActiveForm::validate($form));
            return LibHelper::errorMessage($error['message'], true);
        }

        $transaction = Yii::$app->db->beginTransaction();
        try{
            $form->contact_name = (!empty($form->contact_name) ? $form->contact_name : $admin->fullname);
            $form->email = (!empty($form->email) ? $form->email : $admin->email);
            $account = Account::create($form,$admin->user_id);

            if ($form->logo) $saved_img = LibHelper::saveImage($this, $form->file, $form->logo, Yii::$app->params['dir_member']);
            if (!empty($saved_img) AND !$saved_img['success'])  return $saved_img;

            $transaction->commit();

            return [
                'code' => self::CODE_SUCCESS,
                'success' => TRUE,
                'message' => 'Successfully Created Account',
                'data' => $account->data()
            ];

        }catch(\Exception $e){
            $transaction->rollBack();
            $error = $e->getMessage();
            return LibHelper::errorMessage($error,true);
        }

    }

    public function actionView()
    {
        $admin = Yii::$app->user->identity;
        $admin_account_id = ($admin ? $admin->account_id : NULL);
        $account_id = Yii::$app->request->get('account_id',$admin_account_id);
        
        if(!is_null($account_id)){
            if($account_id >= 1) $account = Account::findOne($account_id);
            else $account = Settings::find()->one();
            
            if (empty($account)) return LibHelper::errorMessage('Account not found',true);
            
            return [
                'success' => TRUE,
                'message' => 'Successfully Retrieved.',
                'data' => $account->data()
            ];
        }
        return LibHelper::errorMessage('Account not found',true);
    }

    public function actionViewAccountMembership()
    {
        $account_mem_id = Yii::$app->request->get('id',null);
        $accountmem = AccountMembership::findOne($account_mem_id);

        if (!$accountmem) return LibHelper::errorMessage('Account Membership not found',true);

        return [
            'success' => TRUE,
            'message' => 'Successfully Retrieved.',
            'data' => $accountmem->data()
        ];
        
    }

    public function actionAccountByClubCode() {

        $club_code = Yii::$app->request->get('code', null);

        $accountclub = Account::find()->where(['club_code'=>$club_code])->one();
        if (!$accountclub) return LibHelper::errorMessage('Account not found',true);

        return [
            'success' => TRUE,
            'message' => 'Successfully Retrieved.',
            'account_id' => $accountclub->account_id
        ];
        
    }

    public function actionQuestionsByClubCode() {

        $club_code = Yii::$app->request->get('code', null);

        $accountclub = Account::find()->where(['club_code'=>$club_code])->one();
        if (!$accountclub) return LibHelper::errorMessage('Invalid club code.',true);

        $accountsecurityquestion = AccountSecurityQuestions::find()->where(['account_id' => $accountclub->account_id]);

        $accountsecurityquestion->andWhere(['NOT IN', 'status', [AccountSecurityQuestions::STATUS_DELETED]]);
        $questions = $accountsecurityquestion->all();

        $data = [];

        foreach($questions as $question){
            $data[] = $question->data();
        }

        return [
            'data'          => $data,
            'code'          => self::CODE_SUCCESS
        ];
    }
        
    public function actionUpdate()
    {
        $admin = Yii::$app->user->getIdentity();

        $account_id = Yii::$app->request->get('account_id',null);
        $account = Account::findOne($account_id);

        if (!$account) return LibHelper::errorMessage('Account not found',true);

        $params_data = Yii::$app->request->post();

        $form = new AccountForm(['scenario'=>'admin-carkee-edit']);
        $form = $this->postLoad($form);
        $form->status = Account::STATUS_PENDING;

        $tmp = [];
        if(!empty($_FILES)){
            foreach($_FILES as $file) {
                $tmp['AccountForm'] = [
                    'name'     => ['file' => $file['name']],
                    'type'     => ['file' => $file['type']],
                    'tmp_name' => ['file' => $file['tmp_name']],
                    'error'    => ['file' => $file['error']],
                    'size'     => ['file' => $file['size']],
                ];
            }

            $_FILES = $tmp;
        }

        if (!empty($_FILES['AccountForm'])) $form->file = UploadedFile::getInstance($form, 'file');
        if (!empty($form->file)) $form->logo = hash('crc32', $form->file->name) . time() . '.' . $form->file->extension;

        if (!$form->validate()){
            $error = self::getFirstError(ActiveForm::validate($form));
            return LibHelper::errorMessage($error['message'],true);
        }

        $transaction = Yii::$app->db->beginTransaction();
        try{
            $excludeFields = ['id', 'file','status'];
            $fields = LibHelper::getFieldKeys($params_data, $excludeFields);

            $response = CrudAction::applyUpdateNew($this, $transaction, $account,$fields,$form);
            if (!$response['success']) return $response;

            if ($form->file) {
                $account->logo = $form->logo;
                $account->save();

                $saved_img = LibHelper::saveImage($this, $form->file, $form->logo, Yii::$app->params['dir_member']);
            }
            if (!empty($saved_img) AND !$saved_img['success']) return $saved_img;

            return [
                'code' => self::CODE_SUCCESS,
                'success' => TRUE,
                'message' => 'Successfully Updated Account',
                'data' => $account->data()
            ];

        }catch(\Exception $e){
            
            $error = $e->getMessage();
            return LibHelper::errorMessage($error,true);
        }
    }

    public function actionApprove()
    {
        $admin = Yii::$app->user->getIdentity();
        $account_id = Yii::$app->request->post('account_id');

        $account = Account::findOne($account_id);

        if (!$account) return LibHelper::errorMessage("Account is not found.",true);

        // if ($account->isApproved() AND $account->isConfirmed()) return LibHelper::errorMessage("User is already approved.",true);
        // else if (!$account->isPending()) return LibHelper::errorMessage("User is not in pending status.",true);
        // else if($account->confirmed_by AND $account->confirmed_by == $admin->user_id) return LibHelper::errorMessage("You already approved this. Please let someone be the checker.",true);
        
        // if(!$admin->isAdministrator()) return LibHelper::errorMessage("Can't Update Account Status! You don't have the required permission to apply changes.",true);
            
        if(!$admin->isAdministrator()) return LibHelper::errorMessage("Can't Update Status! You don't have the required permission to apply changes.",true);

        if($account->isApproved() AND $account->isConfirmed()) return LibHelper::errorMessage('Account is already approved.',true);                
        
        if(!$admin->isRoleSuperAdmin()) if(($account->isConfirmed() OR $account->isPending()) AND $account->account_id != $admin->account_id) return LibHelper::errorMessage("Can only change status by Admins of this Club",true);        
        

        $message = null;
        $settings = Settings::find()->one();

        if($settings->is_one_approval){
            $account->approved_by = $admin->user_id;
            $account->confirmed_by = $admin->user_id;
            $message = "Successfully Approved! Account's Details";
        }else{            
            if(!$account->confirmed_by){
                $account->confirmed_by = $admin->user_id; 
                $message = "Successfully Confirmed! Account's Details";            
            }else if(!$account->approved_by){
                $account->approved_by = $admin->user_id;
                $message = "Successfully Approved! Account's Details";
            }
        }

        if ($account->approved_by AND $account->confirmed_by){
            $account->status      = Account::STATUS_APPROVED;
            $account->approved_at = date('Y-m-d H:i:s');

            // $title = "One (1) membership registration had been approved with ID #: ".$user->user_id;
            // $desc = strtoupper(($account->account_id > 0 ? $account->account->company : "Karkee"))."'s Member ".($user->fullname ? $user->fullname : $user->firstname)." request for registration is now approved";
            // LibHelper::pushNotificationFCM_ToMemberDirector($title, $desc, ($account->account_id > 0 ? $account->account->company : "Karkee"), $account->account_id);
                
        }

        $account->save();

        if($message) 
        {
            return [
                'code' => self::CODE_SUCCESS,
                'success' => TRUE,
                'message'   => $message
            ];
        }

        return LibHelper::errorMessage("Unable to Approve Account's Detail",true);
    }

    public function actionReject()
    {
        $admin = Yii::$app->user->getIdentity();
        $account_id = Yii::$app->request->post('account_id');

        $account = Account::findOne($account_id);

        if (!$account) return LibHelper::errorMessage("Account is not found.",true);
        else if (!$account->isPending()) return LibHelper::errorMessage("Account is no longer pending.",true);

        if(!$admin->isAdministrator()) return LibHelper::errorMessage("Can't Update Account Status! You don't have the required permission to apply changes.",true);
        
        $account->status = Account::STATUS_REJECTED;
        $account->save();

        return [
            'code' => self::CODE_SUCCESS,
            'success' => TRUE,
            'message'   => 'Account is rejected successfully.',
        ];
    }

    public function actionAccountMembershipApprove()
    {
        $admin = Yii::$app->user->getIdentity();
        $membership_id = Yii::$app->request->post('membership_id');

        $accountmem = AccountMembership::findOne($membership_id);

        if (!$accountmem) return LibHelper::errorMessage("Account Membership is not found.",true);

        if ($accountmem->isApproved() AND $accountmem->isConfirmed()) return LibHelper::errorMessage("Membership is already approved.",true);
        else if (!$accountmem->isPending()) return LibHelper::errorMessage("Membership is not in pending status.");
        else if($accountmem->confirmed_by AND $accountmem->confirmed_by == $admin->user_id) return LibHelper::errorMessage("You already approved this. Please let someone be the checker.",true);
        
        if(!$admin->isAdministrator()) return LibHelper::errorMessage("Can't Update Membership Status! You don't have the required permission to apply changes.",true);
            
        $message = null;
        $settings = Settings::find()->one();

        if($settings->is_one_approval){
            $accountmem->approved_by = $admin->user_id;
            $accountmem->confirmed_by = $admin->user_id;
            $message = "Successfully Approved! Membership Details";
        }else{            
            if(!$accountmem->confirmed_by){
                $accountmem->confirmed_by = $admin->user_id; 
                $message = "Successfully Confirmed! Membership Details";            
            }else if(!$accountmem->approved_by){
                $accountmem->approved_by = $admin->user_id;
                $message = "Successfully Approved! Membership Details";
            }
        }

        if ($accountmem->approved_by AND $accountmem->confirmed_by){
            $accountmem->status      = AccountMembership::STATUS_APPROVED;
            $accountmem->approved_at = date('Y-m-d H:i:s');

            // $title = "One (1) membership registration had been approved with ID #: ".$user->user_id;
            // $desc = strtoupper(($account->account_id > 0 ? $account->account->company : "Karkee"))."'s Member ".($user->fullname ? $user->fullname : $user->firstname)." request for registration is now approved";
            // LibHelper::pushNotificationFCM_ToMemberDirector($title, $desc, ($account->account_id > 0 ? $account->account->company : "Karkee"), $account->account_id);
             
            Email::sendSendGridAPI($accountmem->user->email, 'KARKEE - Club Membership', 'club-membership', $params=[
                'name'          => !empty($accountmem->user->fullname) ? $accountmem->user->fullname : $accountmem->user->firstname,
                'status'        => "Approved",
                'client_email'  => $accountmem->user->email,
                'club_email'    => "admin@carkee.sg",
                'club_name'     => "KARKEE",
                'club_link'     => "http://cpanel.carkee.sg",
                'club_logo'     => "http://qa.carkeeapi.carkee.sg/logo-edited.png",
                'api_link'      => (Yii::$app->params['environment'] == 'production' ? Yii::$app->params['api.carkee.endpoint']['prod'] : Yii::$app->params['api.carkee.endpoint']['dev'])
            ]);
       
        }

        $accountmem->save();


        $user = User::findOne($accountmem->user_id);
        if(!empty($user)) $user->cloneModel($accountmem->account_id);
             
        // Yii::info("user","carkee");
        // Yii::info($user,"carkee");
        // Yii::info("accountmem->user_id","carkee");
        // Yii::info($accountmem->user_id,"carkee");
        if($message) 
        {
            return [
                'code' => self::CODE_SUCCESS,
                'success' => TRUE,
                'message'   => $message
            ];
        }

        return LibHelper::errorMessage("Unable to Approve Membership Detail",true);
    }

    public function actionAccountMembershipReject()
    {
        $admin = Yii::$app->user->getIdentity();
        $membership_id = Yii::$app->request->post('membership_id');

        $accountmem = AccountMembership::findOne($membership_id);

        if (!$accountmem) return LibHelper::errorMessage("Account Membership is not found.",true);
        else if (!$accountmem->isPending()) return LibHelper::errorMessage("Account Membership is no longer pending.",true);

        if(!$admin->isAdministrator()) return LibHelper::errorMessage("Can't Update Account Status! You don't have the required permission to apply changes.",true);
        
        $accountmem->status = AccountMembership::STATUS_REJECTED;
        $accountmem->save();

        Email::sendSendGridAPI($accountmem->user->email, 'KARKEE - Club Membership', 'club-membership', $params=[
            'name'          => !empty($accountmem->user->fullname) ? $accountmem->user->fullname : $accountmem->user->firstname,
            'status'        => "Declined",
            'client_email'  => $accountmem->user->email,
            'club_email'    => "admin@carkee.sg",
            'club_name'     => "KARKEE",
            'club_link'     => "http://cpanel.carkee.sg",
            'club_logo'     => "http://qa.carkeeapi.carkee.sg/logo-edited.png",
            'api_link'      => (Yii::$app->params['environment'] == 'production' ? Yii::$app->params['api.carkee.endpoint']['prod'] : Yii::$app->params['api.carkee.endpoint']['dev'])
        ]);

        return [
            'code' => self::CODE_SUCCESS,
            'success' => TRUE,
            'message'   => 'Account Membership is rejected successfully.',
        ];
    }

    public function actionDelete()
    {
        $admin = Yii::$app->user->getIdentity();
        $account_id = Yii::$app->request->post('account_id',null);

        $account = Account::findOne($account_id);

        if (!$account) return LibHelper::errorMessage("Account is not found.",true);

        if(!$admin->isAdministrator()) return LibHelper::errorMessage("Can't Update Account Status! You don't have the required permission to apply changes.",true);
        
        $account->status = Account::STATUS_DELETED;
        $account->save();

        return [
            'code' => self::CODE_SUCCESS,
            'success' => TRUE,
            'message'   => 'Account is deleted successfully.',
        ];
    }

    public function actionSkipMemberApproval(){
        $skip_approval = Yii::$app->request->post('skip_approval',null);
        $account_id = Yii::$app->request->post('account_id',null);
        if(is_null($skip_approval) AND is_null($account_id)) return LibHelper::errorMessage("Please check your details. Can not have both skip_approval and account_id blank",true);
        
        try{
            if(!is_null($skip_approval)){
                if($account_id){
                    $account = Account::find()->where(['account_id'=>$account_id])->one();
                    $account->skip_approval = $skip_approval;
                    $account->save();
                }else{
                    $settings = Settings::find()->one();
                    $settings->skip_approval = $skip_approval;
                    $settings->save();
                }
                $state_mem_approval = $skip_approval == 1 ? 'allowed' : 'not allowed';
                return [
                    'success' => TRUE,
                    'message' => "Successfully {$state_mem_approval} skip membership approval"
                ];
            }

            return LibHelper::errorMessage("Unable to switch membership approval state to allow or disallow skipping",true);

        }catch(\Exception $e){                    
            $error = $e->getMessage();
            return LibHelper::errorMessage($error,true);
        }
    }
    public function actionSetRenewalReminder(){
        $renewal_alert = Yii::$app->request->post('renewal_alert',null);
        $account_id = Yii::$app->request->post('account_id',null);

        if(is_null($renewal_alert) AND is_null($account_id)) return LibHelper::errorMessage("Please check your details. Can not have both renewal_alert and account_id blank",true);
        
        try{
            if(!is_null($renewal_alert)){
                if($account_id){
                    $account = Account::find()->where(['account_id'=>$account_id])->one();
                    $account->renewal_alert = $renewal_alert;
                    $account->save();
                }else{
                    $settings = Settings::find()->one();
                    $settings->renewal_alert = $renewal_alert;
                    $settings->save();
                }
                
                return [
                    'code' => self::CODE_SUCCESS,
                    'success' => TRUE,
                    'message' => "Successfully set days for notification alerts before renewal"
                ];
            }

            return LibHelper::errorMessage("Unable to set days for notification alerts before renewal",true);

        }catch(\Exception $e){
            $error = $e->getMessage();
            return LibHelper::errorMessage($error,true);
        }
    }
    public function actionSetDefaultDaysUnverified(){
        $days_unverified_reg = Yii::$app->request->post('days_unverified_reg',null);
        $account_id = Yii::$app->request->post('account_id',null);

        if(is_null($days_unverified_reg) AND is_null($account_id)) return LibHelper::errorMessage("Please check your details. Can not have both days_unverified_reg and account_id blank",true);
        
        try{
            if(!is_null($days_unverified_reg)){
                if($account_id){
                    $account = Account::find()->where(['account_id'=>$account_id])->one();
                    $account->days_unverified_reg = $days_unverified_reg;
                    $account->save();
                }else{
                    $settings = Settings::find()->one();
                    $settings->days_unverified_reg = $days_unverified_reg;
                    $settings->save();
                }
                
                return [
                    'code' => self::CODE_SUCCESS,
                    'success' => TRUE,
                    'message' => "Successfully set default number of days unverified registration before removal"
                ];
            }

            return LibHelper::errorMessage("Unable to set default number of days unverified registration before removal",true);

        }catch(\Exception $e){            
            $error = $e->getMessage();
            return LibHelper::errorMessage($error,true);
        }
    }

    public function actionSetOneApproval(){
        $is_one_approval = Yii::$app->request->post('is_one_approval',null);
        $account_id = Yii::$app->request->post('account_id',null);
        if(is_null($is_one_approval) AND is_null($account_id)) return LibHelper::errorMessage("Please check your details. Can not have both skip_approval and account_id blank",true);
        
        try{
            if(!is_null($is_one_approval)){
                if($account_id){
                    $account = Account::find()->where(['account_id'=>$account_id])->one();
                    $account->is_one_approval = $is_one_approval;
                    $account->save();
                }else{
                    $settings = Settings::find()->one();
                    $settings->is_one_approval = $is_one_approval;
                    $settings->save();
                }
                $state_is_one_approval = $is_one_approval == 1 ? 'allowed' : 'not allowed';
                return [
                    'code' => self::CODE_SUCCESS,
                    'success' => TRUE,
                    'message' => "Successfully {$state_is_one_approval} one approval policy"
                ];
            }

            return LibHelper::errorMessage("Unable to switch membership approval policy state to allow or disallow",true);
            
        }catch(\Exception $e){
            $error = $e->getMessage();
            return LibHelper::errorMessage($error,true);
        }
    }

    public function actionHardDelete()
    {
        $admin = Yii::$app->user->identity;
        $account_id = Yii::$app->request->post('account_id',null);

        $account = Account::findOne($account_id);

        if (!$account ) return LibHelper::errorMessage('Account not found',true);

        if(!$admin->isAdministrator()) return LibHelper::errorMessage("Can't Update Account Status! You don't have the required permission to apply changes.",true);
        
        foreach($account->users as $user){
            $user->account_id = 0;
            $user->save();
        }


        $account->delete();

        return [
            'code' => self::CODE_SUCCESS,
            'success' => TRUE,
            'message' => 'Successfully deleted.',
        ];
    }

    public function actionOnOffAds(){
        $admin = Yii::$app->user->identity;
        $enable_ads = Yii::$app->request->post('enable_ads',null);
        $account_id = Yii::$app->request->post('account_id',null);

        if(is_null($enable_ads) AND is_null($account_id)) return LibHelper::errorMessage("Please check your details. Can not have both enable_ads and account_id blank",true);
                
        $account = Account::findOne($account_id);

        if (!$account ) return LibHelper::errorMessage('Account not found',true);

        if(!$admin->isAdministrator()) return LibHelper::errorMessage("Can't Update Account Status! You don't have the required permission to apply changes.",true);
        
        try{
            $account->enable_ads = $enable_ads;
            $account->save();

            $state_ads = $enable_ads == 1 ? 'enabled (on)' : 'disabled (off)';
            
            return [
                'code' => self::CODE_SUCCESS,
                'success' => TRUE,
                'message' => "Successfully {$state_ads} ads"
            ];

            return LibHelper::errorMessage("Unable to switch ads state (on/off)",true);

        }catch(\Exception $e){            
            $error = $e->getMessage();
            return LibHelper::errorMessage($error,true);
        }
    }

    public function actionSetClubCode()
    {
        $admin = Yii::$app->user->identity;
        $account_id = Yii::$app->request->post('account_id',null);
        $club_code = Yii::$app->request->post('club_code',null);

        $account = Account::findOne($account_id);

        if (!$account ) return LibHelper::errorMessage('Account not found',true);

        // if(!$admin->isAdministrator()) return LibHelper::errorMessage("Can't Update Account Status! You don't have the required permission to apply changes.");
        
        $account = Account::find()->where(['account_id'=>$account_id])->one();
        $account->club_code = $club_code ? $club_code : mt_rand(100000, 999999);
        $account->save();

        return [
            'code' => self::CODE_SUCCESS,
            'success' => TRUE,
            'message' => 'Successfully Saved Club Code.',
        ];
    }

    public function actionUpdateDefaultSettings()
    {
        $admin = Yii::$app->user->identity;

        $form = new AccountSettingsForm;
        $form = $this->postLoad($form);
        $form->club_code = $form->club_code ? $form->club_code : mt_rand(100000, 999999);
        $account = Account::findOne($form->account_id);
        if (!$account ) return LibHelper::errorMessage('Account not found',true);

        if (!$form->validate()){
            $error = self::getFirstError(ActiveForm::validate($form));
            return LibHelper::errorMessage($error['message'], true);
        }


        // if(!$admin->isAdministrator()) return LibHelper::errorMessage("Can't Update Account Status! You don't have the required permission to apply changes.");
        
        $account = Account::find()->where(['account_id'=>$form->account_id])->one();
        $account->num_days_expiry   = $form->num_days_expiry;
        $account->enable_ads        = $form->enable_ads;
        $account->is_one_approval   = $form->is_one_approval;
        $account->renewal_alert     = $form->renewal_alert;
        $account->skip_approval     = $form->skip_approval;
        $account->club_code         = $form->club_code;
        $account->days_unverified_reg= $form->days_unverified_reg;
        $account->save();

        return [
            'code' => self::CODE_SUCCESS,
            'success' => TRUE,
            'message' => 'Successfully Updated Default Settings.',
        ];
    }


    public function actionUpdateMasterSettings()
    {
        $admin = Yii::$app->user->identity;

        $form = new AccountSettingsForm;
        $form = $this->postLoad($form);
        $form->club_code = $form->club_code ? $form->club_code : mt_rand(100000, 999999);

        if (!empty($_FILES)) $form->file = UploadedFile::getInstanceByName('file');
        if (!empty($form->file)) $form->logo = hash('crc32', $form->file->name) . time() . '.' . $form->file->extension;

        if (!$form->validate()){
            $error = self::getFirstError(ActiveForm::validate($form));
            return LibHelper::errorMessage($error['message'], true);
        }
        if (!empty($form->file)){
            if (!empty($form->logo)) $saved_img = LibHelper::saveImage($this, $form->file, $form->logo, Yii::$app->params['dir_member']);
            if (!empty($saved_img) AND !$saved_img['success'])  return $saved_img;
        }
        $settings = Settings::find()->one();
        if($form->default_interest) $settings->default_interest         = $form->default_interest;
        if($form->renewal_fee) $settings->renewal_fee                   = $form->renewal_fee;
        if($form->company) $settings->company                           = $form->company;
        if($form->logo) $settings->logo                                 = $form->logo;
        if($form->title) $settings->title                               = $form->title;
        if($form->content) $settings->content                           = $form->content;
        if($form->email) $settings->email                               = $form->email;
        if($form->contact_name) $settings->contact_name                 = $form->contact_name;
        if($form->address) $settings->address                           = $form->address;
        if($form->num_days_expiry) $settings->num_days_expiry           = $form->num_days_expiry;
        if($form->enable_ads) $settings->enable_ads                     = $form->enable_ads;
        if($form->enable_banner) $settings->enable_banner               = $form->enable_banner;
        if($form->is_one_approval) $settings->is_one_approval           = $form->is_one_approval;
        if($form->renewal_alert) $settings->renewal_alert               = $form->renewal_alert;
        if($form->skip_approval) $settings->skip_approval               = $form->skip_approval;
        if($form->club_code) $settings->club_code                       = $form->club_code;
        if($form->days_unverified_reg) $settings->days_unverified_reg   = $form->days_unverified_reg;
        $settings->save();

        return [
            'code' => self::CODE_SUCCESS,
            'success' => TRUE,
            'message' => 'Successfully Updated Master Settings.',
        ];
    }

    public function actionAddSecurityQuestions()
    {
        $admin = Yii::$app->user->identity;

        $form = new AccountSecurityQuestionsForm;
        $form = $this->postLoad($form);
        
        if (!$form->validate()){
            $error = self::getFirstError(ActiveForm::validate($form));
            return LibHelper::errorMessage($error['message'], true);
        }


        // if(!$admin->isAdministrator()) return LibHelper::errorMessage("Can't Update Account Status! You don't have the required permission to apply changes.");
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $accountquestions = AccountSecurityQuestions::Create($form);
            $accountquestions->save();
            $transaction->commit();
            return [
                'code' => self::CODE_SUCCESS,
                'success' => TRUE,
                'message' => 'Successfully Added Security Question',
                'data'    => $accountquestions->data()
            ];
        } catch (\Exception $e) {
            $transaction->rollBack();            
            $error = $e->getMessage();
            return LibHelper::errorMessage($error,true);
        } 
    }
    public function actionDeleteSecurityQuestions($id)
    {
        $admin = Yii::$app->user->identity;

        $accountsecurityquestion = AccountSecurityQuestions::findOne($id);
        if (!$accountsecurityquestion ) return LibHelper::errorMessage('Account Security Question not found',true);

        $accountsecurityquestion ->status = AccountSecurityQuestions::STATUS_DELETED;
        $accountsecurityquestion ->save();

            return [
                'code' => self::CODE_SUCCESS,
                'success' => TRUE,
                'message' => 'Successfully Deleted Security Question'
            ];
        
    }
    public function actionViewSecurityQuestions($id)
    {
        $admin = Yii::$app->user->identity;

        $accountsecurityquestion = AccountSecurityQuestions::findOne($id);
        if (!$accountsecurityquestion ) return LibHelper::errorMessage('Account Security Question not found',true);

            return [
                'code' => self::CODE_SUCCESS,
                'success' => TRUE,
                'message' => 'Security Question Successfully Retrieved!',
                'data'    => $accountsecurityquestion->data()
            ];
        
    }
    public function actionEditSecurityQuestions($id)
    {
        $admin = Yii::$app->user->identity;

        $accountsecurityquestion = AccountSecurityQuestions::findOne($id);
        if (!$accountsecurityquestion ) return LibHelper::errorMessage('Account Security Question not found',true);
 
        $form = new AccountSecurityQuestionsForm;
        $form = $this->postLoad($form);
        
        if (!$form->validate()){
            $error = self::getFirstError(ActiveForm::validate($form));
            return LibHelper::errorMessage($error['message'], true);
        }

        // if(!$admin->isAdministrator()) return LibHelper::errorMessage("Can't Update Account Status! You don't have the required permission to apply changes.");
        $transaction = Yii::$app->db->beginTransaction();
        try {
            
            $accountsecurityquestion->question = $form->question;
            $accountsecurityquestion->is_file_upload = $form->is_file_upload;
            $accountsecurityquestion->save();

            $transaction->commit();

            return [
                'code' => self::CODE_SUCCESS,
                'success' => TRUE,
                'message' => 'Successfully Updated Security Question',
                'data'    => $accountsecurityquestion->data()
            ];
        } catch (\Exception $e) {            
            $error = $e->getMessage();
            return LibHelper::errorMessage($error,true);
        } 
    }

    public function actionListSecurityQuestions(){

        $page    = Yii::$app->request->get('page', 1);
        $keyword = Yii::$app->request->get('keyword','');
        $account_id = Yii::$app->request->get('account_id',0);

        $page_size = Yii::$app->request->get('size',10);

        $qry = AccountSecurityQuestions::find()->where(['account_id' => $account_id]);

        $qry->andWhere(['NOT IN', 'status', [AccountSecurityQuestions::STATUS_DELETED]]);

        if ($keyword){
            $qry->andFilterWhere([
                'or',
                ['LIKE', 'question', $keyword]
            ]);
        }

        $totalCount = (int)$qry->count();
        $pages    = new Pagination(['totalCount' => $totalCount, 'defaultPageSize' => $page_size]);        
        $offset   = ($page - 1) * $pages->limit;
        
        $questions = $qry->orderBy(['id' => SORT_DESC])->offset($offset)->limit($pages->limit)->all();
        
        $data = [];

        foreach($questions as $question){
            $data[] = $question->data();
        }

        return [
            'data'          => $data,
            // 'current_page'  => $page,
            // 'page_count'    => ceil($pages->totalCount / $pages->pageSize),
            // 'current_page_size' => count($ads),
            'total' => $totalCount,
            'code'          => self::CODE_SUCCESS
        ];
    }


    

   
}