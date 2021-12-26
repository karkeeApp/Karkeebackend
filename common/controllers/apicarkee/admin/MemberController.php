<?php
namespace common\controllers\apicarkee\admin;

use common\forms\AdminRoleForm;
use common\forms\DocumentForm;
use common\forms\FileForm;
use common\forms\MemberResetPasswordForm;
use common\forms\UserForm;
use common\forms\UserSettingsForm;
use yii\data\Pagination;
use Yii;

use yii\web\UploadedFile;

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

use common\models\User;
use common\lib\Helper;
use common\models\Account;
use common\models\AccountMembership;
use common\models\Document;
use common\models\Email;
use common\models\MemberSecurityAnswers;
use common\models\Settings;
use common\models\UserFile;
use common\models\Watchdog;
use Google\CRC32\Google;
use Google_Client;
use Google_Service;
use Google_Service_Oauth2;

class MemberController extends Controller
{


    public function actionListSecurityAnswers()
    {
        $user = Yii::$app->user->identity;
        $acntmem = AccountMembership::find()->where(['user_id'=>$user->user_id])->one();
        if(!$acntmem) Helper::errorMessage("No Club Registration Found!");

        $data = [];
        foreach($acntmem->member_security_answers as $ans){
            $data[] = $ans->data();
        }
        
        return [
            'code' => self::CODE_SUCCESS,
            'data' => $data
        ];
    }


    public function actionListSecurityAnswersByUserId()
    {
        $user_id        = Yii::$app->request->get('user_id');
        $account_id     = Yii::$app->request->get('account_id');
        $qry = AccountMembership::find()->where(['user_id'=>$user_id]);
        
        if(!is_null($account_id)) $qry->andWhere(['account_id' => $account_id]);
        
        $acntmems = $qry->all();

        $data = [];
        foreach($acntmems as $acntmem){
            $ansdata = [];
            foreach($acntmem->member_security_answers as $ans) $ansdata[] = $ans->simpleData(); 

            $data[] = [
                            'account_id' => $acntmem->account_id,
                            'user_id' => $acntmem->user_id,
                            'company' => $acntmem->account->company,
                            'security_questions' => $ansdata
                        ];
            
        }
        
        return [
            'code' => self::CODE_SUCCESS,
            'data' => $data 
        ];
    }

    public function actionFileSecurityAnswers()
    {
        $id        = Yii::$app->request->get('id');
        $memsecans = MemberSecurityAnswers::findOne($id);
        if(!$memsecans) Helper::errorMessage("Member security answer not found!",true);
        try{
            $dir = Yii::$app->params['dir_sec_questions'];
            Yii::$app->response->sendFile($dir . $memsecans->answer, $memsecans->answer, ['inline' => TRUE]);
        } catch(\Exception $e) {            
            
            $error = $e->getMessage();
            return Helper::errorMessage($error,true);
        }
    }

    public function actionUploadDoc()
    {
        $user_id   = Yii::$app->request->post('user_id');
        $field = Yii::$app->request->post('field');
        
        $user = User::find()->where(['user_id' => $user_id])->one();

        if (!$user) return Helper::errorMessage("User is not found.",true);

        $form = new UserForm(['scenario' => 'admin-carkee-upload']);
        $form = $this->postLoad($form);

        if(!empty($_FILES) AND count($_FILES) > 0) $uploadFile = UploadedFile::getInstanceByName('file');

        if(!empty($uploadFile) AND count($_FILES) > 0) {
            
            $newFilename = hash('crc32', $uploadFile->name) . time() . '.' . $uploadFile->getExtension();
            
            $fileDestination = Yii::$app->params['dir_member'] . $newFilename;

            if(!$uploadFile->saveAs($fileDestination)) return Helper::errorMessage("Error uploading the file",true);
            
            $user->{$field} = $newFilename;
            $user->save();

            $docform = new DocumentForm;
            $docform = $this->postLoad($docform);   
            $docform->user_id       = $user_id;
            $docform->account_id    = $user->account_id;
            $docform->filename      = $newFilename;
            $doc_type = Document::EquivalentTypes()[$field];
            Document::Create($docform,$user->user_id,$doc_type);

            return [
                'code'    => self::CODE_SUCCESS,
                'message' => 'Successfully uploaded'
            ];
        }

        if(!empty($uploadFile) AND count($_FILES) > 0) return Helper::errorMessage("Invalid file",true);
        else return ['message'=>'No File Attached!'];
    }

    public function actionDoc()
    {
        $field     = Yii::$app->request->get('f');
        $user_id   = Yii::$app->request->get('u');
        $user = User::find()->where(['user_id' => $user_id])->one();

        if (!$user) return Helper::errorMessage("User is not found",true);

        /*
         * only allow public view have profiles
         */
        if (in_array($field, ['img_profile', 'img_vendor', 'company_logo','club_logo','brand_guide'])){ 
            // if (!$user OR $user->account_id != 0 OR !array_key_exists($field, $user->attributes)) {
            if (!$user OR !array_key_exists($field, $user->attributes)) return Helper::errorMessage("Invalid file",true);            
        }

        try{
            $dir = Yii::$app->params['dir_member'];

            /**
             * Load default profile
             */
            if (in_array($field, ['img_profile', 'img_vendor','company_logo','club_logo','brand_guide']) AND empty($user->{$field})) $user->{$field} = 'default-profile.png';

            return Yii::$app->response->sendFile($dir . $user->{$field}, $user->{$field}, ['inline' => TRUE]);
        } catch(\Exception $e) {
            
            $error = $e->getMessage();
            return Helper::errorMessage($error,true);
        }
    }   

    public function actionForgotPassword()
    {
        $form = new MemberResetPasswordForm(['scenario' => 'forgot']);
        $form = $this->postLoad($form);
        // $form->load(Yii::$app->request->post());
        
        if (!$form->validate()) {
            $error = self::getFirstError(ActiveForm::validate($form));
            return Helper::errorMessage($error['message'], true);
        }

        $user = User::find()
                    ->where(['account_id' => 0])
                    ->andWhere(['email' => $form->email])
                    ->one();

        if (!$user) return Helper::errorMessage("Account not found",true);

        $transaction = Yii::$app->db->beginTransaction();

        try {
            // $user->reset_code = (Yii::$app->params['environment'] == 'development') ? 123123 : mt_rand(100000, 999999);
            $user->reset_code = mt_rand(100000, 999999);
            $user->save();

            $transaction->commit();

            Email::sendSendGridAPI($user->email, 'KARKEE - Reset password', 'carkee-reset-password', $params=[
                'name'          => !empty($user->fullname) ? $user->fullname : $user->firstname,
                'reset_code'    => $user->reset_code,
                'client_email'  => $user->email,
                'club_email'    => "admin@carkee.sg",
                'club_name'     => "KARKEE",
                'club_link'     => "http://cpanel.carkee.sg",
                'club_logo'     => "http://qa.carkeeapi.carkee.sg/logo-edited.png",
                'api_link'      => (Yii::$app->params['environment'] == 'production' ? Yii::$app->params['api.carkee.endpoint']['prod'] : Yii::$app->params['api.carkee.endpoint']['dev'])
            ]);

            return [
                'code'       => self::CODE_SUCCESS,   
                'message'    => 'Code has been sent to your email address.',
                'email'      => $user->email,
                'account_id' => 0,
            ];

        } catch (\Exception $e) {
            $transaction->rollBack();
            
            $error = $e->getMessage();
            return Helper::errorMessage($error,true);
        }        
    }

    public function actionForgotPasswordConfirmCode()
    {
        $form = new MemberResetPasswordForm(['scenario' => 'confirm']);
        $form = $this->postLoad($form);
        
        if (!$form->validate()) {
            $error = self::getFirstError(ActiveForm::validate($form));
            return Helper::errorMessage($error['message'], true);
        }

        $user = User::find()
                    ->where(['account_id' => 0])
                    ->andWhere(['email' => $form->email])
                    ->andWhere(['reset_code' => $form->reset_code])
                    ->one();

        if (!$user) return Helper::errorMessage("Invalid code.",true);

        return [
            'code'       => self::CODE_SUCCESS,   
            'email'      => $user->email,
            'account_id' => 0,
            'reset_code' => $user->reset_code,
        ];
    }

    public function actionForgotPasswordUpdate()
    {
        $form = new MemberResetPasswordForm(['scenario' => 'update']);
        $form = $this->postLoad($form);
        
        if (!$form->validate()) {
            $error = self::getFirstError(ActiveForm::validate($form));
            return Helper::errorMessage($error['message'], true);
        }

        $user = User::find()
                    ->andWhere(['email' => $form->email])
                    ->one();

        if (!$user) return Helper::errorMessage("Email not found.",true);

        $user = User::find()
                    ->where(['account_id' => 0])
                    ->andWhere(['email' => $form->email])
                    ->one();

        if (!$user) return Helper::errorMessage("Account Id not found.",true);

        $user = User::find()
                    ->where(['account_id' => 0])
                    ->andWhere(['email' => $form->email])
                    ->andWhere(['reset_code' => $form->reset_code])
                    ->one();

        if (!$user) return Helper::errorMessage("Reset Code not found.",true);

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $user->setPassword($form->password_new);
            $user->reset_code = NULL;
            $user->save();

            $transaction->commit();

            return [
                'code'    => self::CODE_SUCCESS,   
                'message' => 'Successfully updated',
            ];
        } catch (\Exception $e) {
            $transaction->rollBack();
            
            $error = $e->getMessage();
            return Helper::errorMessage($error,true);
        }
    }

    public function actionSocialMediaCheck(){
        $accessToken = Yii::$app->request->post('sm_token','');
        $loginType = (int) Yii::$app->request->post('login_type',1);
                
        switch($loginType){
            case 1 :    return $this->fbUserCheck($accessToken);
            case 2 :    return $this->googleUserCheck($accessToken);
            case 3 :    return $this->appleUserCheck($accessToken);
            default:    return Helper::errorMessage("Invalid Details",true);
        }
    }

    private function fbUserCheck($accessToken = null){
   
        $fb = new \Facebook\Facebook([
            'app_id' => '269276291272983',
            'app_secret' => 'fc8cf664beb15e29245c437311f20b51',
            'default_graph_version' => 'v10.0',
            'http_client_handler' => 'stream'            
          ]);

        try {
     
            if (isset($accessToken)) { // you got a valid facebook authorization token
                $response = $fb->get('/me?fields=id,name,email', $accessToken);
                $userfb = $response->getGraphUser();
                
    
                // $user = User::findOne(['email' => $userfb->getEmail()]);
                $user = User::find()
                                ->where(['email' => $userfb->getEmail()])
                                ->andWhere(['account_id' => 0])
                                ->one();
                
                return [
                    'code'          => self::CODE_SUCCESS,   
                    'facebook'      => $userfb,
                    'in_db'         => ($user ? true : false),
                    'user_id'       => ($user ? $user->user_id : null),
                    'access_token'  => ($user && $user->auth_key) ? $user->auth_key : Yii::$app->security->generateRandomString()
                ];
    
            } else return Helper::errorMessage("Invalid Access Token",true);
            
        } catch(\Facebook\Exceptions\FacebookSDKException $e) {
            return Helper::errorMessage("Invalid Access Token",true);            
        }catch(\Exception $e){            
            
            $error = $e->getMessage();
            return Helper::errorMessage($error,true);
        }
        
        return Helper::errorMessage("Invalid Details",true);
    }

    private function googleUserCheck($accessToken = null){
        try{
            $dir = Yii::$app->params['dir_socialmedia_credentials'];
            $filegoogleconfig = $dir . 'google_client_config.json';
            // Get the API client and construct the service object.
            $client = new \Google\Client();
            $accessToken = $accessToken ? $accessToken : ($client->verifyIdToken() ? $client->verifyIdToken() : NULL);
            $client->setAuthConfig($filegoogleconfig);
            $client->setAccessType('offline');
            if($accessToken) $client->setAccessToken($accessToken);
            
            if($client){
                $oauth2 = new \Google\Service\Oauth2($client);
                $googleUser = $oauth2->userinfo->get();

                $user = User::find()
                                ->where(['email' => $googleUser->email])
                                ->andWhere(['account_id' => 0])
                                ->one();
                
                return [
                    'code'              => self::CODE_SUCCESS,   
                    'google'            => $googleUser,
                    'in_db'             => ($user ? true : false),
                    'user_id'           => ($user ? $user->user_id : null),
                    'access_token'      => ($user && $user->auth_key) ? $user->auth_key : Yii::$app->security->generateRandomString()
                    // 'google_id'     => $client->getId(),
                    // 'name'          => $client->getName(),
                    // 'email'         => $client->getEmail()
                ];
            }
        }catch(\Exception $e){
            
            $error = $e->getMessage();
            return Helper::errorMessage($error,true);
        }

        return Helper::errorMessage("Invalid Details",true);
    }
    

    private function appleUserCheck($accessToken = null){
        
        $dir = Yii::$app->params['dir_socialmedia_credentials'];
        $fileapplep8 = $dir . 'AuthKey_46F938U5QW.p8';
        $client_id = 'sg.carkee.appleid';
        $teamId = '93K7CUUG7Z';
        $redirect_url = 'https://qa.carkeeapi.carkee.sg/member/apple-user-check-redirect';
       
        if($accessToken){
            try{
                $apple_payload = $this->jwtDecode($accessToken);
                // $user = User::findOne(['email' => $apple_payload[1]->email]);
                $user = User::find()
                                ->where(['email' => $apple_payload[1]->email])
                                ->andWhere(['account_id' => 0])
                                ->one();
                
                return [
                    'code'              => self::CODE_SUCCESS,   
                    'apple'             => $apple_payload[1],
                    'in_db'             => ($user ? true : false),
                    'user_id'           => ($user ? $user->user_id : null),
                    'access_token'      => ($user && $user->auth_key) ? $user->auth_key : Yii::$app->security->generateRandomString()
                    
                ]; 
            }catch(\Exception $e){
            
                $error = $e->getMessage();
                return Helper::errorMessage($error,true);
            }
        }

        return Helper::errorMessage("Invalid Access Token",true);
    }

    private function jwtDecode($accessToken){
        $data = [];
        $tokenarr = explode(".",$accessToken);
        $data[0] = json_decode(base64_decode($tokenarr[0]));
        $data[1] = json_decode(base64_decode($tokenarr[1]));
        
        return $data;
    }
    
    public function actionChangeRole()
    {
        $form = new AdminRoleForm(['scenario' => 'account_admin_add']);
        $form = $this->postLoad($form);
        // $form->load(Yii::$app->request->post());

        $user = User::find()->where(['user_id' => $form->user_id])->one();
        
        if (!$form->validate()) {
            $error = self::getFirstError(ActiveForm::validate($form));
            return Helper::errorMessage($error['message'], true);
        }

        $user->role = $form->role;
        $user->save();

        return [
            'success' => TRUE,
            'message' => 'Successfully change role to '.$user->role()
        ];
    }

    private function memberList()
    {
        $admin             = Yii::$app->user->identity;
        $account_id        = Yii::$app->request->get('account_id',NULL);
        $member_type       = Yii::$app->request->get('type',NULL);
        $keyword           = Yii::$app->request->get('keyword','');
        $status            = Yii::$app->request->get('status',NULL);
        $premium_status    = Yii::$app->request->get('premium_status',NULL);
        $role              = Yii::$app->request->get('role',User::ROLE_USER);

        $page    = Yii::$app->request->get('page', 1);
        $page_size = Yii::$app->request->get('size',10);

        $qry = User::find()->where(['role' => $role]);

        if(!is_null($account_id)) $qry->andWhere(['account_id' => $account_id]);
        if(!is_null($status)) $qry->andWhere(['status' => $status]);
        if(!is_null($member_type)) $qry->andWhere(['member_type' => $member_type]);
        if(!is_null($premium_status)) $qry->andWhere(['premium_status' => $premium_status]);
        

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

    public function actionIndex()
    {
        return $this->memberList();
    }

    public function actionList()
    {
        return $this->memberList();
    }

    public function actionCreate()
    {
        $form = new UserForm(['scenario' => 'admin-add-carkee-member']);
        $form = $this->postLoad($form);
        
        $form->account_id = 0;
        
        if (!$form->validate()) {
            $error = self::getFirstError(ActiveForm::validate($form));
            return Helper::errorMessage($error['message'], true);
        }
        
        

        $transaction = Yii::$app->db->beginTransaction(); 

        try {
            $user = User::create($form, User::TYPE_MEMBER);

            $dir = Yii::$app->params['dir_member'];
            // $user->transfer_screenshot = Helper::base64ToImage($dir,Yii::$app->request->post('transfer_screenshot')); 
            // $user->img_authorization = Helper::base64ToImage($dir,Yii::$app->request->post('img_authorization')); 
            // $user->img_log_card = Helper::base64ToImage($dir,Yii::$app->request->post('img_log_card')); 
            // $user->img_insurance = Helper::base64ToImage($dir,Yii::$app->request->post('img_insurance')); 
            // $user->img_nric = Helper::base64ToImage($dir,Yii::$app->request->post('img_nric')); 
            // $user->img_profile = Helper::base64ToImage($dir,Yii::$app->request->post('img_profile')); 

            $transfile = UploadedFile::getInstanceByName('transfer_screenshot');
            $imgauthfile = UploadedFile::getInstanceByName('img_authorization');
            $imglogfile = UploadedFile::getInstanceByName('img_log_card');
            $imginsfile = UploadedFile::getInstanceByName('img_insurance');
            $imgnricfile = UploadedFile::getInstanceByName('img_nric');
            $imgprofile = UploadedFile::getInstanceByName('img_profile');

            $docform = new DocumentForm;
            $docform = $this->postLoad($docform);   
            $docform->user_id       = $user->user_id;
            $docform->account_id    = $user->account_id;

            if (!empty($transfile)) {
                $newFilename = hash('crc32', $transfile->name) . time() . '.' . $transfile->getExtension();                
                $fileDestination = $dir . $newFilename;    
                if (!$transfile->saveAs($fileDestination)) {
                    return [
                        'code'    => self::CODE_ERROR,
                        'message' => 'Error uploading the file'
                    ];
                }    
                $user->transfer_screenshot = $newFilename;

                $docform->filename      = $newFilename;

                $doc_type1 = Document::EquivalentTypes()['transfer_screenshot'];
                Document::Create($docform,$user->user_id,$doc_type1);
            }
            if (!empty($imgauthfile)) {
                $newFilename = hash('crc32', $imgauthfile->name) . time() . '.' . $imgauthfile->getExtension();                
                $fileDestination = $dir . $newFilename;    
                if (!$imgauthfile->saveAs($fileDestination)) {
                    return [
                        'code'    => self::CODE_ERROR,
                        'message' => 'Error uploading the file'
                    ];
                }    
                $user->img_authorization = $newFilename;                

                $docform->filename      = $newFilename;

                $doc_type2 = Document::EquivalentTypes()['img_authorization'];
                Document::Create($docform,$user->user_id,$doc_type2);
            }
            if (!empty($imglogfile)) {
                $newFilename = hash('crc32', $imglogfile->name) . time() . '.' . $imglogfile->getExtension();                
                $fileDestination = $dir . $newFilename;    
                if (!$imglogfile->saveAs($fileDestination)) {
                    return [
                        'code'    => self::CODE_ERROR,
                        'message' => 'Error uploading the file'
                    ];
                }    
                $user->img_log_card = $newFilename;                

                $docform->filename      = $newFilename;

                $doc_type3 = Document::EquivalentTypes()['img_log_card'];
                Document::Create($docform,$user->user_id,$doc_type3);
            }
            if (!empty($imginsfile)) {
                $newFilename = hash('crc32', $imginsfile->name) . time() . '.' . $imginsfile->getExtension();                
                $fileDestination = $dir . $newFilename;    
                if (!$imginsfile->saveAs($fileDestination)) {
                    return [
                        'code'    => self::CODE_ERROR,
                        'message' => 'Error uploading the file'
                    ];
                }    
                $user->img_insurance = $newFilename;                

                $docform->filename      = $newFilename;

                $doc_type4 = Document::EquivalentTypes()['img_insurance'];
                Document::Create($docform,$user->user_id,$doc_type4);
            }
            if (!empty($imgnricfile)) {
                $newFilename = hash('crc32', $imgnricfile->name) . time() . '.' . $imgnricfile->getExtension();                
                $fileDestination = $dir . $newFilename;    
                if (!$imgnricfile->saveAs($fileDestination)) {
                    return [
                        'code'    => self::CODE_ERROR,
                        'message' => 'Error uploading the file'
                    ];
                }    
                $user->img_nric = $newFilename;                

                $docform->filename      = $newFilename;

                $doc_type5 = Document::EquivalentTypes()['img_nric'];
                Document::Create($docform,$user->user_id,$doc_type5);
            }
            if (!empty($imgprofile)) {
                $newFilename = hash('crc32', $imgprofile->name) . time() . '.' . $imgprofile->getExtension();                
                $fileDestination = $dir . $newFilename;    
                if (!$imgprofile->saveAs($fileDestination)) {
                    return [
                        'code'    => self::CODE_ERROR,
                        'message' => 'Error uploading the file'
                    ];
                }    
                $user->img_profile = $newFilename;                

                $docform->filename      = $newFilename;

                $doc_type6 = Document::EquivalentTypes()['img_profile'];
                Document::Create($docform,$user->user_id,$doc_type6);
            }
            $user->save();

            $transaction->commit();

            // Watchdog::carkeeLog('Carkee: @email - member registration', ['@email' => $user->email], $user);
            
            return [
                'code'        => self::CODE_SUCCESS,
                'message'     => 'Successfully registered',
                'accesstoken' => $user->auth_key,
                'data'        => $user->data(1),
            ];
        } catch (\Exception $e) {
            $transaction->rollBack();      
            
            $error = $e->getMessage();
            return Helper::errorMessage($error,true);
        }
    }
    public function actionCreateNoApproval()
    {
        $form = new UserForm(['scenario' => 'admin-add-carkee-member']);
        $form = $this->postLoad($form);        
        
        if (!$form->validate()) {
            $error = self::getFirstError(ActiveForm::validate($form));
            return Helper::errorMessage($error['message'], true);
        }

        $transfile = UploadedFile::getInstanceByName('transfer_screenshot');
        $imgauthfile = UploadedFile::getInstanceByName('img_authorization');
        $imglogfile = UploadedFile::getInstanceByName('img_log_card');
        $imginsfile = UploadedFile::getInstanceByName('img_insurance');
        $imgnricfile = UploadedFile::getInstanceByName('img_nric');
        $imgprofile = UploadedFile::getInstanceByName('img_profile');

        $transaction = Yii::$app->db->beginTransaction(); 

        try {
            $form->account_id = 0;
            $form->no_approval = 1;
            $usertype = !is_null($form->member_type) ? $form->member_type : User::TYPE_MEMBER;
            $user = User::create($form, $usertype);

            $docform = new DocumentForm;
            $docform = $this->postLoad($docform);   
            $docform->user_id       = $user->user_id;
            $docform->account_id    = $user->account_id;

            if (!empty($transfile)) {
                $newFilename = hash('crc32', $transfile->name) . time() . '.' . $transfile->getExtension();                
                $fileDestination = Yii::$app->params['dir_member'] . $newFilename;    
                if (!$transfile->saveAs($fileDestination)) {
                    return [
                        'code'    => self::CODE_ERROR,
                        'message' => 'Error uploading the file'
                    ];
                }    
                $user->transfer_screenshot = $newFilename;                

                $docform->filename      = $newFilename;

                $doc_type1 = Document::EquivalentTypes()['transfer_screenshot'];
                Document::Create($docform,$user->user_id,$doc_type1);
            }
            if (!empty($imgauthfile)) {
                $newFilename = hash('crc32', $imgauthfile->name) . time() . '.' . $imgauthfile->getExtension();                
                $fileDestination = $dir . $newFilename;    
                if (!$imgauthfile->saveAs($fileDestination)) {
                    return [
                        'code'    => self::CODE_ERROR,
                        'message' => 'Error uploading the file'
                    ];
                }    
                $user->img_authorization = $newFilename;                

                $docform->filename      = $newFilename;

                $doc_type2 = Document::EquivalentTypes()['img_authorization'];
                Document::Create($docform,$user->user_id,$doc_type2);
            }
            if (!empty($imglogfile)) {
                $newFilename = hash('crc32', $imglogfile->name) . time() . '.' . $imglogfile->getExtension();                
                $fileDestination = $dir . $newFilename;    
                if (!$imglogfile->saveAs($fileDestination)) {
                    return [
                        'code'    => self::CODE_ERROR,
                        'message' => 'Error uploading the file'
                    ];
                }    
                $user->img_log_card = $newFilename;                

                $docform->filename      = $newFilename; 

                $doc_type3 = Document::EquivalentTypes()['img_log_card'];
                Document::Create($docform,$user->user_id,$doc_type3);
            }
            if (!empty($imginsfile)) {
                $newFilename = hash('crc32', $imginsfile->name) . time() . '.' . $imginsfile->getExtension();                
                $fileDestination = $dir . $newFilename;    
                if (!$imginsfile->saveAs($fileDestination)) {
                    return [
                        'code'    => self::CODE_ERROR,
                        'message' => 'Error uploading the file'
                    ];
                }    
                $user->img_insurance = $newFilename;                

                $docform->filename      = $newFilename;

                $doc_type4 = Document::EquivalentTypes()['img_insurance'];
                Document::Create($docform,$user->user_id,$doc_type4);
            }
            if (!empty($imgnricfile)) {
                $newFilename = hash('crc32', $imgnricfile->name) . time() . '.' . $imgnricfile->getExtension();                
                $fileDestination = $dir . $newFilename;    
                if (!$imgnricfile->saveAs($fileDestination)) {
                    return [
                        'code'    => self::CODE_ERROR,
                        'message' => 'Error uploading the file'
                    ];
                }    
                $user->img_nric = $newFilename;                

                $docform->filename      = $newFilename;

                $doc_type5 = Document::EquivalentTypes()['img_nric'];
                Document::Create($docform,$user->user_id,$doc_type5);
            }
            if (!empty($imgprofile)) {
                $newFilename = hash('crc32', $imgprofile->name) . time() . '.' . $imgprofile->getExtension();                
                $fileDestination = $dir . $newFilename;    
                if (!$imgprofile->saveAs($fileDestination)) {
                    return [
                        'code'    => self::CODE_ERROR,
                        'message' => 'Error uploading the file'
                    ];
                }    
                $user->img_profile = $newFilename;                

                $docform->filename      = $newFilename;

                $doc_type6 = Document::EquivalentTypes()['img_profile'];
                Document::Create($docform,$user->user_id,$doc_type6);
            }
            $user->save();

            $transaction->commit();

            // Watchdog::carkeeLog('Carkee: @email - member registration', ['@email' => $user->email], $user);
            
            return [
                'code'        => self::CODE_SUCCESS,
                'message'     => 'Successfully registered',
                'accesstoken' => $user->auth_key,
                'data'        => $user->data(1),
            ];
        } catch (\Exception $e) {
            $transaction->rollBack();
            
            $error = $e->getMessage();
            return Helper::errorMessage($error,true);
        }
    }

    public function actionUpdate()
    {
        $user_id = Yii::$app->request->get('user_id');

        $params_data = Yii::$app->request->post();

        $form = new UserForm;
        $form = $this->postLoad($form);
        
        $user = User::find()->where(['user_id' => $user_id])->one();
        
        if (!$form->validate()) {
            $error = self::getFirstError(ActiveForm::validate($form));
            return Helper::errorMessage($error['message'], true);
        }

        $transfile = UploadedFile::getInstanceByName('transfer_screenshot');
        $imgauthfile = UploadedFile::getInstanceByName('img_authorization');
        $imglogfile = UploadedFile::getInstanceByName('img_log_card');
        $imginsfile = UploadedFile::getInstanceByName('img_insurance');
        $imgnricfile = UploadedFile::getInstanceByName('img_nric');
        $imgprofile = UploadedFile::getInstanceByName('img_profile');

        if($user->password_hash == Yii::$app->security->generatePasswordHash($form->password)) $user->setPassword($form->password);

        $excludeFields = ['user_id','password','password_confirm','status','img_profile','img_nric','img_insurance','img_log_card','img_authorization','transfer_screenshot'];
        
        $fields = Helper::getFieldKeys($params_data, $excludeFields);

        foreach($fields as $field) $user->{$field} = $form->{$field}; 

        if(!empty($form->password) OR !empty($form->password_confirm)){
            if($form->password == $form->password_confirm) $user->setPassword($form->password);            
            else return Helper::errorMessage("Password is Invalid or Does not Match!", true);
        }
        
        $docform = new DocumentForm;
        $docform = $this->postLoad($docform);   
        $docform->user_id       = $user->user_id;
        $docform->account_id    = $user->account_id;
       
        if (!empty($transfile)) {
            $newFilename = hash('crc32', $transfile->name) . time() . '.' . $transfile->getExtension();                
            $fileDestination = Yii::$app->params['dir_member'] . $newFilename;    
            if (!$transfile->saveAs($fileDestination)) {
                return [
                    'code'    => self::CODE_ERROR,
                    'message' => 'Error uploading the file'
                ];
            }    
            $user->transfer_screenshot = $newFilename;                

            $docform->filename      = $newFilename;

            $doc_type1 = Document::EquivalentTypes()['transfer_screenshot'];
            Document::Create($docform,$user->user_id,$doc_type1);
        }
        if (!empty($imgauthfile)) {
            $newFilename = hash('crc32', $imgauthfile->name) . time() . '.' . $imgauthfile->getExtension();                
            $fileDestination = $dir . $newFilename;    
            if (!$imgauthfile->saveAs($fileDestination)) {
                return [
                    'code'    => self::CODE_ERROR,
                    'message' => 'Error uploading the file'
                ];
            }    
            $user->img_authorization = $newFilename;                

            $docform->filename      = $newFilename;

            $doc_type2 = Document::EquivalentTypes()['img_authorization'];
            Document::Create($docform,$user->user_id,$doc_type2);
        }
        if (!empty($imglogfile)) {
            $newFilename = hash('crc32', $imglogfile->name) . time() . '.' . $imglogfile->getExtension();                
            $fileDestination = $dir . $newFilename;    
            if (!$imglogfile->saveAs($fileDestination)) {
                return [
                    'code'    => self::CODE_ERROR,
                    'message' => 'Error uploading the file'
                ];
            }    
            $user->img_log_card = $newFilename;                

            $docform->filename      = $newFilename;

            $doc_type3 = Document::EquivalentTypes()['img_log_card'];
            Document::Create($docform,$user->user_id,$doc_type3);
        }
        if (!empty($imginsfile)) {
            $newFilename = hash('crc32', $imginsfile->name) . time() . '.' . $imginsfile->getExtension();                
            $fileDestination = $dir . $newFilename;    
            if (!$imginsfile->saveAs($fileDestination)) {
                return [
                    'code'    => self::CODE_ERROR,
                    'message' => 'Error uploading the file'
                ];
            }    
            $user->img_insurance = $newFilename;                

            $docform->filename      = $newFilename;

            $doc_type4 = Document::EquivalentTypes()['img_insurance'];
            Document::Create($docform,$user->user_id,$doc_type4);
        }
        if (!empty($imgnricfile)) {
            $newFilename = hash('crc32', $imgnricfile->name) . time() . '.' . $imgnricfile->getExtension();                
            $fileDestination = $dir . $newFilename;    
            if (!$imgnricfile->saveAs($fileDestination)) {
                return [
                    'code'    => self::CODE_ERROR,
                    'message' => 'Error uploading the file'
                ];
            }    
            $user->img_nric = $newFilename;                

            $docform->filename      = $newFilename;

            $doc_type5 = Document::EquivalentTypes()['img_nric'];
            Document::Create($docform,$user->user_id,$doc_type5);
        }
        if (!empty($imgprofile)) {
            $newFilename = hash('crc32', $imgprofile->name) . time() . '.' . $imgprofile->getExtension();                
            $fileDestination = $dir . $newFilename;    
            if (!$imgprofile->saveAs($fileDestination)) {
                return [
                    'code'    => self::CODE_ERROR,
                    'message' => 'Error uploading the file'
                ];
            }    
            $user->img_profile = $newFilename;                

            $docform->filename      = $newFilename;

            $doc_type6 = Document::EquivalentTypes()['img_profile'];
            Document::Create($docform,$user->user_id,$doc_type6);
        }

        $user->save();
        $edituser = $user;
        $data = array_merge($edituser->data(1),$edituser->carkeeData(1));
        Yii::info($data,'carkee');
        return [
            'code' => self::CODE_SUCCESS, 
            'success' => TRUE,
            'message' => 'Successfully updated.',
            'data'    => $data
        ];
    }
        
    public function actionInfoByUserId()
    {

        $user_id = Yii::$app->request->get('user_id', 0);

        $user = User::find()->where(['user_id' => $user_id])->one();
        
        if (!$user) {
            return [
                'success' => TRUE,
                'content' => 'User not found.',
            ];
        }

        $data = array_merge($user->data(1),$user->carkeeData(1));

        return [
            'code' => self::CODE_SUCCESS,   
            'data' => $data
        ];
    }

    public function actionInfo()
    {
        
        $user = Yii::$app->user->identity;

        $data = array_merge($user->data(1),$user->carkeeData(1));

        return [
            'code' => self::CODE_SUCCESS,   
            'data' => $data
        ];
    }

    public function actionOptionsByUserId()
    {
        $user_id = Yii::$app->request->get('user_id', 0);

        $user = User::find()->where(['user_id' => $user_id])->one();

        if (!$user) {
            return [
                'success' => TRUE,
                'content' => 'User not found.',
            ];
        }

        return [
            'code'            => self::CODE_SUCCESS,
            'member_id'       => $user->memberId(),
            // 'account_id'      => $user->account_id,
            'owner_options'   => User::ownerOptions(TRUE),
            'relationships'   => User::relationships(TRUE),
            'salaries'        => User::salaries(TRUE),
            'total_payable'   => 'SGD 250.00',
            'entity_eun'      => 'T20SS0149L',
            'entity_name'     => 'BMW Car Club Singapore',
            'details' => [
                [
                    'label' => 'Member entrance fee (one-time)',
                    'amount' => 'SGD 100.00',
                ],
                [
                    'label' => 'Subscription fee',
                    'amount' => 'SGD 150.00',
                ]
            ]
        ];
    }       

    public function actionOptions()
    {
        $user = Yii::$app->user->identity;

        return [
            'code'            => self::CODE_SUCCESS,
            'member_id'       => $user->memberId(),
            // 'account_id'      => $user->account_id,
            'owner_options'   => User::ownerOptions(TRUE),
            'relationships'   => User::relationships(TRUE),
            'salaries'        => User::salaries(TRUE),
            'total_payable'   => 'SGD 250.00',
            'entity_eun'      => 'T20SS0149L',
            'entity_name'     => 'BMW Car Club Singapore',
            'details' => [
                [
                    'label' => 'Member entrance fee (one-time)',
                    'amount' => 'SGD 100.00',
                ],
                [
                    'label' => 'Subscription fee',
                    'amount' => 'SGD 150.00',
                ]
            ]
        ];
    }       

    public function actionSetExpiry()
    {
        $user_id = Yii::$app->request->get('user_id');
        $member_expiry = Yii::$app->request->post('member_expiry');

        $user = User::find()->where(['user_id' => $user_id])->one();

        if (!$user) return Helper::errorMessage("User is not found.",true);

        $user->member_expire = $member_expiry;
        $user->save();

        return [
            'success' => TRUE,
            'message' => 'Member Expiry Successfully Set!',
            'data'    => $user->data(1)
        ];
    }
    
    public function actionApprove()
    {
        $account = Yii::$app->user->getIdentity();
        $user_id = Yii::$app->request->post('user_id',0);

        $user = User::find()->where(['user_id' => $user_id])->one();
        
        if (!$user) return Helper::errorMessage('User is not found.',true);

        // if ($user->isApproved() AND $user->isConfirmed()) return Helper::errorMessage('User is already approved.',true);        
        // else if($user->isConfirmed() AND $user->confirmed_by == $account->user_id) return Helper::errorMessage('You already approved this. Please let someone be the checker.',true);
        // else if(!$user->isPending()) return Helper::errorMessage('User is not in pending status.',true);
        
        if(!$account->isAdministrator()) return Helper::errorMessage("Can't Update Status! You don't have the required permission to apply changes.",true);

        if($user->isApproved() AND $user->isConfirmed()) return Helper::errorMessage('User is already approved.',true);                
        
        if(!$account->isRoleSuperAdmin()) if(($user->isConfirmed() OR $user->isPending()) AND $user->account_id != $account->account_id) return Helper::errorMessage("Can only change status by Admins of this Club",true);        
        
        $message = null;
        $settings = Settings::find()->one();
        $user_default_expiry = date('Y-m-d', strtotime('+1 year', strtotime($user->member_expire)));
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

        if (!$user->confirmed_by AND $user->approved_by !== $account->user_id) {
            $user->confirmed_by = $account->user_id;
            $user->status      = User::STATUS_APPROVED;   
            $message = "Successfully Approved! Member's Account Details";
            $user->save();
        }

        if ($user->approved_by AND $user->confirmed_by){
            $user->status      = User::STATUS_APPROVED;
            $user->approved_at = date('Y-m-d H:i:s');         
            $user->member_expire = $user_default_expiry;

            $title = "One (1) membership registration had been approved with ID #: ".$user->user_id;
            $desc = strtoupper(($account->account_id > 0 ? $account->account->company : "Karkee"))."'s Member ".($user->fullname ? $user->fullname : $user->firstname)." request for registration is now approved";
            Helper::pushNotificationFCM_ToMemberDirector($title, $desc, ($account->account_id > 0 ? $account->account->company : "Karkee"), $account->account_id);
                
        }
        $user->save();

        return [
            'success' => TRUE,
            'message'   => $message
        ];

        // if($message) 
        // {
        //     return [
        //         'success' => TRUE,
        //         'message'   => $message
        //     ];
        // }

        // return Helper::errorMessage("Unable to Approve Member's Account Details",true);
    }

    public function actionReject()
    {
        $account = Yii::$app->user->getIdentity();
        $user_id = Yii::$app->request->post('user_id',0);

        $user = User::find()->where(['user_id' => $user_id])->one();

        if (!$user) return Helper::errorMessage("User is not found.",true); 

        if ($user->isIncomplete()) return Helper::errorMessage("User is already rejected.",true); 
        else if (!$user->isPending()) return Helper::errorMessage("User is not in pending status.",true); 
    
        //$user->status = User::STATUS_INCOMPLETE;
        $user->status = User::STATUS_REJECTED;
        $user->save();

        // $title = "One (1) membership registration had been rejected with ID #: ".$user->user_id;
        // $desc = strtoupper(($account->account_id > 0 ? $account->account->company : "Karkee"))."'s Member ".($user->fullname ? $user->fullname : $user->firstname)."'s request for registration is rejected!";
        // Helper::pushNotificationFCM_ToMemberDirector($title, $desc, ($account->account_id > 0 ? $account->account->company : "Karkee"), $account->account_id);

        return [
            'success' => TRUE,
            'message'   => 'User was successfully rejected.',
        ];
    }

    public function actionDelete()
    {
        $user_id = Yii::$app->request->post('user_id',0);

        $user = User::find()->where(['user_id' => $user_id])->one();

        if (!$user) return Helper::errorMessage("User is not found.",true); 

        $user->status = User::STATUS_DELETED;
        $user->save();

        return [
            'success' => TRUE,
            'message'   => 'User was successfully deleted.',
        ];
    }

    public function actionSetSponsor() {
        
        $user_id = Yii::$app->request->get('user_id',0);

        $user = User::find()->where(['user_id' => $user_id])->one();

        if (!$user) return Helper::errorMessage("User is not found.",true); 

        $user->role = User::ROLE_SPONSORSHIP;
        $user->save();

        return [
            'success' => TRUE,
            'message' => 'Successfully set as Sponsor',
        ];
    }
    public function actionSponsorLevel() {
        
        $user_id = Yii::$app->request->get('user_id',0);
        $level = Yii::$app->request->post('level');

        $user = User::find()->where(['user_id' => $user_id])->one();

        if (!$user) return Helper::errorMessage("User is not found.",true); 

        $user->level=$level;
        $user->carkee_level=$level;
        $user->save();

        return [
            'success' => TRUE,
            'message' => 'Successfully set Sponsor to '.$user->level()
        ];
    }
    public function actionSetDefaultExpiry(){
        $defaultmemexpiry = Yii::$app->request->post('member_expiry',null);
        $account_id = Yii::$app->request->post('account_id',null);

        if(!$defaultmemexpiry AND is_null($account_id)) return Helper::errorMessage("Please check your details. Can not have both member_expiry and account_id blank",true); 
            
        try{
            if($defaultmemexpiry){
                $defaultmemexpiryepoch = date('Y-m-d',strtotime($defaultmemexpiry));
                if($account_id){
                    $account = Account::find()->where(['account_id'=>$account_id])->one();
                    $account->member_expiry = $defaultmemexpiryepoch;
                    $account->save();
                }else{
                    $settings = Settings::find()->one();
                    $settings->member_expiry = $defaultmemexpiryepoch;
                    $settings->save();
                }
                return [
                    'success' => TRUE,
                    'message' => "Successfully set default member expiry to {$defaultmemexpiryepoch}"
                ];
            }

            return Helper::errorMessage("Unable to update default member expiry. Please check your member expiry date",true); 

        }catch(\Exception $e){
            
            $error = $e->getMessage();
            return Helper::errorMessage($error,true);

        }
    }
    
    public function actionSkipMemberApproval(){
        $skip_approval = Yii::$app->request->post('skip_approval',null);
        $account_id = Yii::$app->request->post('account_id',null);
        if(is_null($skip_approval) AND is_null($account_id)) return Helper::errorMessage("Please check your details. Can not have both skip_approval and account_id blank",true); 
        
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

            return Helper::errorMessage("Unable to switch membership approval state to allow or disallow skipping",true); 

        }catch(\Exception $e){                  
            
            $error = $e->getMessage();
            return Helper::errorMessage($error,true);   
        }
    }
    public function actionSetRenewalReminder(){
        $renewal_alert = Yii::$app->request->post('renewal_alert',null);
        $account_id = Yii::$app->request->post('account_id',null);

        if(is_null($renewal_alert) AND is_null($account_id)) return Helper::errorMessage("Please check your details. Can not have both renewal_alert and account_id blank",true);
        
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
                    'success' => TRUE,
                    'message' => "Successfully set days for notification alerts before renewal"
                ];
            }

            return Helper::errorMessage("Unable to set days for notification alerts before renewal",true);

        }catch(\Exception $e){      
            
            $error = $e->getMessage();
            return Helper::errorMessage($error,true);
        }
    }

    public function actionSetOneApproval(){
        $is_one_approval = Yii::$app->request->post('is_one_approval',null);
        $account_id = Yii::$app->request->post('account_id',null);
        if(is_null($is_one_approval) AND is_null($account_id)) return Helper::errorMessage("Please check your details. Can not have both skip_approval and account_id blank",true);
        
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
                    'success' => TRUE,
                    'message' => "Successfully {$state_is_one_approval} one approval policy"
                ];
            }

            return Helper::errorMessage("Unable to switch membership approval policy state to allow or disallow",true);

        }catch(\Exception $e){                  
            
            $error = $e->getMessage();
            return Helper::errorMessage($error,true);
        }
    }

    public function actionUpdateDefaultSettings()
    {
        $admin = Yii::$app->user->identity;

        $form = new UserSettingsForm(['scenario'=>'update-default-settings']);
        $form = $this->postLoad($form);
        $form->club_code = $form->club_code ? $form->club_code : mt_rand(100000, 999999);
        
        $account = Account::findOne($form->account_id);
        if (!$account ) return Helper::errorMessage('Account not found',true);

        
        if (!$form->validate()) {
            $error = self::getFirstError(ActiveForm::validate($form));
            return Helper::errorMessage($error['message'], true);
        }


        // if(!$admin->isAdministrator()) return Helper::errorMessage("Can't Update Account Status! You don't have the required permission to apply changes.");
        
        $account = Account::find()->where(['account_id'=>$form->account_id])->one();
        $account->member_expiry     = $form->member_expiry;
        $account->enable_ads        = $form->enable_ads;
        $account->is_one_approval   = $form->is_one_approval;
        $account->renewal_alert     = $form->renewal_alert;
        $account->skip_approval     = $form->skip_approval;
        $account->club_code         = $form->club_code;
        $account->save();

        return [
            'success' => TRUE,
            'message' => 'Successfully Updated Default Settings.',
        ];
    }

}
