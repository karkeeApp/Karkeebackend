<?php
namespace common\controllers\api;

use Yii;
use yii\widgets\ActiveForm;
use yii\web\UploadedFile;
use \Firebase\JWT\JWT;
use AppleSignIn\ASDecoder;

use common\models\Account;
use common\models\User;
use common\models\ItemRedeem;
use common\models\Email;
use common\models\Watchdog;
use common\models\Renewal;
use common\models\UserLog;

use common\forms\UserForm;
use common\forms\RenewalForm;
use common\forms\SocialMediaCheckForm;

use api\forms\LoginForm;
use api\forms\LoginUiidForm;
use common\forms\AccountMembershipForm;
use common\forms\UserPaymentForm;
use common\helpers\Common;
use common\lib\PaginationLib;
use common\helpers\MyActiveDataProvider;
use common\helpers\Sort;
use common\lib\Helper;
use common\models\AccountMembership;
use common\models\AccountSecurityQuestions;
use common\models\MemberExpiry;
use common\models\MemberSecurityAnswers;
use common\models\Settings;
use common\models\UserDirector;
use common\models\UserFcmToken;
use common\models\UserPayment;
use common\models\UserSocialMedia;
use Google_Client;
use Google_Service_Oauth2;
use Google_Service_Plus;
use yii\imagine\Image;
use yii\helpers\FileHelper;
class MemberController extends Controller
{
    private function adminEmails(){

        $data = User::adminEmails();
        return $data;
    }    
    private function subEmails(){

        $data = User::subAdminEmails();
        return $data;
    }    
    private function superEmails(){

        $data = User::superAdminEmails();
        return $data;
    }
    //For testing purpose only.
    public function actionMain(){
        return $this->adminEmails();
    }
    public function actionSub(){
        return $this->subEmails();
    }
    public function actionSuper(){
        return $this->superEmails();
    }

    public function actionRenewal()
    {
        $user = Yii::$app->user->identity;
        
        /**
         * Check if have pending renewal
         */

        $renewal = Renewal::find()
                            ->where(['user_id'=> $user->user_id])
                            ->andWhere(['account_id'=> $user->account_id])
                            ->andWhere(['status' => Renewal::STATUS_PENDING])
                            ->one();

        if ($renewal){
            return [
                'code'    => self::CODE_ERROR,
                'message' => 'We are now processing your renewal.'
            ];
        }

        
        $form = new RenewalForm();
        $uploadFile = null;
        $logFile = null;
        if (!empty($_FILES)) {
            $temp = [];

            foreach($_FILES as $key => $file) {
                $temp['name'][$key] = $file['name'];
                $temp['type'][$key] = $file['type'];
                $temp['tmp_name'][$key] = $file['tmp_name'];
                $temp['error'][$key] = $file['error'];
                $temp['size'][$key] = $file['size'];

                
            }
            $_FILES['RenewalForm'] = $temp;
            
        }

        $form = $this->postLoad($form);
        $uploadFile = UploadedFile::getInstance($form,'file');
        $logFile = UploadedFile::getInstanceByName('log_card');
        $transaction = Yii::$app->db->beginTransaction();
        try {

            if(empty($uploadFile)){
                return [
                'code'    => self::CODE_ERROR,
                'message' => 'Payment screenshot is required'
                ];
            }
            if(empty($logFile)){
                return [
                    'code'    => self::CODE_ERROR,
                    'message' => 'Log card file is required'
                ];
            }
            if ($uploadFile) {

                $newFilename = hash('crc32', $uploadFile->name) . time() . '.' . $uploadFile->getExtension();
                $fileDestination = Yii::$app->params['dir_renewal'] . $newFilename;

                $newFilename2 = hash('crc32', $logFile->name) . time() . '.' . $logFile->getExtension();
                $fileDestination2 = Yii::$app->params['dir_renewal'] . $newFilename2;

                if (!$uploadFile->saveAs($fileDestination)) {
                    return [
                        'code'    => self::CODE_ERROR,
                        'message' => 'Error uploading payment screenshot file'
                    ];
                }

                if (!$logFile->saveAs($fileDestination2)) {
                    return [
                        'code'    => self::CODE_ERROR,
                        'message' => 'Error uploading log card file'
                    ];
                }

                $renewal             = new Renewal;
                $renewal->user_id    = $user->user_id;
                $renewal->account_id = $user->account_id;
                $renewal->filename   = $newFilename;
                $renewal->log_card   = $newFilename2;
                $renewal->save();

                $dir_payment = Yii::$app->params['dir_payment'];
                @copy($fileDestination,$dir_payment.$renewal->filename);
                @copy($fileDestination2,$dir_payment.$renewal->log_card);

                $renewal->user->status = User::STATUS_PENDING_RENEWAL_APPROVAL;
                $renewal->user->save();

                $userpaymentform = new UserPaymentForm;
                $userpaymentform = $this->postLoad($userpaymentform);
                $userpaymentform->renewal_id = $renewal->id;
                $userpaymentform->user_id = $user->user_id;
                $userpaymentform->account_id = $user->account_id;
                $userpaymentform->amount = 0;
                $userpaymentform->description = $user->fullname . " renewal payment";
                $userpaymentform->filename = $newFilename;
                $userpaymentform->log_card = $newFilename2;
                $userpaymentform->name = $user->fullname . " renewal";
                $userpaymentform->payment_for = !is_null($userpaymentform->payment_for) ? $userpaymentform->payment_for : UserPayment::PAYMENT_FOR_RENEWAL;
                $userPayment = UserPayment::Add($userpaymentform, $user->user_id);


                $member_expiry = MemberExpiry::find()
                                                ->where(['status' => MemberExpiry::STATUS_ACTIVE])
                                                ->andWhere(['user_id'=> $user->user_id])
                                                ->andWhere(['account_id'=> $user->account_id])
                                                ->andWhere(['member_expiry' => $user->member_expire])
                                                ->one();
                if($member_expiry){
                    $member_expiry->renewal_id = $renewal->id;
                    $member_expiry->save();
                }

                $user_log = [
                    'user_id' => $user->user_id,
                    'renewal_id' => $renewal->id,
                    'type' => 2, //renewal
                    'log_card' => $newFilename2,
                ];
                UserLog::create($user_log);

                Email::sendEmailNotification(Yii::$app->params['admin.email'], 'KARKEE Event', 'admin-notification', User::adminEmails(), User::subAdminEmails(), User::superAdminEmails(), $params=[
                    'name'       => !empty($user->fullname) ? $user->fullname : $user->firstname,
                    'heading'      => 'The following user has made a payment for membership renewal',
                    'email'         => $user->email,
                    'client_email'  => $user->email,
                    'club_email'    => "admin@carkee.sg",
                    'club_name'     => "KARKEE",
                    'club_link'     => "http://cpanel.carkee.sg",
                    'club_logo'     => "http://qa.carkeeapi.carkee.sg/logo-edited.png",
                    'api_link'      => (Yii::$app->params['environment'] == 'production' ? Yii::$app->params['api.carkee.endpoint']['prod'] : Yii::$app->params['api.carkee.endpoint']['dev'])
                ]);

                $transaction->commit();

                return [
                    'code'    => self::CODE_SUCCESS,
                    'message' => 'Successfully uploaded'
                ];
            }

        } catch (\Exception $e) {
            
            return [
                'code'    => self::CODE_ERROR,   
                'message' => $e->getMessage(),
            ];
        }
    }

    public function actionRenewalAttachment()
    {
        // $loginUser = Yii::$app->user->identity;
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

    public function actionRenewalLogCard()
    {
        // $loginUser = Yii::$app->user->identity;
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

    public function actionFileSecurityAnswers()
    {
        $id        = Yii::$app->request->get('id');
        $memsecans = MemberSecurityAnswers::findOne($id);
        if(!$memsecans) Helper::errorMessage("Member security answer not found!");
        try{
            $dir = Yii::$app->params['dir_sec_questions'];
            Yii::$app->response->sendFile($dir . $memsecans->answer, $memsecans->answer, ['inline' => TRUE]);
        } catch(\Exception $e) {
            echo $e->getMessage();
        }
    }


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

    
    private function registerNormal(){
        $form = new UserForm(['scenario' => 'register']);
        $form = $this->postLoad($form);

        $errors = [];

        if (!$form->validate()) {
            $errors['register-form'] = ActiveForm::validate($form);
        }

        if (!empty($errors)) {
            return self::getFirstError(ActiveForm::validate($form));
        }

        $transaction = Yii::$app->db->beginTransaction(); 

        try {
            if(!$form->account_id) $form->account_id = 0;
            // Yii::info($form,'carkee');
            // $user = User::create($form);
            $user = User::create($form, User::TYPE_MEMBER);

            $transaction->commit();

            Watchdog::carkeeLog('Carkee: @email - member registration', ['@email' => $user->email], $user);
            return [
                'code'        => self::CODE_SUCCESS,
                'message'     => 'Successfully registered',
                'accesstoken' => $user->auth_key,
            ];
        } catch (\Exception $e) {
            $transaction->rollBack();

            return [
                'code'    => self::CODE_ERROR,   
                'message' => $e->getMessage(),
            ];
        }
    }
    private function registerNoApproval()
    {
        $form = new UserForm(['scenario' => 'admin-add-carkee-member']);
        $form = $this->postLoad($form);
        
        if(empty($form->fullname)){ 
            $splitemail = explode("@",$form->email);
            $form->fullname = $splitemail[0];
        }
        if (!$form->validate()) return self::getFirstError(ActiveForm::validate($form));

        $transaction = Yii::$app->db->beginTransaction(); 

        try {
            if(!$form->account_id) $form->account_id = 0;
            $form->no_approval = 1;
            $form->verification_code = mt_rand(100000, 999999);
            if($form->club_code){
                $club = Account::find()->where(['club_code'=>$form->club_code])->one();
                if($club) $form->account_id = $club->account_id;
            }
            $user = User::create($form, User::TYPE_MEMBER);

            $transaction->commit();

            // Watchdog::carkeeLog('Carkee: @email - member registration', ['@email' => $user->email], $user);
            $envlink = Yii::$app->params['environment'] == 'production' ? Yii::$app->params['api.carkee.endpoint']['prod'] : Yii::$app->params['api.carkee.endpoint']['dev'];
            $apilink = "{$envlink}/site/verify-registration?user_id={$user->user_id}&account_id={$user->account_id}&verification_code={$form->verification_code}";
            Email::sendSendGridAPI($user->email, 'Thank you for your KARKEE registration', 'carkee-register-verify', $params=[
                'name' => !empty($user->fullname) ? $user->fullname : $user->firstname,
                'user_id'  => $user->user_id,
                'account_id'  => $user->account_id,
                'reg_codes'  => $form->verification_code,
                'client_email'  => $user->email,
                'club_email'    => "admin@carkee.sg",
                'club_name'     => "KARKEE",
                'api_link'      => $apilink,
                'club_link'     => "https://cpanel.carkee.sg",
                'club_logo'     => "https://qa.carkeeapi.carkee.sg/logo-edited.png"
            ]);
            
            return [
                'code'        => self::CODE_SUCCESS,
                'message'     => 'Successfully registered',
                'accesstoken' => $user->auth_key,
                'data'        => $user->data(),
            ];
        } catch (\Exception $e) {
            $transaction->rollBack();

            return [
                'code'    => self::CODE_ERROR,   
                'message' => $e->getMessage(),
            ];
        }
    }

    public function actionSignInCodes()
    {

        $code = Yii::$app->request->post('verification_code');
        $email = Yii::$app->request->post('email',null);

        $user = User::find()
                    ->leftJoin('user_settings', 'user_settings.user_id = user.user_id')
                    ->where(['user_settings.club_code' => $code])
                    ->andWhere(['email' => $email])
                    ->one();

        if(!$user){
            return [
                'code'    => self::CODE_ERROR,   
                'message' => "Invalid Email or Code",
            ];
        }

        

        if(!empty($user->user_settings) AND $user->user_settings->club_code == $code){
            $settings = Settings::find()->one();
            $superadmin = User::find()
                            ->where(['IN','status',[User::STATUS_APPROVED]])
                            ->andWhere(['account_id'=>0])
                            ->andWhere(['status'=>User::STATUS_APPROVED])
                            ->one();

            $user->confirmed_by = $superadmin->user_id;
            $user->approved_by = $superadmin->user_id;
            $user->approved_at = date('Y-m-d H:i:s');
            $user->status = User::STATUS_APPROVED;
        }

        $user->user_settings->verification_code = NULL;
        $user->user_settings->save();
        return [
            'code'        => self::CODE_SUCCESS,
            'message'     => 'Successfully Login with Codes',
            'accesstoken' => $user->auth_key,
            'data'        => $user->data(),
        ];
        
    }

    public function actionSecurityQuestions(){
        $account_id = Yii::$app->request->get('account_id',null);
        $qry = AccountSecurityQuestions::find();
        
        if($account_id) $qry->where(['account_id'=>$account_id]);
        
        $questions = $qry->all();
        
        if(!$questions) Helper::errorMessage("Empty!");

        $data = [];
        $i = 1;
        foreach($questions as $question){            
            $data[] = ['item' => $i++, 'question' => $question->data()];
        }
        return [
            'code'        => self::CODE_SUCCESS,
            'message'     => 'Successfully Retrieved Questions',
            'data'        => $data
        ];
    }

    public function actionQuestionsByClubCode() {

        $club_code = Yii::$app->request->get('code', null);

        $accountclub = Account::find()->where(['club_code'=>$club_code])->one();
        if (!$accountclub) return Helper::errorMessage('Invalid club code.');

        $accountsecurityquestion = AccountSecurityQuestions::find()->where(['account_id' => $accountclub->account_id]);

        $accountsecurityquestion->andWhere(['NOT IN', 'status', [AccountSecurityQuestions::STATUS_DELETED]]);
        $questions = $accountsecurityquestion->all();

        $data = [];
        $i = 1;
        foreach($questions as $question){
            $data[] = ['item' => $i++, 'question' => $question->data()];
        }

        return [
            'message'     => 'Successfully Retrieved Questions',
            'data'          => $data,
            'code'          => self::CODE_SUCCESS
        ];
    }

    public function actionClubRegistration(){

        $user = Yii::$app->user->identity;
        $form = new AccountMembershipForm;
        $form = $this->postLoad($form);
        
        $accountclub = Account::find()->where(['club_code'=>$form->club_code])->one();
        if(!$accountclub) Helper::errorMessage("Club Code don't exist! Please contact club admin to verify this issue. Thanks.");
        else $form->account_id = $accountclub->account_id;

        $checkacntquestions = AccountSecurityQuestions::find();
        $checkqstcnt = $checkacntquestions->where(['account_id'=>$form->account_id])->count();
        
        if($checkqstcnt == 0) $acntsecquestions = $checkacntquestions->where(['account_id'=>0]);
        else $acntsecquestions = $checkacntquestions->where(['account_id'=>$form->account_id]);
        
        $questionscount = $acntsecquestions->count();
        $secquestions = $acntsecquestions->all(); 

        $files = [];
        if(!empty($_FILES['answers'])) $files = $_FILES['answers'];

        $temp = [];
        if (!empty($files)) {           

            foreach($files['name'] as $key => $fname) {
                $temp['name']['answers'][] = $files['name'][$key];
                $temp['type']['answers'][] = $files['type'][$key];
                $temp['tmp_name']['answers'][] = $files['tmp_name'][$key];
                $temp['error']['answers'][] = $files['error'][$key];
                $temp['size']['answers'][] = $files['size'][$key];                
            }
            $_FILES['AccountMembershipForm'] = $temp;
        }
        
        $fileanswers = [];
        if(!empty($_FILES['AccountMembershipForm'])) $form->files = UploadedFile::getInstancesByName('answers');
        
        if($form->files){
            foreach($form->files as $fileans){
                $ansfilename = hash('crc32', $fileans->name) . time() . '.' . $fileans->getExtension();                
                $fileanswers[] = $ansfilename;
            }
        }        

        $questions_id = $form->question_id;            
        $answers = array_fill(0, $questionscount, NULL);

        $i = 0;
        foreach($secquestions as $csq){
            $key = array_search($csq->id, $questions_id);
            if (false !== $key){        
                if($csq->is_file_upload == 1){
                    if(!empty($fileanswers[$i])) $answers[$key] = $fileanswers[$i++];
                    else $answers[$key] = "Error in uploading file.";
                }else{
                    if(!empty($form->answers[$key])) $answers[$key] = $form->answers[$key];
                }                
            }         
        }
        
        // if (!empty($form->answers))
        // if (count($form->answers) != $questionscount) return Helper::errorMessage("Please don't leave some question unanswered.");
        // else 
        $form->answers = $answers;

        
         //if(count($form->answers) != $questionscount) Helper::errorMessage("Please don't leave it blank for all questions needed to answer as provided the club admins");
        if (!$form->validate()) return self::getFirstError(ActiveForm::validate($form));
    
        $accntmemusr = AccountMembership::find()->where(['account_id' => $form->account_id])->andWhere(['user_id'=>$user->user_id])->one();
        if($accntmemusr)  Helper::errorMessage("You have already a Club Registration for the Club you registering. Please contact Karkee Personnel for further details. Thanks!");
        
        if($user->account_id == $form->account_id) Helper::errorMessage("You are already a Registrant for the Club you registering. Please contact Karkee Personnel for further details. Thanks!");

        $transaction = Yii::$app->db->beginTransaction();

        try {
            
            $accountmembership = AccountMembership::Create($form, $user);            
            
            if(!empty($secquestions) AND !empty($questions_id)){
                $x = 0;
                foreach($secquestions as $csq){
                    $key = array_search($csq->id, $questions_id);
                    if (false !== $key){
                        $memsecans = new MemberSecurityAnswers;
                        $memsecans->account_membership_id = $accountmembership->id;
                        $memsecans->question_id = $questions_id[$key];
                        $memsecans->user_id = $user->user_id;
                        $memsecans->account_id = $form->account_id;                        
                        if($csq->is_file_upload == 1){                            
                            $form->filename = Yii::$app->params['dir_sec_questions'] . $form->answers[$key];
                            if(is_null($form->files)) $memsecans->answer = "File don't exist!";
                            else if ($form->files[$x] && !$form->files[$x++]->saveAs($form->filename)) $memsecans->answer = 'Error uploading the file';
                            else $memsecans->answer = $form->answers[$key];
                        }else{
                            $memsecans->answer = $form->answers[$key];
                        }                
                        $memsecans->save();
                    }         
                }
            }
            
            $transaction->commit();


            
            Email::sendEmailNotification(Yii::$app->params['admin.email'], 'KARKEE Member registration', 'admin-notification', $this->adminEmails(), $this->subEmails(), $this->superEmails(), $params=[
                'name'       => !empty($user->fullname) ? $user->fullname : $user->firstname,
                'heading'      => 'The following user has just submitted membership application',
                'email'         => $user->email,
                'client_email'  => $user->email,
                'club_email'    => "admin@carkee.sg",
                'club_name'     => "KARKEE",
                'club_link'     => "http://cpanel.carkee.sg",
                'club_logo'     => "http://qa.carkeeapi.carkee.sg/logo-edited.png",
                'api_link'      => (Yii::$app->params['environment'] == 'production' ? Yii::$app->params['api.carkee.endpoint']['prod'] : Yii::$app->params['api.carkee.endpoint']['dev'])
            ]);

            $acntmem = AccountMembership::find()->where(['user_id'=>$user->user_id])->one();
            if(!$acntmem) Helper::errorMessage("No Club Registration Found!");

            $data = [];
            foreach($acntmem->member_security_answers as $ans){
                $data[] = $ans->data();
            }
            return [
                'code'        => self::CODE_SUCCESS,   
                'message' => 'Successfully Submitted Club Registration',
                'data'      => $data
            ];
        } catch (\Exception $e) {
            $transaction->rollBack();

            return [
                'code'    => self::CODE_ERROR,   
                'message' => $e->getMessage(),
            ];
        }
    }
    public function actionRequestNewClub()
    {
        $user = Yii::$app->user->identity;
        $form = new UserForm(['scenario' => "request-new-club"]);
        $form = $this->postLoad($form);

        $tmp = [];
        $imgfield = 'club_logo_file';
        foreach($_FILES as $file) {
            $tmp['UserForm'] = [
                'name'     => [$imgfield => $file['name']],
                'type'     => [$imgfield => $file['type']],
                'tmp_name' => [$imgfield => $file['tmp_name']],
                'error'    => [$imgfield => $file['error']],
                'size'     => [$imgfield => $file['size']],
            ];
        }

        $_FILES = $tmp;
        
        if (!empty($_FILES['UserForm'])) $form->club_logo = UploadedFile::getInstance($form, $imgfield);
            
        if (!$form->validate()) return self::getFirstError(ActiveForm::validate($form));

        $transaction = Yii::$app->db->beginTransaction();
        try {

            
            if ($form->club_logo) {
                // $this->imageFile->saveAs('uploads/' . $this->imageFile->baseName . '.' . $this->imageFile->extension);

                $user->club_logo = hash('crc32', $form->club_logo->name) . time() . '.' . $form->club_logo->getExtension();
                
                $fileDestination = Yii::$app->params['dir_member'] . $user->club_logo;
                
                if (!$form->club_logo->saveAs($fileDestination)) {
                    return [
                        'code'    => self::CODE_ERROR,
                        'message' => 'Error uploading the file'
                    ];
                }
            }

            $user->brand_synopsis = $form->club_name;
            $user->save();

            $account = new Account;
            $account->contact_name = (!empty($form->contact_name) ? $form->contact_name : $user->fullname);
            $account->address = (!empty($form->address) ? $form->address : (!empty($user->add_1) ? $user->add_1 : $user->add_2));
            $account->email = (!empty($form->email) ? $form->email : $user->email);
            $account->company = $form->club_name;
            $account->company_full_name = (!empty($form->company_full_name) ? $form->company_full_name : $form->club_name);
            $account->logo = $user->club_logo;
            $account->status = Account::STATUS_PENDING;
            $account->save();

            $transaction->commit();

            Email::sendEmailNotification(Yii::$app->params['admin.email'], 'KARKEE Member registration', 'admin-notification', $this->adminEmails(), $this->subEmails(), $this->superEmails(), $params=[
                'name'       => !empty($user->fullname) ? $user->fullname : $user->firstname,
                'heading'      => 'The following user has requested for a New Club named - '. $account->company,
                'email'         => $user->email,
                'client_email'  => $user->email,
                'club_email'    => "admin@carkee.sg",
                'club_name'     => "KARKEE",
                'club_link'     => "http://cpanel.carkee.sg",
                'club_logo'     => "http://qa.carkeeapi.carkee.sg/logo-edited.png",
                'api_link'      => (Yii::$app->params['environment'] == 'production' ? Yii::$app->params['api.carkee.endpoint']['prod'] : Yii::$app->params['api.carkee.endpoint']['dev'])
            ]);

            return [
                'code'        => self::CODE_SUCCESS,   
                'message' => 'Successfully Requested New Club',
            ];
        } catch (\Exception $e) {
            $transaction->rollBack();

            return [
                'code'    => self::CODE_ERROR,   
                'message' => $e->getMessage(),
            ];
        }
    }

    public function actionRegister()
    {

        $form = new UserForm(['scenario' => 'register']);
        $form = $this->postLoad($form);

        $errors = [];

        if (!$form->validate()) {
            $errors['register-form'] = ActiveForm::validate($form);
        }

        if (!empty($errors)) {
            return self::getFirstError(ActiveForm::validate($form));
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $user = User::create($form, User::TYPE_MEMBER);

            $transaction->commit();

            return [
                'code'        => self::CODE_SUCCESS,
                'message'     => 'Successfully registered',
                'accesstoken' => $user->auth_key
            ];
        } catch (\Exception $e) {
            $transaction->rollBack();

            return [
                'code'    => self::CODE_ERROR,   
                'message' => $e->getMessage(),
            ];
        }
    }

    public function actionRegisterVendor()
    {
        $form = new UserForm(['scenario' => 'register-vendor']);
        $form = $this->postLoad($form);

        $errors = [];

        if (!$form->validate()) {
            $errors['register-form'] = ActiveForm::validate($form);
        }

        if (!empty($errors)) {
            return self::getFirstError(ActiveForm::validate($form));
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $user = User::create($form, User::TYPE_VENDOR);

            $transaction->commit();

            /**
             * Send email notificaton
             */
            // if(Yii::$app->params['environment'] == 'development'){

                $club_name = strtoupper($user->account->company);

                Email::sendSendGridAPI($user->email, 'Thank you for your '.$club_name.' registration', 'club-register', $params=[
                    'name' => !empty($user->fullname) ? $user->fullname : $user->firstname,
                    'user_id'  => $user->user_id,
                    'client_email'  => $user->email,
                    'club_email'    => $user->account->email,
                    'club_name'     => $club_name,
                    'club_link'     => $user->account->club_link,
                    'club_logo'     => $user->account->logo_link,
                    'api_link'      => (Yii::$app->params['environment'] == 'production' ? Yii::$app->params['api.carkee.endpoint']['prod'] : Yii::$app->params['api.carkee.endpoint']['dev'])
                ]);
            // }else{
            //     Email::send($user->email, 'Thank you for your registration', 'registration', $params=[
            //         'name' => !empty($user->fullname) ? $user->fullname : $user->firstname
            //     ]);
            // }

            Watchdog::carkeeLog('Clubs: @email - vendor registration', ['@email' => $user->email], $user);

            return [
                'code'        => self::CODE_SUCCESS,   
                'message'     => 'Successfully registered',
                'accesstoken' => $user->auth_key
            ];
        } catch (\Exception $e) {
            $transaction->rollBack();

            return [
                'code'    => self::CODE_ERROR,   
                'message' => $e->getMessage(),
            ];
        }
    }

    public function actionUpdateProfile()
    {
        $user = Yii::$app->user->identity;
        $step = Yii::$app->request->post('step');
        $maxStep = 5;

        $account = $user->account;

        if (in_array($step, [1, 2, 3, 5])){
            $form = new UserForm(['scenario' => "step{$step}-{$account->company}"]);
            $form = $this->postLoad($form);

            $errors = [];

            if (!$form->validate()) {
                $errors['user-form'] = ActiveForm::validate($form);
            }

            if (!empty($errors)) {
                return self::getFirstError(ActiveForm::validate($form));
            }
        } else {
            $form = new UserForm();
        }

        /**
         * Upload validation
         */
        if ($step == 4) {            
            if (empty($user->img_profile)) {
                return [
                    'code'    => self::CODE_ERROR,   
                    'message' => 'Please upload profile image',
                ];
            }elseif (empty($user->img_nric)) {
                return [
                    'code'    => self::CODE_ERROR,   
                    'message' => 'Please upload license or NRIC',
                ];
            }elseif (empty($user->img_insurance)) {
                return [
                    'code'    => self::CODE_ERROR,
                    'message' => 'Please upload insurance',
                ];
            }elseif ($user->are_you_owner != User::OWNER_YES AND empty($user->img_authorization)) {
                return [
                    'code'    => self::CODE_ERROR,   
                    'message' => 'Please upload authorization letter',
                ];
            }elseif (empty($user->img_log_card)) {
                return [
                    'code'    => self::CODE_ERROR,   
                    'message' => 'Please upload log card',
                ];                
            }
        }

        // if ($step == 5 AND empty($user->transfer_screenshot)) {
        //     return [
        //         'code'    => self::CODE_ERROR,   
        //         'message' => 'Please attach transfer screenshot',
        //     ];
        // }

        $transaction = Yii::$app->db->beginTransaction();
        try {

            switch($step) {
                case 1:
                    $fields = ['country', 'postal_code', 'unit_no', 'add_1', 'add_2', 'gender', 'birthday', 'nric', 'profession', 'company', 'annual_salary'];
                break;
                case 2:
                    $fields = ['chasis_number', 'plate_no', 'car_model', 'registration_code', 'are_you_owner', 'insurance_date'];
                break;
                case 3:
                    $fields = ['contact_person', 'emergency_code', 'emergency_no', 'relationship'];
                break;
                case 5:
                    // $fields = [];
                    $fields = [];
                break;
                default:
                    $fields = [];
            }

            if ($step < 1 OR $step > 5){
                throw new \yii\web\HttpException(404, 'Invalid step.');
            }

            foreach($fields as $field) $user->{$field} = $form->{$field};

            $user->step = $step + 1;

            if ($user->isIncomplete() AND $step == 5){
                
                Helper::pushNotificationFCM_ToTreasurer(UserFcmToken::NOTIF_TYPE_APP_REGISTRATION, "New Member Registration Request for Approval",($user->fullname ? $user->fullname : $user->firstname)." request account registration approval for " . ($user->account_id > 0 ? strtoupper($user->account->company) : "KARKEE"), $user->account_id);
                
                $user->status = User::STATUS_PENDING;
            }

            $user->save();

            $transaction->commit();

            $data = [
                'code'        => self::CODE_SUCCESS,   
                'message' => 'Successfully updated',
            ];

            if ($step == 4) {
                $data['details'][] = [
                    'label' => 'Member entrance fee (one-time)',
                    'amount' => 'SGD 100.00',
                ];

                $data['details'][] = [
                    'label' => 'Subscription fee\n(Joining between January to June)',
                    'amount' => 'SGD 150.00',
                ];

                // $data['details'][] = [
                //     'label' => 'For existing members, no fee required',
                //     'amount' => 'SGD 0.00',
                // ];

                $data['total_payable'] = 'SGD 250.00';

                $data['entity_eun']  = 'T17SS0002E';
                $data['entity_name'] = 'M CLUB OF SINGAPORE';
            }

            return $data;
        } catch (\Exception $e) {
            $transaction->rollBack();

            return [
                'code'    => self::CODE_ERROR,   
                'message' => $e->getMessage(),
            ];
        }
    }

    public function actionUpdateVendorOnboarding()
    {
        $user = Yii::$app->user->identity;
        $step = Yii::$app->request->post('step');
        $maxStep = 3;

        if (in_array($step, [1, 2, 3])){
            $form = new UserForm(['scenario' => "vendor-step{$step}"]);
            $form = $this->postLoad($form);

            $errors = [];

            if (!$form->validate()) {
                $errors['user-form'] = ActiveForm::validate($form);
            }

            if (!empty($errors)) {
                return self::getFirstError(ActiveForm::validate($form));
            }
        } else {
            $form = new UserForm();
        }

        if ($step == 3 AND empty($user->transfer_screenshot)) {
            return [
                'code'    => self::CODE_ERROR,   
                'message' => 'Please attach transfer screenshot',
            ];
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {

            switch($step) {
                case 1:
                    $fields = ['telephone_code', 'telephone_no', 'company_email', 'company_country', 'company_postal_code', 'company_add_1', 'company_unit_no', 'company_add_2'];
                break;
                case 2:
                    $fields = ['gender', 'birthday', 'nric', 'country', 'postal_code', 'add_1', 'unit_no', 'add_2'];
                break;
                case 3:
                    // $fields = [];
                    $fields = [];
                break;
                default:
                    $fields = [];
            }

            if ($step < 1 OR $step > 3){
                throw new \yii\web\HttpException(404, 'Invalid step.');
            }

            foreach($fields as $field) $user->{$field} = $form->{$field};

            $user->step = $step + 1;

            if ($user->isIncomplete() AND $step == 3){

                Helper::pushNotificationFCM_ToTreasurer(UserFcmToken::NOTIF_TYPE_APP_REGISTRATION, "New Vendor Registration Request for Approval",($user->fullname ? $user->fullname : $user->firstname)." request account registration approval for " . ($user->account_id > 0 ? strtoupper($user->account->company) : "KARKEE"), $user->account_id);
                
                $user->status = User::STATUS_PENDING;
            }

            $user->save();

            $transaction->commit();

            $data = [
                'code'        => self::CODE_SUCCESS,   
                'message' => 'Successfully updated',
            ];

            if ($step == 2) {
                $data['details'][] = [
                    'label' => 'Member entrance fee (one-time)',
                    'amount' => 'SGD 100.00',
                ];

                $data['details'][] = [
                    'label' => 'Subscription fee\n(Joining between January to June)',
                    'amount' => 'SGD 150.00',
                ];

                $data['total_payable'] = 'SGD 250.00';

                $data['entity_eun']  = 'T17SS0002E';
                $data['entity_name'] = 'M CLUB OF SINGAPORE';
            }

            return $data;
        } catch (\Exception $e) {
            $transaction->rollBack();

            return [
                'code'    => self::CODE_ERROR,   
                'message' => $e->getMessage(),
            ];
        }
    }

    public function actionUpdatePersonalProfile()
    {
        $user = Yii::$app->user->identity;

        $account = $user->account;

        $form = new UserForm(['scenario' => "edit_member-{$account->company}"]);
        $form = $this->postLoad($form);

        $errors = [];

        if (!$form->validate()) {
            $errors['user-form'] = ActiveForm::validate($form);
        }

        if (!empty($errors)) {
            return self::getFirstError(ActiveForm::validate($form));
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {

            $fields = ['fullname', 'country', 'postal_code', 'unit_no', 'add_1', 'add_2', 'gender', 'birthday', 'nric', 'profession', 'company', 'annual_salary', 'contact_person', 'emergency_no', 'emergency_code', 'relationship', 'about'];

            foreach($fields as $field) $user->{$field} = $form->{$field};

            $user->save();
            $transaction->commit();

            return [
                'code'        => self::CODE_SUCCESS,   
                'message' => 'Successfully updated',
            ];
        } catch (\Exception $e) {
            $transaction->rollBack();

            return [
                'code'    => self::CODE_ERROR,   
                'message' => $e->getMessage(),
            ];
        }
    }

    public function actionBrandSynopsis()
    {
        $user = Yii::$app->user->identity;

        $form = new UserForm(['scenario' => "brand-synopsis"]);
        $form = $this->postLoad($form);

        $errors = [];

        if (!$form->validate()) {
            $errors['user-form'] = ActiveForm::validate($form);
        }

        if (!empty($errors)) {
            return self::getFirstError(ActiveForm::validate($form));
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {

            // $fields = ['brand_synopsis'];

            // foreach($fields as $field) $user->{$field} = $form->{$field};
            $user->brand_synopsis = $form->brand_synopsis;
            $user->save();
            $transaction->commit();

            return [
                'code'        => self::CODE_SUCCESS,   
                'message' => 'Successfully Saved Brand Synopsis',
            ];
        } catch (\Exception $e) {
            $transaction->rollBack();

            return [
                'code'    => self::CODE_ERROR,   
                'message' => $e->getMessage(),
            ];
        }
    }

    public function actionUpdateVendorProfile()
    {
        $user = Yii::$app->user->identity;

        if (!$user->isVendor()) {
            return [
                'code'    => self::CODE_ERROR,   
                'message' => 'You are not a vendor.',
            ];
        }

        $form = new UserForm(['scenario' => 'edit_vendor']);
        $form = $this->postLoad($form);

        $errors = [];

        if (!$form->validate()) {
            $errors['user-form'] = ActiveForm::validate($form);
        }

        if (!empty($errors)) {
            return self::getFirstError(ActiveForm::validate($form));
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $fields = [
                'company', 'telephone_code', 'telephone_no', 'company_email', 'company_country', 'company_postal_code', 'company_add_1', 'company_unit_no', 'company_add_2', 
                'gender', 'fullname', 'birthday', 'nric', 'country', 'postal_code', 'add_1', 'unit_no', 'add_2',
                'about'
            ];

            foreach($fields as $field) $user->{$field} = $form->{$field};

            $user->save();
            $transaction->commit();

            return [
                'code'        => self::CODE_SUCCESS,   
                'message' => 'Successfully updated',
            ];
        } catch (\Exception $e) {
            $transaction->rollBack();

            return [
                'code'    => self::CODE_ERROR,   
                'message' => $e->getMessage(),
            ];
        }
    }

    public function actionUpdateVehicle()
    {
        $user = Yii::$app->user->identity;
        $account = $user->account;

        $form = new UserForm(['scenario' => "edit_vehicle-{$account->company}"]);
        $form = $this->postLoad($form);

        $errors = [];

        if (!$form->validate()) {
            $errors['user-form'] = ActiveForm::validate($form);
        }

        if (!empty($errors)) {
            return self::getFirstError(ActiveForm::validate($form));
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $fields = ['chasis_number', 'plate_no', 'car_model', 'registration_code', 'insurance_date'];

            foreach($fields as $field) $user->{$field} = $form->{$field};

            $user->save();
            $transaction->commit();

            return [
                'code'        => self::CODE_SUCCESS,   
                'message' => 'Successfully updated',
            ];
        } catch (\Exception $e) {
            $transaction->rollBack();

            return [
                'code'    => self::CODE_ERROR,   
                'message' => $e->getMessage(),
            ];
        }
    }

    public function actionLogin()
    {
        $form = new LoginForm;
        $form = $this->postLoad($form);
        
        $errors = [];

        if (!$form->validate()) {
            $errors = ActiveForm::validate($form);
        }

        if (!empty($errors)) {
            return self::getFirstError(ActiveForm::validate($form));
        }

        $user = Yii::$app->user->identity;
        
        if($user->user_fcm){ 
            $userfcm = UserFcmToken::find()->where(['user_id'=>$user->user_id])->andWhere(['account_id'=>$user->account_id])->one();
        }else{
            $userfcm = new UserFcmToken;
            $userfcm->user_id = $user->user_id; 
            $userfcm->account_id = $user->account_id; 
        }

               
        if($form->fcm_token) $userfcm->fcm_token = $form->fcm_token;
        if(!is_null($form->fcm_topics)) $userfcm->fcm_topics = $form->fcm_topics;

        $userfcm->save();
        
        return [
            'code'        => self::CODE_SUCCESS,   
            'accesstoken' => $user->auth_key,
            'step'        => $user->step,
        ];
    }

    public function actionLoginUiid()
    {
        $form = new LoginUiidForm(['scenario' => 'uiid']);
        $form = $this->postLoad($form);

        $errors = [];


        if (!$form->validate()) {
            $errors = ActiveForm::validate($form);
        }

        if (!empty($errors)) {
            return self::getFirstError(ActiveForm::validate($form));
        }

        $user = Yii::$app->user->identity;
        
        if($user->user_fcm){ 
            $userfcm = UserFcmToken::find()->where(['user_id'=>$user->user_id])->andWhere(['account_id'=>$user->account_id])->one();
        }else{
            $userfcm = new UserFcmToken;
            $userfcm->user_id = $user->user_id; 
            $userfcm->account_id = $user->account_id; 
        }

               
        if($form->fcm_token) $userfcm->fcm_token = $form->fcm_token;
        if(!is_null($form->fcm_topics)) $userfcm->fcm_topics = $form->fcm_topics;

        $userfcm->save();
        
        return [
            'code'        => self::CODE_SUCCESS,   
            'accesstoken' => $user->auth_key,
            'step'        => $user->step,
        ];
    }

    public function actionLoginBiometric()
    {
        $form = new LoginUiidForm(['scenario' => 'uiid']);
        $form = $this->postLoad($form);

        $errors = [];


        if (!$form->validate()) {
            $errors = ActiveForm::validate($form);
        }

        if (!empty($errors)) {
            return self::getFirstError(ActiveForm::validate($form));
        }

        $user = Yii::$app->user->identity;
        
        if($user->user_fcm){ 
            $userfcm = UserFcmToken::find()->where(['user_id'=>$user->user_id])->andWhere(['account_id'=>$user->account_id])->one();
        }else{
            $userfcm = new UserFcmToken;
            $userfcm->user_id = $user->user_id; 
            $userfcm->account_id = $user->account_id; 
        }

               
        if($form->fcm_token) $userfcm->fcm_token = $form->fcm_token;
        if(!is_null($form->fcm_topics)) $userfcm->fcm_topics = $form->fcm_topics;

        $userfcm->save();
        
        return [
            'code'        => self::CODE_SUCCESS,   
            'accesstoken' => $user->auth_key,
            'step'        => $user->step,
        ];
    }

    public function actionLoginFaceId()
    {
        $form = new LoginUiidForm(['scenario' => 'uiid']);
        $form = $this->postLoad($form);

        $errors = [];


        if (!$form->validate()) {
            $errors = ActiveForm::validate($form);
        }

        if (!empty($errors)) {
            return self::getFirstError(ActiveForm::validate($form));
        }

        $user = Yii::$app->user->identity;
        
        if($user->user_fcm){ 
            $userfcm = UserFcmToken::find()->where(['user_id'=>$user->user_id])->andWhere(['account_id'=>$user->account_id])->one();
        }else{
            $userfcm = new UserFcmToken;
            $userfcm->user_id = $user->user_id; 
            $userfcm->account_id = $user->account_id; 
        }

               
        if($form->fcm_token) $userfcm->fcm_token = $form->fcm_token;
        if(!is_null($form->fcm_topics)) $userfcm->fcm_topics = $form->fcm_topics;

        $userfcm->save();
                
        return [
            'code'        => self::CODE_SUCCESS,   
            'accesstoken' => $user->auth_key,
            'step'        => $user->step,
        ];
    }

    public function actionInfo()
    {
        $user = Yii::$app->user->identity;

        // if ($user->isMembershipExpire()){
        //     $user->generateAuthKey();
        //     $user->save();
        // }

        $data = $user->data();

        

        return [
            'code' => self::CODE_SUCCESS,   
            'data' => $data
        ];
    }

    public function actionUpdateEmail()
    {
        $user    = Yii::$app->user->identity;
        $form = new \common\forms\MemberEmailForm();
        $form = $this->postLoad($form);

        $form->user = $user;

        $errors = [];

        if (!$form->validate()) {
            $errors = ActiveForm::validate($form);
        }

        if (!empty($errors)) {
            return self::getFirstError(ActiveForm::validate($form));
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $user->email = $form->email;
            $user->save();

            $transaction->commit();

            return [
                'code'    => self::CODE_SUCCESS,   
                'message' => 'Successfully updated',
            ];
        } catch (\Exception $e) {
            $transaction->rollBack();

            return [
                'code'    => self::CODE_ERROR,   
                'message' => $e->getMessage(),
            ];
        }
    }

    public function actionUpdateMobile()
    {
        $user    = Yii::$app->user->identity;
        $form = new \common\forms\MemberMobileForm();
        $form = $this->postLoad($form);

        $form->user = $user;

        $errors = [];

        if (!$form->validate()) {
            $errors = ActiveForm::validate($form);
        }

        if (!empty($errors)) {
            return self::getFirstError(ActiveForm::validate($form));
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $user->mobile_code = $form->mobile_code;
            $user->mobile      = $form->mobile;
            $user->save();

            $transaction->commit();

            return [
                'code'    => self::CODE_SUCCESS,   
                'message' => 'Successfully updated',
            ];
        } catch (\Exception $e) {
            $transaction->rollBack();

            return [
                'code'    => self::CODE_ERROR,   
                'message' => $e->getMessage(),
            ];
        }
    }

    public function actionUpdatePassword()
    {
        $user    = Yii::$app->user->identity;
        $form = new \common\forms\MemberPasswordForm();
        $form = $this->postLoad($form);

        $form->user = $user;

        $errors = [];

        if (!$form->validate()) {
            $errors = ActiveForm::validate($form);
        }

        if (!empty($errors)) {
            return self::getFirstError(ActiveForm::validate($form));
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $user->setPassword($form->password_new);
            $user->save();

            $transaction->commit();

            return [
                'code'    => self::CODE_SUCCESS,   
                'message' => 'Successfully updated',
            ];
        } catch (\Exception $e) {
            $transaction->rollBack();

            return [
                'code'    => self::CODE_ERROR,   
                'message' => $e->getMessage(),
            ];
        }
    }

    public function actionVerifyPassword()
    {
        $user    = Yii::$app->user->identity;
        $password = Yii::$app->request->post('password');

        if (empty($password) OR !$user->validatePassword($password)) {
            return [
                'code'    => self::CODE_ERROR,   
                'message' => 'Invalid password',
            ];
        } else {
            return [
                'code'    => self::CODE_SUCCESS,   
                'message' => 'Valid password',
            ];
        }
    }

    public function actionUpdatePin()
    {
        $user    = Yii::$app->user->identity;
        $form = new \common\forms\PinForm();
        $form = $this->postLoad($form);

        $errors = [];

        if (!$form->validate()) {
            $errors = ActiveForm::validate($form);
        }

        if (!empty($errors)) {
            return self::getFirstError(ActiveForm::validate($form));
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $user->setPin($form->pin);
            $user->save();

            $transaction->commit();

            return [
                'code'    => self::CODE_SUCCESS,
                'message' => 'Successfully updated',
            ];
        } catch (\Exception $e) {
            $transaction->rollBack();

            return [
                'code'    => self::CODE_ERROR,   
                'message' => $e->getMessage(),
            ];
        }
    }

    public function actionRedeemList()
    {
        $user   = Yii::$app->user->identity;
        $page   = Yii::$app->request->get('page', 1);
        $status = Yii::$app->request->get('status');

        $_GET['hashUrl'] = '#list/filter/';

        foreach(['status', 'sort', 'page'] as $key) {
            $_GET[$key] = Yii::$app->request->get($key);
        }

        if (empty($_GET['sort'])) $_GET['sort'] = 'created_at';

        $sort = new Sort([
            'attributes' => [
                'created_at' => [
                    'desc' => ['created_at' => SORT_DESC],
                    'asc' => ['created_at' => SORT_ASC],
                    'default' => SORT_DESC,
                    'label' => 'Date',
                ],                
            ],
        ]);

        $sort->route = 'category#list/filter';

        $qry = ItemRedeem::find()->where('user_id = ' . $user->user_id);

        if ($status) {
            $qry->andWhere(['status' => $status]);
        }

        $qry->orderBy($sort->orders);

        $dataProvider = new MyActiveDataProvider([
            'query' => $qry,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        /**
         * Parse data
         */
        $data = [];

        foreach($dataProvider->getModels() as $redeem) {
            $data[] = [
                'redeem' => $redeem->data(ItemRedeem::REDEEM_INFO_VENDOR),
                'item'   => $redeem->item->data(),
            ];
        }

        return [
            'success'     => TRUE,
            'data'        => $data,
            'count'       => $dataProvider->getCount(),
            'currentPage' => (int)$page,
            'pageCount'   => ceil($dataProvider->pagination->totalCount / $dataProvider->pagination->pageSize),
            'code'        => self::CODE_SUCCESS,
        ];
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
        } catch(\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function actionUploadDoc()
    {
        $user = Yii::$app->user->identity;

        $field = Yii::$app->request->post('field');
        $tmp = [];

        foreach($_FILES as $file) {
            $tmp['UserForm'] = [
                'name'     => [$field => $file['name']],
                'type'     => [$field => $file['type']],
                'tmp_name' => [$field => $file['tmp_name']],
                'error'    => [$field => $file['error']],
                'size'     => [$field => $file['size']],
            ];
        }

        $_FILES = $tmp;

        $form = new UserForm(['scenario' => 'upload']);
        $form = $this->postLoad($form);

        $uploadFile = UploadedFile::getInstance($form, $field);

        if ($uploadFile) {
            // $this->imageFile->saveAs('uploads/' . $this->imageFile->baseName . '.' . $this->imageFile->extension);

            $newFilename = hash('crc32', $uploadFile->name) . time() . '.' . $uploadFile->getExtension();
            
            $fileDestination = Yii::$app->params['dir_member'] . $newFilename;

            if (!$uploadFile->saveAs($fileDestination)) {
                return [
                    'code'    => self::CODE_ERROR,
                    'message' => 'Error uploading the file'
                ];
            }

            $user->{$field} = $newFilename;
            $user->save();
            if($user->account_id == 1 || $user->account_id == 8){
                if($field == 'img_log_card'){
                    $user_log = [
                        'user_id' => $user->user_id,
                        'type' => 1, //member
                        'log_card' => $newFilename,
                    ];
                    UserLog::create($user_log);
                }
            }
            return [
                'code'    => self::CODE_SUCCESS,
                'message' => 'Successfully uploaded'
            ];
        }

        return [
            'code'    => self::CODE_ERROR,
            'message' => 'Invalid file'
        ];
    }

    public function actionDoc()
    {
        $loginUser = Yii::$app->user->identity;
        $field     = Yii::$app->request->get('f');
        $user_id   = Yii::$app->request->get('u');
        $user      = User::findOne($user_id);

        /*
         * only allow public view have profiles
         */
        if (in_array($field, ['img_profile', 'img_vendor', 'company_logo','club_logo','brand_guide'])){ 
            // if (!$user OR $user->account_id != $loginUser->account_id OR !array_key_exists($field, $user->attributes)) {
            if (!$user OR !array_key_exists($field, $user->attributes)) {
                echo "Invalid file";
                return;
            }
        }

        try{
            $dir = Yii::$app->params['dir_member'];

            /**
             * Load default profile
             */
            if (in_array($field, ['img_profile', 'img_vendor', 'company_logo','club_logo','brand_guide']) AND empty($user->{$field})) $user->{$field} = 'default-profile.png';

            Yii::$app->response->sendFile($dir . $user->{$field}, $user->{$field}, ['inline' => TRUE]);
        } catch(\Exception $e) {
            echo $e->getMessage();
        }
    }    

    public function actionForgotPassword()
    {
        $form = new \common\forms\MemberResetPasswordForm(['scenario' => 'forgot']);
        $form = $this->postLoad($form);

        $errors = [];

        if (!$form->validate()) {
            $errors = ActiveForm::validate($form);
        }

        if (!empty($errors)) {
            return self::getFirstError(ActiveForm::validate($form));
        }

        $user = User::find()
        ->where(['account_id' => $form->account_id])
        ->andWhere(['email' => $form->email])
        ->one();

        if (!$user) {
            return [
                'code'    => self::CODE_ERROR,   
                'message' => 'Account not found.',
            ];
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            // $user->reset_code = (Yii::$app->params['environment'] == 'development') ? 123123 : mt_rand(100000, 999999);
            $user->reset_code = mt_rand(100000, 999999);
            $user->save();

            $transaction->commit();
            // if(Yii::$app->params['environment'] == 'development'){
                $club_name = strtoupper($user->account->company);

                Email::sendSendGridAPI($user->email, $club_name.' - Reset password', 'club-reset-password', $params=[
                    'name'          => !empty($user->fullname) ? $user->fullname : $user->firstname,
                    'reset_code'    => $user->reset_code,
                    'client_email'  => $user->email,
                    'club_email'    => $user->account->email,
                    'club_name'     => $club_name,
                    'club_link'     => $user->account->club_link,
                    'club_logo'     => $user->account->logo_link,
                    'api_link'      => (Yii::$app->params['environment'] == 'production' ? Yii::$app->params['api.carkee.endpoint']['prod'] : Yii::$app->params['api.carkee.endpoint']['dev'])
                ]);
            // }else{
            //     Email::send($user->email, 'MCLUB - Reset password', 'mclub-reset-password', $params=[
            //         'name'       => !empty($user->fullname) ? $user->fullname : $user->firstname,
            //         'reset_code' => $user->reset_code,
            //         'client_email' => $user->email
            //     ]);
            // }
            return [
                'code'       => self::CODE_SUCCESS,   
                'message'    => 'Code has been sent to your email address.',
                'email'      => $user->email,
                'account_id' => $user->account_id,
            ];

        } catch (\Exception $e) {
            $transaction->rollBack();

            return [
                'code'    => self::CODE_ERROR,   
                'message' => $e->getMessage(),
            ];
        }        
    }

    public function actionForgotPasswordConfirmCode()
    {
        $form = new \common\forms\MemberResetPasswordForm(['scenario' => 'confirm']);
        $form = $this->postLoad($form);

        $errors = [];

        if (!$form->validate()) {
            $errors = ActiveForm::validate($form);
        }

        if (!empty($errors)) {
            return self::getFirstError(ActiveForm::validate($form));
        }

        $user = User::find()
        ->where(['account_id' => $form->account_id])
        ->andWhere(['email' => $form->email])
        ->andWhere(['reset_code' => $form->reset_code])
        ->one();

        if (!$user) {
            return [
                'code'    => self::CODE_ERROR,   
                'message' => 'Invalid code.',
            ];
        }

        return [
            'code'       => self::CODE_SUCCESS,   
            'email'      => $user->email,
            'account_id' => $user->account_id,
            'reset_code' => $user->reset_code,
        ];
    }

    public function actionForgotPasswordUpdate()
    {
        $form = new \common\forms\MemberResetPasswordForm(['scenario' => 'update']);
        $form = $this->postLoad($form);

        $errors = [];

        if (!$form->validate()) {
            $errors = ActiveForm::validate($form);
        }

        if (!empty($errors)) {
            return self::getFirstError(ActiveForm::validate($form));
        }

        $user = User::find()
        ->where(['account_id' => $form->account_id])
        ->andWhere(['email' => $form->email])
        ->andWhere(['reset_code' => $form->reset_code])
        ->one();

        if (!$user) {
            return [
                'code'    => self::CODE_ERROR,   
                'message' => 'Account not found.',
            ];
        }

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

            return [
                'code'    => self::CODE_ERROR,   
                'message' => $e->getMessage(),
            ];
        }
    }

    public function actionUpdateTopic(){
        
        $user = Yii::$app->user->identity;
        $topic = Yii::$app->request->get('topic',null);
        $fcm_token = Yii::$app->request->get('fcm_token',null);
        $fcm_topics = Yii::$app->request->get('fcm_topics',null);
        
        if(!$topic){
            return [
                'code'    => self::CODE_ERROR,   
                'message' => 'Invalid Topic value',
            ];
        }

        if($user->user_fcm){ 
            $userfcm = UserFcmToken::find()->where(['user_id'=>$user->user_id])
                                            ->andWhere(['account_id' => $user->account_id])
                                            ->one();
        }else{
            $userfcm = new UserFcmToken;
            $userfcm->user_id = $user->user_id; 
            $userfcm->account_id = $user->account_id; 
        }

               
        if($fcm_token) $userfcm->fcm_token = $fcm_token;
        if($fcm_topics) $userfcm->fcm_topics = $fcm_topics;

        $userfcm->save();

        return [
            'code'    => self::CODE_SUCCESS,   
            'message' => 'Successfully Updated Topic',
        ];
    }

    public function actionOptions()
    {
        $user = Yii::$app->user->identity;

        if ($user->account->company == 'p9club'){
            return $this->p9clubOptions();
        } else {
            return $this->mclubOptions();
        }
    }

public function mclubOptions()
    {        
        $html = "<span style=\"font-family: 'Helvetica-Bold'; font-size: 16; color:black;\">
    

Entrance fee:
</br>

</span>

<span style=\"font-family: 'Helvetica'; font-size: 16; color:black;\">
  

$100 SGD (one-time)
</br>
</br>

</span>

    <span style=\"font-family: 'Helvetica-Bold'; font-size: 16; color:black;\">
  

Subscription fee:
</br>

</span>

<span style=\"font-family: 'Helvetica'; font-size: 16; color:black;\">


$150 SGD (Joining between January to June)
</br>

</span>

    <span style=\"font-family: 'Helvetica-Bold'; font-size: 16; color:red;\">

- total $250 payable
</br>
</br>
</span>

<span style=\"font-family: 'Helvetica'; font-size: 16; color:black;\">
$75 SGD (Joining between July to December)
</br>
</span>


    <span style=\"font-family: 'Helvetica-Bold'; font-size: 16; color:red;\">

- total $175 payable
</br>
</br>
</span>


<span style=\"font-family: 'Helvetica-Bold'; font-size: 16; color:black;\">

Subscription fees renewal:
</br>
</span>

<span style=\"font-family: 'Helvetica'; font-size: 16; color:black;\">

$150 SGD (one year)
</br>
payable at the end of every calendar year.
</span>
<span style=\"font-family: 'Helvetica-Bold'; font-size: 16; color:red;\">

Please note there is no pro-rated subscription fees for annual renewal
</br>
</br>
</span>

<span style=\"font-family: 'Helvetica'; font-size: 16; color:black;\">

All Membership fees payment can be made by fund transfer to our club???s account.
</br>
</span>

    <span style=\"font-family: 'Helvetica-Bold'; font-size: 16; color:red;\">
Bank: OCBC
</br>
</span>

    <span style=\"font-family: 'Helvetica-Bold'; font-size: 16; color:red;\">

Acc No.: 712110253001
</br>
</span>";
        
        $user = Yii::$app->user->identity;

        return [
            'code'            => self::CODE_SUCCESS,
            'member_id'       => $user->memberId(),
            'payment_summary' => $html,
            'owner_options'   => User::ownerOptions(TRUE),
            'relationships'   => User::relationships(TRUE),
            'salaries'        => User::salaries(TRUE),
            'total_payable'   => 'SGD 250.00',
            'entity_eun'      => 'T17SS0002E',
            'entity_name'     => 'M CLUB OF SINGAPORE',
            'details' => [
                [
                    'label' => 'Member entrance fee (one-time)',
                    'amount' => 'SGD 100.00',
                ],
                [
                    'label' => 'Subscription fee\n(Joining between January to June)',
                    'amount' => 'SGD 150.00',
                ]
            ]
        ];
    } 

    public function p9clubOptions()
    {        
        $html = "
<span style=\"font-family: 'Helvetica-Bold'; font-size: 16; color:black;\">
    Entrance fee:
</span>

</br>

<span style=\"font-family: 'Helvetica'; font-size: 16; color:black;\">
    $8 SGD (one-time)
</span>

</br>
</br>

<span style=\"font-family: 'Helvetica-Bold'; font-size: 16; color:black;\">
    Subscription fee:
</span>

</br>

<span style=\"font-family: 'Helvetica'; font-size: 16; color:black;\">
    $120 SGD (Joining between January to June)
</span>

</br>

<span style=\"font-family: 'Helvetica-Bold'; font-size: 16; color:red;\">
    - total $128 payable
</span>

<br />

<span style=\"font-family: 'Helvetica-Bold'; font-size: 16; color:black;\">
    Subscription fees renewal:
</span>

</br>

<span style=\"font-family: 'Helvetica'; font-size: 16; color:black;\">
    $150 SGD (one year)
    </br>
    payable at the end of every calendar year.
</span>

<span style=\"font-family: 'Helvetica-Bold'; font-size: 16; color:red;\">
    Please note there is no pro-rated subscription fees for annual renewal
</span>

</br>
</br>

<span style=\"font-family: 'Helvetica'; font-size: 16; color:black;\">
    All Membership fees payment can be made by fund transfer to our club???s account.
</span>

</br>

<span style=\"font-family: 'Helvetica-Bold'; font-size: 16; color:red;\">
    Bank: OCBC
</span>

</br>

<span style=\"font-family: 'Helvetica-Bold'; font-size: 16; color:red;\">
    Acc No.: 712110253001
</span>";
        
        $user = Yii::$app->user->identity;

        return [
            'code'            => self::CODE_SUCCESS,
            'member_id'       => $user->memberId(),
            'payment_summary' => $html,
            'owner_options'   => User::ownerOptions(TRUE, 'p9club'),
            'relationships'   => User::relationships(TRUE),
            'salaries'        => User::salaries(TRUE),
            'total_payable'   => 'SGD 128.00',
            'entity_eun'      => 'T19SS0171A',
            'entity_name'     => 'P9 Club',
            'details' => [
                [
                    'label' => 'Member entrance fee (one-time)',
                    'amount' => 'SGD 8.00',
                ],
                [
                    'label' => 'Annual Subscription fee',
                    'amount' => 'SGD 120.00',
                ]
            ]
        ];
    }       


    public function actionSocialMediaCheck(){
        $accessToken = Yii::$app->request->post('sm_token','');
        $loginType = (int) Yii::$app->request->post('login_type',1);
        $accountid = (int) Yii::$app->request->post('account_id', 0);
        // $form = new SocialMediaCheckForm;
        // $form = $this->postLoad($form);
        switch($loginType){
            case 1 :    return $this->fbUserCheck($accessToken,$accountid);
            case 2 :    return $this->googleUserCheck($accessToken,$accountid);
            case 3 :    return $this->appleUserCheck($accessToken,$accountid);
            default:
                        return [
                            'code'    => self::CODE_ERROR,   
                            'message' => 'Invalid Details'
                        ];
        }
    }

    private function fbUserCheck($accessToken = null,$accountid){
   
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
                
                $user = User::find()
                                    ->where(['email' => $userfb->getEmail()])
                                    ->andWhere(['account_id' => $accountid])
                                    ->one();
                    
                return [
                    'code'          => self::CODE_SUCCESS,   
                    'facebook'      => $userfb,
                    'in_db'         => ($user ? true : false),
                    'user_id'       => ($user ? $user->user_id : null),
                    'access_token'  => ($user && $user->auth_key) ? $user->auth_key : Yii::$app->security->generateRandomString()
                ];

            } else { // if ($helper->getError()) {
                return [
                    'code'    => self::CODE_ERROR,   
                    // 'message' => $helper->getErrorReason(),
                    'message' => 'Invalid Access Token',
                ];
            }
            
        } catch(\Facebook\Exceptions\FacebookSDKException $e) {
            return [
                'code'    => self::CODE_ERROR,   
                // 'message' => $e->getMessage(),
                'message' => 'Invalid Access Token'
            ];
            
        }catch(\Exception $e){
            return [
                'code'    => self::CODE_ERROR,   
                'message' => $e->getMessage()
            ];
        }
        
        return [
            'code'    => self::CODE_ERROR,   
            'message' => 'Invalid Details'
        ];
    }

    private function googleUserCheck($accessToken = null,$accountid){
        try{
            $dir = Yii::$app->params['dir_socialmedia_credentials'];
            $filegoogleconfig = $dir . 'google_client_config.json';
            // Get the API client and construct the service object.
            $client = new Google_Client();
            $accessToken = $accessToken ? $accessToken : ($client->verifyIdToken() ? $client->verifyIdToken() : NULL);
            $client->setAuthConfig($filegoogleconfig);
            $client->setAccessType('offline');
            if($accessToken) $client->setAccessToken($accessToken);
            
            if($client){
                $oauth2 = new Google_Service_Oauth2($client);
                $googleUser = $oauth2->userinfo->get();

                // $user = User::findOne(['email' => $googleUser->email]);
                $user = User::find()
                                ->where(['email' => $googleUser->email])
                                ->andWhere(['account_id' => $accountid])
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
            return [
                'code'    => self::CODE_ERROR,   
                'message' => 'Invalid Access Token'
            ];
        }
        return [
            'code'    => self::CODE_ERROR,   
            'message' => 'Invalid Details'
        ];
    }
    
    public function actionAppleUserCheckRedirect(){

        return [
            'code'    => self::CODE_SUCCESS,   
            'message' => 'Apple Redirect Success'
        ]; 
    }

    private function appleUserCheck($accessToken = null,$accountid){
        
        $dir = Yii::$app->params['dir_socialmedia_credentials'];
        $fileapplep8 = $dir . 'AuthKey_46F938U5QW.p8';
        $client_id = 'sg.carkee.appleid';
        $teamId = '93K7CUUG7Z';
        $redirect_url = 'https://qa.carkeeapi.carkee.sg/member/apple-user-check-redirect';
       
        // $client_secret = 'eyJraWQiOiI4NkQ4OEtmIiwiYWxnIjoiUlMyNTYifQ.eyJpc3MiOiJodHRwczovL2FwcGxlaWQuYXBwbGUuY29tIiwiYXVkIjoic2cuY2Fya2VlLmFwcCIsImV4cCI6MTYxNTYyNDEyOCwiaWF0IjoxNjE1NTM3NzI4LCJzdWIiOiIwMDAzNTcuZWExNmQ5ZDAzNTFkNDc0N2I2NzQyZWMxOGE5YzhiZTkuMDYwMiIsImNfaGFzaCI6Ikx6aG00Vi02SkNmeHdneC1WUDBJbkEiLCJlbWFpbCI6InBoYW1uaGF0aWV1dGh1eUBnbWFpbC5jb20iLCJlbWFpbF92ZXJpZmllZCI6InRydWUiLCJhdXRoX3RpbWUiOjE2MTU1Mzc3MjgsIm5vbmNlX3N1cHBvcnRlZCI6dHJ1ZX0.NHB4c3auxmvqsDBrnGR5OuZSGEen-IRDvlUSfI_Y4kH_7zniNcfpyvrchn1QasNroPLxjqlDGIKzqwSPxwaRr3eSE0cjA5ZWmaDIHXEmf-Q2LPIwa5Ib4Bs7OjJgBjLTqykcgHlNonzzhrL1PAf6CUQL6plEoaNzA9_BmMxo54nVm3HUGNDcl_UcUON03MDKGG9mecoyNhhl8vHxSw4RhzQygBbGok1htQ0PVDeDcs7jT6r3mlmIa31YJwKZf-pNBPVWlO62uQVSczxsC1bPFtppFucq_mjM2lz9spNh0IEDkIsxwAC1YOWTv9_QdbaRVCmHVpMziedeDYSbUfleEg';
        if($accessToken){
            try{
                $apple_payload = $this->jwtDecode($accessToken);
                // $user = User::findOne(['email' => $apple_payload[1]->email]);
                
                $user = User::find()
                                ->where(['email' => $apple_payload[1]->email])
                                ->andWhere(['account_id' => $accountid])
                                ->one();
                
                return [
                    'code'              => self::CODE_SUCCESS,   
                    'apple'             => $apple_payload[1],
                    'in_db'             => ($user ? true : false),
                    'user_id'           => ($user ? $user->user_id : null),
                    'access_token'      => ($user && $user->auth_key) ? $user->auth_key : Yii::$app->security->generateRandomString()
                    
                ]; 
            }catch(\Exception $e){
                return [
                    'code'    => self::CODE_ERROR,   
                    'message' => 'Invalid Details or Access Token'
                ];
            }
        }


        return [
            'code'    => self::CODE_ERROR,   
            'message' => 'Invalid Access Token'
        ];
    }

    private function jwtDecode($accessToken){
        $data = [];
        $tokenarr = explode(".",$accessToken);
        $data[0] = json_decode(base64_decode($tokenarr[0]));
        $data[1] = json_decode(base64_decode($tokenarr[1]));
        
        return $data;
    }
}
