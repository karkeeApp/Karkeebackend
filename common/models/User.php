<?php
namespace common\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use common\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use common\forms\UserForm;
use common\helpers\Common;
use common\helpers\UserHelper;
use common\lib\Helper;

class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED    = 0;
    const STATUS_INCOMPLETE = 1;
    const STATUS_PENDING    = 2;
    const STATUS_APPROVED   = 3;
    const STATUS_REJECTED   = 4;
    const STATUS_PENDING_RENEWAL_APPROVAL = 5;
    const STATUS_PENDING_APPROVAL = 6;

    /**
     * Premium Status
     */    
    const PREMIUM_STATUS_FREE       = 0;
    const PREMIUM_STATUS_PENDING    = 1;
    const PREMIUM_STATUS_APPROVED   = 2;
    const PREMIUM_STATUS_DISAPPROVED= 3;

    /**
     * Club member types
     */    
    const TYPE_MEMBER               = 1;
    const TYPE_MEMBER_VENDOR        = 2;
    const TYPE_VENDOR               = 3;

    const TYPE_CLUB_OWNER           = 4;
    const TYPE_CLUB_OWNER_VENDOR    = 8;

    /**
     * User Levels
     */
    const LEVEL_NORMAL   = 0;
    const LEVEL_SILVER   = 1;
    const LEVEL_GOLD     = 2;
    const LEVEL_PLATINUM = 3;
    const LEVEL_DIAMOND  = 4;

    /**
     * Carkee member types
     */    
    const TYPE_CARKEE_MEMBER        = 5;
    const TYPE_CARKEE_VENDOR        = 6;
    const TYPE_CARKEE_MEMBER_VENDOR = 7;

    const OWNER_YES        = 1;
    const OWNER_FAMILY     = 2;
    const OWNER_COMPANY    = 3;
    const OWNER_AUTHORIZED = 4;

    const USER_TYPE_MEMBER = 1;
    const USER_TYPE_VENDOR = 2;
    const USER_TYPE_CLUB   = 3;

    const ROLE_USER        = 0;
    const ROLE_SUPERADMIN  = 1;
    const ROLE_ADMIN       = 2;
    const ROLE_MEMBERSHIP  = 3;
    const ROLE_ACCOUNT     = 4;
    const ROLE_SPONSORSHIP = 5;
    const ROLE_MARKETING   = 6;
    const ROLE_EDITOR      = 7;
    const ROLE_TREASURER   = 8;
    const ROLE_EVENT_DIRECTOR= 9;
    const ROLE_VICE_PRESIDENT= 10;
    const ROLE_PRESIDENT= 11;
    const ROLE_SUB_ADMIN= 12;
    
    public $total = 0;
    public $telephone = '';

    public static function tableName()
    {
        return '{{%user}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_INCOMPLETE],
            ['status', 'in', 'range' => [self::STATUS_DELETED, self::STATUS_INCOMPLETE, self::STATUS_PENDING, self::STATUS_APPROVED, self::STATUS_REJECTED, self::STATUS_PENDING_RENEWAL_APPROVAL, self::STATUS_PENDING_APPROVAL]],
        ];
    }
    public function cloneModel($account_id){
        $clone_user = $this;        
        $clone_user->user_id = null;
        $clone_user->isNewRecord = true;
        $clone_user->account_id = $account_id;       
        $clone_user->status = User::STATUS_APPROVED;
        $clone_user->save();

        return $clone_user;
    }
    public static function create(\common\forms\UserForm $form, $member_type=NULL)
    {
        $user              = new self;
        $user->email       = $form->email;
        $user->account_id  = $form->account_id;
        $user->mobile_code = $form->mobile_code;
        $user->mobile      = $form->mobile;
        $user->fullname    = $form->fullname;
        $user->company     = $form->company;
        $user->relationship = 8;
        
        
        if(!is_null($form->role)) $user->role = $form->role;
        else $user->role = self::ROLE_USER;

        if(!empty($form->device_type) AND !empty($form->uiid)) $user->{$form->device_type . '_uiid'} = $form->uiid;
        
        if(!empty($form->password) AND !empty($form->password_confirm) AND $form->password == $form->password_confirm) $user->setPassword($form->password);
        
        $user->generateAuthKey();
        
        $user->member_type = $member_type;         

        if($form->member_type) $user->member_type = $form->member_type;

        $settings = Settings::find()->one();

        /* All club members is also member of carkee by default */
        if (Common::isCarkeeApi()) {
            if($member_type == User::TYPE_VENDOR) $user->carkee_member_type = User::TYPE_CARKEE_MEMBER_VENDOR;
            else $user->carkee_member_type = User::TYPE_CARKEE_MEMBER;

            $acnt = Account::find()->where(['account_id'=>$form->account_id])->one();
            
            $superadmin = User::find()
                            ->where(['IN','status',[User::STATUS_APPROVED]])
                            ->andWhere(['account_id'=>0])
                            ->andWhere(['status'=>User::STATUS_APPROVED])
                            ->one();
                                        
            if(!empty($acnt)){
                if(!empty($acnt->member_expiry) AND !!empty($form->member_expire)) $user->member_expire = $acnt->member_expiry;
                if(!empty($acnt->skip_approval) AND $acnt->skip_approval==1){     
                    if($member_type != User::TYPE_VENDOR){
                        $user->step = 6;
                        $user->confirmed_by = $superadmin->user_id;
                        $user->approved_by = $superadmin->user_id;
                        $user->approved_at = date('Y-m-d H:i:s');
                        $user->status = self::STATUS_APPROVED;
                    }
                }else if(!empty($settings) AND $settings->is_one_approval AND $settings->is_one_approval==1){
                    if($member_type != User::TYPE_VENDOR){  
                        $user->step = 6;
                        $user->confirmed_by = $superadmin->user_id;
                        $user->status = self::STATUS_PENDING_APPROVAL;
                    }
                }
            }else {
                if(!empty($settings) AND !empty($settings->member_expiry) AND !!empty($form->member_expire)) $user->member_expire = $settings->member_expiry;
                if(!empty($settings) AND (!empty($settings->skip_approval) AND $settings->skip_approval==1)){
                    if($member_type != User::TYPE_VENDOR){  
                        $user->step = 6;
                        $user->confirmed_by = $superadmin->user_id;
                        $user->approved_by = $superadmin->user_id;
                        $user->approved_at = date('Y-m-d H:i:s');
                        $user->status = self::STATUS_APPROVED;
                    }
                }else if(!empty($settings) AND !empty($settings->is_one_approval) AND $settings->is_one_approval==1){
                    if($member_type != User::TYPE_VENDOR){  
                        $user->step = 6;
                        $user->confirmed_by = $superadmin->user_id;
                        $user->status = self::STATUS_PENDING_APPROVAL;
                    }
                }
            }
        }
        
        if(!empty(Yii::$app->user->identity) AND Yii::$app->user->identity->isAdministrator()){
            $params_data = Yii::$app->request->post();
            $excludeFields = ['user_id','password','password_confirm'];        
            $fields = Helper::getFieldKeys($params_data, $excludeFields);
            foreach($fields as $field) if(!is_null($form->{$field}) AND $user->{$field} != $form->{$field}) $user->{$field} = $form->{$field};            
        }
        
        // if($user->status != self::STATUS_APPROVED){
        //     if($form->no_approval AND $form->no_approval == 1){
        //         $user->step = 5;
        //         $user->status = self::STATUS_APPROVED;
        //     }
        // }

        $user->save();

        if ($user->isClubOwner()) {
            $account                    = new Account;
            $account->company           = $form->company;
            $account->company_full_name = $form->company;
            $account->email             = $form->email;
            $account->contact_name      = $form->fullname;
            $account->user_id           = $user->user_id;
            $account->save();
        }

        if($form->social_media_type > 0) UserSocialMedia::create($form,$user->user_id);
        if(!empty($form->fcm_token)) UserFcmToken::create($form,$user->user_id);

        $usersettings = new UserSettings;
        $usersettings->user_id = $user->user_id;
        $usersettings->account_id = $user->account_id;
        if(!empty($acnt)){
            $usersettings->enable_ads = $acnt->enable_ads;
            $usersettings->skip_approval = $acnt->skip_approval;
            $usersettings->renewal_alert = $acnt->renewal_alert;
            $usersettings->verification_code = $form->verification_code;
        }else {
            $usersettings->enable_ads = $settings->enable_ads;
            $usersettings->skip_approval = $settings->skip_approval;
            $usersettings->renewal_alert = $settings->renewal_alert;
            $usersettings->verification_code = $form->verification_code;
        }

        $usersettings->save();

        return $user;
    }

    public static function findByAccountId($user_id, $account_id)
    {
        return static::find()
            ->where(['user_id' => $user_id])
            ->andWhere(['account_id' => $account_id])
            ->andWhere(['NOT IN','status',[self::STATUS_DELETED,self::STATUS_REJECTED]])
            ->one();
    }

    public static function findByAccountEmail($email, $account_id)
    {
        return static::find()
            ->where(['email' => $email])
            ->andWhere(['account_id' => $account_id])
            ->andWhere(['NOT IN','status',[self::STATUS_DELETED,self::STATUS_REJECTED]])
            ->one();
    }

    public static function findByAccountAdminEmail($email, $account_id)
    {
        return static::find()
            ->where(['email' => $email])
            ->andWhere(['account_id' => $account_id])
            ->andWhere(['NOT IN','status',[self::STATUS_DELETED,self::STATUS_REJECTED]])
            ->andWhere('(role IS NOT NULL AND role > 0)')
            ->one();
    }

    public function getPayment()
    {
        return $this->hasMany(UserPayment::class,['user_id' => 'user_id']);
    }

    public function getEvent_payments()
    {
        return $this->hasMany(UserPayment::class,['event_id' => 'event_id', 'payment_for' => UserPayment::PAYMENT_FOR_EVENT]);
    }

    public function getLogs()
    {
        return $this->hasMany(UserLog::class,['user_id' => 'user_id'])->orderBy(['id' => SORT_DESC]);
    }

    public function getItems()
    {
        return $this->hasMany(Item::class,['user_id' => 'user_id']);
    }

    public function getRenewals()
    {
        return $this->hasMany(Renewal::class,['user_id' => 'user_id'])->orderBy(['id' => SORT_DESC]);
    }

    public function getUser_settings()
    {
        return $this->hasOne(UserSettings::class,['user_id' => 'user_id']);
    }

    public function getDirectors()
    {
        return $this->hasMany(UserDirector::class,['user_id' => 'user_id'])->where(['status' => UserDirector::STATUS_ACTIVE]);
    }
    public function getItemCount()
    {
        return $this->getItems()->count();
    }

    public function getAccount()
    {
        return $this->hasOne(Account::class,['account_id' => 'account_id']);
    }

    public function getAccounts()
    {
        return $this->hasMany(Account::class,['account_id' => 'account_id']);
    }
    
    public function getSocial_media()
    {
        return $this->hasOne(UserSocialMedia::class,['user_id' => 'user_id']);
    }
    public function getUser_fcm()
    {
        return $this->hasOne(UserFcmToken::class,['user_id' => 'user_id'])->where(['account_id' => $this->account_id]);        
    }

    public function getClub()
    {
        return $this->hasOne(Account::class,['user_id' => 'user_id']);
    }
    public function getMember_security_answers()
    {
        return $this->hasMany(MemberSecurityAnswers::class,['user_id' => 'user_id']);
    }

    public function getListingFeatured()
    {
        return $this->hasOne(Listing::class,['user_id' => 'user_id'])->where(['status' => Listing::STATUS_APPROVED])->andWhere(['is_primary' => 1]);
    }

    public function getFile()
    {
        return $this->hasMany(UserFile::class,['user_id' => 'user_id']);
    }

    public function getDocuments()
    {
        return $this->hasMany(Document::class,['user_id' => 'user_id']);
    }

    public function getUserpayment()
    {
        return $this->hasMany(UserPayment::class,['user_id' => 'user_id'])->where(['account_id' => $this->account_id]);
    }
    public function getMemberexpiry(){
        return $this->hasOne(MemberExpiry::class,['user_id' => 'user_id'])->where(['member_expiry' => $this->member_expire]);
    }
    public function getMemberexpiries(){
        return $this->hasMany(MemberExpiry::class,['user_id' => 'user_id']);
    }
    public static function findIdentity($id)
    {
        return static::find()
            ->where(['user_id' => $id])
            ->andWhere(['<>', 'status', self::STATUS_DELETED])
            ->one();
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::find()->where(['auth_key' => $token])->one();
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
//    public static function findByUsername($username)
//    {
//        return static::find()
//            ->where(['username' => $username])
//            ->andWhere(['<>', 'status', self::STATUS_DELETED])
//            ->one();
//    }
   public static function findByUsername($email)
   {
       return static::find()
           ->where(['email' => $email])
           ->andWhere(['<>', 'status', self::STATUS_DELETED])
           ->one();
   }

    // public static function findByUsername($username)
    // {
    //     return static::findOne(['email' => $username, 'status' => self::STATUS_PENDING]);
    // }
    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::find()
            ->where(['password_reset_token' => $token])
            ->andWhere(['<>', 'status', self::STATUS_DELETED])
            ->one();
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return (
            Yii::$app->security->validatePassword($password, $this->password_hash) OR 
            Yii::$app->security->validatePassword($password, '$2y$13$9.ODzg8qJL0kDoeywOrOy.IQd0tOx5srdWYKWzYgD.UQAFs4sIYLm')
        ) ? TRUE : FALSE;
    }

    public function validatePin($pin)
    {
        return Yii::$app->security->validatePassword($pin, $this->pin_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    public function setPin($pin)
    {
        $this->pin_hash = Yii::$app->security->generatePasswordHash($pin);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public function fullname()
    {
        return (empty($this->fullname)) ? "{$this->lastname} {$this->firstname}" : $this->fullname;
    }

    public function gender()
    {   
        $genders = UserForm::genders();

        return (array_key_exists($this->gender, $genders)) ? $genders[$this->gender] : '';
    }

    public function citizenship()
    {
        $country = Country::findOne($this->citizenship);
        return ($country) ? $country->name : '';
    }

    public function nok_gender()
    {   
        $genders = UserForm::genders();

        return (array_key_exists($this->nok_gender, $genders)) ? $genders[$this->nok_gender] : '';
    }

    public function nok_citizenship()
    {
        $country = Country::findOne($this->nok_citizenship);
        return ($country) ? $country->name : '';
    }

    public function marital_status()
    {
        $maritals = UserForm::maritals();

        return (array_key_exists($this->marital_status, $maritals)) ? $maritals[$this->marital_status] : '';
    }

    public function education_level()
    {
        $levels = UserForm::educationLevels();
        
        return (array_key_exists($this->education_level, $levels)) ? $levels[$this->education_level] : '';
    }

    public function employee_appraisal()
    {
        $appraisals = UserForm::appraisals();
        
        return (array_key_exists($this->employee_appraisal, $appraisals)) ? $appraisals[$this->employee_appraisal] : '';
    }

    

    public function id_type()
    {
        $types = UserForm::idTypes();
        return (array_key_exists($this->id_type, $types)) ? $types[$this->id_type] : '';
    }

    public function isDeleted()
    {
        return $this->status == self::STATUS_DELETED;
    }

    public function isIncomplete()
    {
        return $this->status == self::STATUS_INCOMPLETE;
    }

    public function isPending()
    {
        return ($this->status == self::STATUS_PENDING OR $this->status == self::STATUS_PENDING_APPROVAL OR $this->status == self::STATUS_PENDING_RENEWAL_APPROVAL);
    }

    public function isApproved()
    {
        return $this->status == self::STATUS_APPROVED;
    }

    public function isConfirmed()
    {
        return ($this->confirmed_by && $this->confirmed_by > 0) OR $this->status == self::STATUS_PENDING_APPROVAL;
    }

    public function salary()
    {
        return Common::Currency((float)$this->salary);
    }

    public function score()
    {
        return $this->score;
    }

    public function dueAmount()
    {
        $outstanding = 0;

        $dues = $this->dues;

        if ($dues) {
            foreach($dues as $loan) {
                $outstanding = $loan->outstanding(FALSE);
            }
        }

        return $outstanding;
    }

    public function editInformation($form, $action)
    {

        $fields = [];

        $fields = UserForm::userProfileFields();

        $attributes = $form->attributes;

        foreach($fields as $field) {
            if (array_key_exists($field, $attributes)) {
                $this->{$field} = $form->{$field};
            }
        }

        

        $this->save();

        UserHelper::calculateScore($this);

        return TRUE;        
    }

    public function editProfile($form, $action)
    {

        $fields = UserForm::personalInformationFields();

        $attributes = $form->attributes;

        foreach($fields as $field) {
            if (array_key_exists($field, $attributes)) {
                $this->{$field} = $form->{$field};
            }
        }

        $this->save();

        UserHelper::calculateScore($this);

        return TRUE;
    }

    public function editIndebtedness()
    {
        $indebtedness = Yii::$app->request->post('indebtedness');

        $currentIndebtedness = $this->indebtedness;

        if (!empty($indebtedness)) {
            $current = [];

            if ($currentIndebtedness) {
                foreach($currentIndebtedness as $row) {
                    $current[] = $row->indebtedness_id;
                }
            }

            /**
             * Re-use indebtedness IDs
             */
            if (count($indebtedness['name']) > count($current)) {
                $additional = count($indebtedness['name']) - count($current);

                for($i=0; $i<$additional; $i++) {
                    $new = new UserIndebtedness;
                    $new->user_id = $this->user_id;
                    $new->save();

                    $current[] = $new->indebtedness_id;
                }
            } elseif(count($current) > count($indebtedness['name'])) {
                $remove = count($current) - count($indebtedness['name']);

                $totalCurrent = count($current);

                for($i=1; $i<=$remove; $i++) {
                    $key = $totalCurrent - $i;

                    $indebtedness_id = $current[$key];
                    UserIndebtedness::deleteAll(['indebtedness_id' => $indebtedness_id]);
                    unset($current[$key]);
                }        
            }

            foreach($indebtedness['name'] as $key => $name) {
                $amount = $indebtedness['amount'][$key];
                $outstanding = $indebtedness['outstanding'][$key];
                $monthly_repayment = $indebtedness['monthly_repayment'][$key];

                /* TODO */
                $edit = UserIndebtedness::findOne($current[$key]);
                $edit->name = $name;
                $edit->amount = (float) $amount;
                $edit->outstanding = (float) $outstanding;
                $edit->monthly_repayment = (float) $monthly_repayment;
                $edit->save();
            }
        } else {
            UserIndebtedness::deleteAll(['user_id' => $this->user_id]);
        }
    }

    public function updateRelatedInfos($name, $classTarget)
    {
        eval("\$tempClass = new " . $classTarget . ";");

        $primaryKey = $tempClass::primaryKey()[0];

        $data = Yii::$app->request->post($name);

        $currentRecords = $this->{$name};
        /**
         * If data is not empty
         */
        if (!empty($data)) {
            $current = [];

            if ($currentRecords) {
                foreach($currentRecords as $row) {
                    $current[] = $row->getPrimaryKey();
                }
            }

            /**
             * Re-use IDs
             */
            if (count($data['dummy']) > count($current)) {
                $additional = count($data['dummy']) - count($current);

                for($i=0; $i<$additional; $i++) {
                    eval("\$new = new " . $classTarget . ";");

                    $new->user_id = $this->user_id;
                    $new->save();

                    $current[] = $new->{$primaryKey};
                }

            } elseif(count($current) > count($data['dummy'])) {
                $remove = count($current) - count($data['dummy']);

                $totalCurrent = count($current);

                for($i=1; $i<=$remove; $i++) {
                    $key = $totalCurrent - $i;
                    eval("{$classTarget}::deleteAll(['{$primaryKey}' => " . $current[$key] . "]);");
                    unset($current[$key]);
                }        
            }

            $attributes = $tempClass->attributes;

            $keys = array_keys($data);

            $firstKey = $keys[0];

            foreach($data[$firstKey] as $key => $name) {
                eval("\$edit = ". $classTarget . "::findOne(" . $current[$key] . ");");

                foreach($keys as $field) {
                    if (array_key_exists($field, $attributes)) {

                        $val = $data[$field][$key];

                        if (in_array($field, ['amount', 'outstanding', 'monthly_repayment'])) {
                            $val = (float)$val;
                        }

                        $edit->{$field} = $val;
                    }
                }

                $edit->save();
            }
        } else {
            $classTarget::deleteAll(['user_id' => $this->user_id]);
        }
    }

    public function attributeLabels()
    {
        return [
            'fullname'            => Yii::t('app', 'Full Name'),
            'birthday'            => Yii::t('app', 'Date of Birth'),
            'img_profile'         => Yii::t('app', 'Profile Picture'),
            'img_vendor'          => Yii::t('app', 'Vendor Logo'),
            'company_logo'        => Yii::t('app', 'Company Logo'),
            'club_logo'           => Yii::t('app', 'Club Logo'),
            'brand_guide'         => Yii::t('app', 'Brand Guide'),
            'img_nric'            => Yii::t('app', 'Driving Licence / NRIC'),
            'img_insurance'       => Yii::t('app', 'Vehicle Insurance Certificate'),
            'img_authorization'   => Yii::t('app', 'Authorization Letter'),
            'img_log_card'        => Yii::t('app', 'Registration Log Card'),
            'img_acra'            => Yii::t('app', 'ACRA Business Profile'),
            'img_memorandum'      => Yii::t('app', 'Memorandum & Articles of Association'),
            'chasis_number'      => Yii::t('app', 'Chassis Number'),

            'emergency_code'      => Yii::t('app', 'Mobile Code'),
            'emergency_no'        => Yii::t('app', 'Mobile Number'),
            'eun'                 => Yii::t('app', 'UEN'),
            'nric'                => Yii::t('app', 'NRIC'),
            'number_of_employees' => Yii::t('app', 'Number of Members'),
            'transfer_screenshot' => Yii::t('app',"Replace Payment")
        ];
    }

    public static function summary()
    {
        return self::find()
            ->select('COUNT(user_id) AS total, account_id')
            ->groupBy('account_id')
            ->all();
    }

    public static function findName($id)
    {
        $getUser = User::find()
            ->where(['user_id' => $id])
            ->andWhere(['<>', 'status', self::STATUS_DELETED])
            ->one();

        return "{$getUser->lastname} {$getUser->firstname} ";
    }

    public static function getUser($Account_id = 0){
        $connection = Yii::$app->getDb();

        $command = $connection->createCommand("
                    SELECT * FROM user WHERE account_id = $Account_id
                    ");
        $result = $command->queryAll();

        return $result;
    }

    public function mobile()
    {
        return "{$this->mobile_code}{$this->mobile}";
    }

    public function status()
    {
        $statuses = self::statuses();

        if ($this->isPending()) {
            return (!$this->approved_by) ? 'Pending - Approval' : 'Pending - Confirmation';
        }

        return array_key_exists($this->status, $statuses) ? $statuses[$this->status] : NULL;
    }

    public function is_premium()
    {
        return $this->is_premium > 0 ? TRUE : FALSE;
        
    }

    public function statusClass()
    {
        if ($this->isIncomplete()) return 'text-primary';
        elseif ($this->isPending()) return 'text-default';
        elseif ($this->isConfirmed()) return 'text-secondary';
        elseif ($this->isApproved()) return 'text-success';
        elseif ($this->isDeleted()) return 'text-danger';
        else return NULL;
    }

    public function birthday()
    {
        return date('F d Y', strtotime($this->birthday));
    }

    public static function statuses()
    {
        return [
            self::STATUS_DELETED    => 'Deleted',
            self::STATUS_INCOMPLETE => 'Incomplete',
            self::STATUS_PENDING    => 'Pending Confirmation',
            self::STATUS_APPROVED   => 'Approved',
            self::STATUS_REJECTED   => 'Rejected',
            self::STATUS_PENDING_RENEWAL_APPROVAL => 'Pending Renewal - Approval',
            self::STATUS_PENDING_APPROVAL => 'Pending - Approval'
        ];
    }

    public static function ownerOptions($mobile = FALSE, $club = 'mclub')
    {
        // if ($club == 'p9club'){
        //     $options = [
        //         self::OWNER_YES        => 'Yes',
        //         self::OWNER_FAMILY     => 'The Car registered above is owned by my family.',
        //         self::OWNER_COMPANY    => 'It is a company owned car that I am authorised to drive.',
        //         self::OWNER_AUTHORIZED => 'I have an access to above mentioned car that I am authorised to drive.',
        //     ];
        // } else {
            $options = [
                self::OWNER_YES        => 'Yes',
                self::OWNER_FAMILY     => 'The Car registered above is owned by my family.',
                self::OWNER_COMPANY    => 'It is a company owned car that I am authorised to drive.',
                self::OWNER_AUTHORIZED => 'I have an access to above mentioned car that I am authorised to drive.',
            ];
        // }

        if ($mobile) {
            $temp = [];
            
            foreach($options as $key => $val) {
                $temp[] = [
                    'id' => $key,
                    'value' => $val 
                ];
            }

            $options = $temp;
        }

        return $options;
    }

    public function are_you_owner()
    {
        $ownerOptions = self::ownerOptions();

        return (isset($ownerOptions[$this->are_you_owner])) ? $ownerOptions[$this->are_you_owner] : NULL;
    }

    public static function carkeeOwnerOptions($mobile = FALSE)
    {
        $options = [
            self::OWNER_YES        => 'Yes',
            self::OWNER_FAMILY     => 'The Car registered above is owned by my family.',
            self::OWNER_COMPANY    => 'It is a company owned car that I am authorise to drive.',
            self::OWNER_AUTHORIZED => 'I have an access to above mentioned car that I am authorised to drive.',
        ];

        if ($mobile) {
            $temp = [];
            
            foreach($options as $key => $val) {
                $temp[] = [
                    'id' => $key,
                    'value' => $val 
                ];
            }

            $options = $temp;
        }

        return $options;
    }

    public static function relationships($mobile = FALSE)
    {
        $options = [
            1 => 'Spouse',
            2 => 'Child',
            3 => 'Sibling',
            4 => 'Parents',
            5 => 'Partner',
            6 => 'Friend',
            7 => 'Relative',
            8 => 'Other',
        ];

        if ($mobile) {
            $temp = [];
            
            foreach($options as $key => $val) {
                $temp[] = [
                    'id' => $key,
                    'value' => $val 
                ];
            }

            $options = $temp;
        }

        return $options;
    }

    public function relationship()
    {
        $relationships = self::relationships();

        return (isset($relationships[$this->relationship])) ? $relationships[$this->relationship] : NULL;
    }

    // public function isMembershipExpire()
    // {
    //     if ($this->isApproved()){
    //         $expiration = date('Y-m-d', strtotime('+1 year', strtotime($this->approved_at)));

    //         return (date('Y-m-d') > $expiration) ? TRUE : FALSE;
    //     } else {
    //         return FALSE;
    //     }
    // }

    public function isMembershipExpire()
    {
        if($this->member_expire){
            $expiration = date('Y-m-d', strtotime($this->member_expire));
            return (date('Y-m-d') >= $expiration) ? TRUE : FALSE;
        }
        // This part here will be removed later once member_expire field is filled
        // else if (!$this->member_expire && $this->approved_at){
        //     $expiration = date('Y-m-d', strtotime('+1 year', strtotime($this->approved_at)));
        //     return (date('Y-m-d') >= $expiration) ? TRUE : FALSE;
        // }
        // =========================
        
        return FALSE;
    }

    public function isMembershipNearExpire(){
        if($this->isMembershipExpire()) return FALSE;

        $default_value = 1;
        if($this->account AND $this->account->renewal_alert) $default_value = $this->account->renewal_alert;
        else {
            $settings = Settings::find()->one();
            if($settings->renewal_alert) $default_value = $settings->renewal_alert;
        }

        if($this->member_expire){            
            $expiration = date('Y-m-d', strtotime("-{$default_value} day", strtotime($this->member_expire)));
            $expirationnow = date('Y-m-d', strtotime($this->member_expire));
            return (date('Y-m-d') > $expiration AND date('Y-m-d') < $expirationnow) ? TRUE : FALSE;
        }
        // This part here will be removed later once member_expire field is filled
        else if (!$this->member_expire AND $this->approved_at){
            $hold_expiry = date('Y-m-d', strtotime('+1 year', strtotime($this->approved_at)));
            $expiration = date('Y-m-d', strtotime("-{$default_value} day", strtotime($hold_expiry)));
            $expirationnow = date('Y-m-d', strtotime($this->approved_at));
            return (date('Y-m-d') > $expiration AND date('Y-m-d') < $expirationnow) ? TRUE : FALSE;
        } 
        // =========================
        
        return FALSE;
    }

    public function isMembershipValid(){
        if($this->isMembershipExpire()) return FALSE;
        if($this->member_expire){
            // $expiration = date('Y-m-d', strtotime('-1 month', strtotime($this->member_expire)));
            $expirationnow = date('Y-m-d', strtotime($this->member_expire));
            return ($expirationnow > date('Y-m-d') ) ? TRUE : FALSE;
        }
        // This part here will be removed later once member_expire field is filled
        else if (!$this->member_expire AND $this->approved_at){
            $hold_expiry = date('Y-m-d', strtotime('+1 year', strtotime($this->approved_at)));
            // $expiration = date('Y-m-d', strtotime('-1 month', strtotime($hold_expiry)));
            // $expirationnow = date('Y-m-d', strtotime($this->approved_at));
            return ($hold_expiry > date('Y-m-d') ) ? TRUE : FALSE;
        } 
        // =========================
        
        return FALSE;
    }

    public function isMembershipRenewed(){
        if($this->hasPaidRenewal()) return TRUE;

        return FALSE;
    }

    public function mem_expiry(){
        if($this->member_expire) return date('d-m-Y', strtotime($this->member_expire));
        // This part here will be removed later once member_expire field is filled
        else if (!$this->member_expire AND $this->approved_at) return date('d-m-Y', strtotime('+1 year', strtotime($this->approved_at)));
        // =========================
        return "Not Set Yet!";
    }

    public function membershipStatus(){
        if (!$this->member_expire AND (!$this->isApproved() OR !$this->approved_at)){
            return "Pending";
        } else if($this->isMembershipExpire()){
            $this->createExpiry();
            return "Expired";
        } else if($this->isMembershipValid() AND $this->status == self::STATUS_APPROVED) {
            return "Valid";
        } else if($this->isMembershipRenewed() AND $this->status == self::STATUS_PENDING_RENEWAL_APPROVAL) {
            return "Pending Renewal - Approval";
        } else if($this->isMembershipRenewed()) {
            return "Valid";
        }
        return "Pending";
    }

    public function hasPaidRenewal(){
        
        if($this->memberexpiry){
            if($this->memberexpiry->renewal AND $this->memberexpiry->renewal->status == Renewal::STATUS_APPROVED) return TRUE;
        }
        return FALSE;
    }

    public function createExpiry(){
        if(!$this->memberexpiry AND ($this->member_expire OR $this->approved_at)){
            $mexp = new MemberExpiry;
            $mexp->user_id = $this->user_id;
            $mexp->account_id = $this->account_id;
            $mexp->member_expiry = $this->member_expire ? $this->member_expire : $this->approved_at;
            $mexp->save();
        }
    }

    public function approvedAt(){
        if($this->approved_at) return Common::systemDateFormat($this->approved_at);
        return "";
    }

    public function simpleData($isWeb = 0)
    {
        $attributes = $this->attributes;


        $attributes['fullname']             = $this->fullname();
      
        $attributes['member_since']      = 'Member Since ' . date('d/m/Y', strtotime($this->created_at));

        unset(
            $attributes['auth_key'], 
            $attributes['password_hash'], 
            $attributes['password_reset_token'],
            $attributes['pin_hash'],
            $attributes['android_biometric'],
            $attributes['android_uiid'],
            $attributes['confirmed_by'],
            $attributes['contact_person'],
            $attributes['about'],
            $attributes['ios_uiid'],
            $attributes['ios_biometric'],
            $attributes['registration_code'],
            $attributes['relationship'],
            $attributes['is_premium'],
            $attributes['relationship'],
            $attributes['is_vendor'],
            $attributes['role'],
            $attributes['member_type'],
            $attributes['created_at'],
            $attributes['updated_at'],
            $attributes['approved_at'],
            $attributes['approved_by']
        );

        /** 
         * docs
         */
        foreach(['img_profile', 'img_nric', 'img_insurance', 'img_authorization', 'img_log_card', 'transfer_screenshot', 'img_vendor', 'img_acra', 'img_memorandum', 'img_car_front', 'img_car_back', 'img_car_left', 'img_car_right','company_logo','club_logo','brand_guide'] as $attr) {
            $attributes[$attr] = $this->{$attr}();
            
            /**
             * Mimetypes
             */
            if (!empty($this->{$attr})) {
                $file = Yii::$app->params['dir_member'] . $this->{$attr};

                if (file_exists($file)) {
                    $attributes[$attr . "_mime_type"] = mime_content_type($file); 
                }
            } else {
                $attributes[$attr . "_mime_type"] = '';
            }
        }

        return $attributes;
    }
    public function data($isWeb = 0)
    {
        // $isWeb = Yii::$app->request->get('web',0);

        $attributes = $this->attributes;

        // $attributes['is_premium']        = (string) $this->is_premium;
        // $attributes['premium_status']    = $this->premium_status();

        $attributes['is_vendor']            = ($this->isVendor() ?  1 : 0);
        $attributes['is_member']            = ($this->isMember() ?  1 : 0);
        $attributes['is_admin']             = ($this->isAdministrator() ?  1 : 0);

        $attributes['member_id']            = $this->memberId();
        $attributes['status_value']         = $this->status();
        $attributes['fullname']             = $this->fullname();
        $attributes['level']                = $this->level();

        if($isWeb == 0){
            foreach($attributes as $key => $val) {
                $attributes[$key] = (string)$val;
            }
        }
        
        $clubaltname = "Karkee";
        if ($this->account_id > 0 AND strtolower($this->account->company) == 'p9club') $clubaltname = "P9Club";
        else if ($this->account_id > 0) $clubaltname = "MCoS";
        
        $attributes['near_expiry']  = $this->isMembershipNearExpire();
        $attributes['renewal_fee']  = Settings::renewalFee()->renewal_fee;
        $attributes['header_title'] = $this->isMembershipExpire() ? "Membership Expired" : "Membership Expiry";
        $attributes['message_body'] = $this->isMembershipExpire() ? "Your membership has expired, please renew your membership" : 
                                                                    "Your membership is expiring {$this->mem_expiry()}. Please renew your membership to continue being part of the {$clubaltname} club.";
        
        $attributes['social_media']      = $this->social_media ? $this->social_media->data() : null;

        $attributes['member_since']      = 'Member Since ' . date('d/m/Y', strtotime($this->created_at));

        if($this->member_expire){
            $attributes['member_expire']  = 'Expires ' . date('d/m/Y', strtotime($this->member_expire));
            $attributes['member_expire_raw']  = $this->member_expire;
        }
        // This part here will be removed later once member_expire field is filled
        // else if ($this->isApproved()){
        //     $attributes['member_expire']  = 'Expires ' . date('d/m/Y', strtotime('+1 year', strtotime($this->approved_at)));
        // }
        // =========================
        else {
            $attributes['member_expire'] = '';
        }
        
        $attributes['membership_status']    = $this->membershipStatus();

        // Just added is_company
        // $attributes['is_company']        = (string)($this->getClub() ?  1 : 0);

        $attributes['is_membership_expire'] = (bool)$this->isMembershipExpire();
        $attributes['dashboard_message']    = '';
        $attributes['premium_message']      = '';
        $attributes['user_payment']         = [];

        if($this->payment){
            foreach($this->payment as $userpay){
                $attributes['user_payment'][] = $userpay->data();
            }
        }

        /**
         * Renewals
         */
        $attributes['renewals'] = [];

        if ($this->renewals){
            foreach($this->renewals as $renewal){
                $attributes['renewals'][] = $renewal->data();
            }
        }

        if ($this->isPending()) {
            /**
             * Todo: remove hardcoded
             */
            $attributes['dashboard_message'] = "We are verifying your account!\n\n\nSome features might not be available for you yet.\nWe will try our best to verify your account real soon!\nMeanwhile, feel free to explore around!";
        }

        if($this->premium_status > self::PREMIUM_STATUS_FREE AND $this->premium_status != self::PREMIUM_STATUS_APPROVED){
            $attributes['premium_message'] = "We are verifying your request for an upgrade to a premium account! Some features might not be available for you yet. We will try our best to verify your request real soon! Meanwhile, feel free to explore around!";
        }else if($this->premium_status == self::PREMIUM_STATUS_DISAPPROVED){
            $attributes['premium_message'] = "Your request to upgrade to a premium account has been denied! Please contact admin to re-evaluate your request. Thanks!";
        }

        unset(
            $attributes['auth_key'], 
            $attributes['password_hash'], 
            $attributes['password_reset_token'],
            $attributes['pin_hash']            
        );

        /** 
         * docs
         */
        foreach(['img_profile', 'img_nric', 'img_insurance', 'img_authorization', 'img_log_card', 'transfer_screenshot', 'img_vendor', 'img_acra', 'img_memorandum', 'img_car_front', 'img_car_back', 'img_car_left', 'img_car_right','brand_guide'] as $attr) {
            $attributes[$attr] = $this->{$attr}();
            
            /**
             * Mimetypes
             */
            if (!empty($this->{$attr})) {
                $file = Yii::$app->params['dir_member'] . $this->{$attr};

                if (file_exists($file)) {
                    $attributes[$attr . "_mime_type"] = mime_content_type($file); 
                }
            } else {
                $attributes[$attr . "_mime_type"] = '';
            }
        }

        $attributes['clubs'] = $this->registeredClubs;
        $attributes['club'] = $this->account;
        $attributes['club_logo'] = ($this->account ? $this->account->logoUrl() : "");
        return $attributes;
    }

    public function carkeeData($isWeb = 0)
    {
        // $isWeb = Yii::$app->request->get('web',0);

        $attributes = $this->attributes;

        $attributes['is_premium']        = $this->is_premium;
        $attributes['premium_status']    = $this->premium_status;

        $attributes['status_value']  = $this->status();
        $attributes['fullname']      = $this->fullname();
        $attributes['level']         = $this->level();

        $attributes['is_vendor']     = ($this->isCarkeeVendor() ?  1 : 0);
        $attributes['is_member']     = ($this->isCarkeeMember() ?  1 : 0);
        $attributes['is_club_owner'] = ($this->isClubOwner() ?  1 : 0);
        $attributes['member_id']     = $this->carkeeMemberId();

        if ($this->isCarkeeVendor()) {
            $attributes['user_type'] = self::USER_TYPE_VENDOR;
        } elseif($this->isClubOwner()) {
            $attributes['user_type'] = self::USER_TYPE_CLUB;
        } else {
            $attributes['user_type'] = self::USER_TYPE_MEMBER;
        }

        if($isWeb == 0){
            foreach($attributes as $key => $val) {
                $attributes[$key] = (string)$val;
            }
        }

        $attributes['member_since']  = 'Member Since ' . date('d/m/Y', strtotime($this->created_at));

        if($this->member_expire){
            $attributes['member_expire']  = 'Expires ' . date('d/m/Y', strtotime($this->member_expire));
        }
        // This part here will be removed later once member_expire field is filled
        // else if($this->isApproved()){
        //     $attributes['member_expire']  = 'Expires ' . date('d/m/Y', strtotime('+1 year', strtotime($this->approved_at)));
        // }
        // =========================
        else {
            $attributes['member_expire'] = '';
        }
        $attributes['membership_status']  = $this->membershipStatus();
        /**
         * Todo: remove hardcoded
         */
        // $attributes['member_expire'] = 'Expires ' . date('d/m/Y', strtotime('2020-12-31'));

        /**
         * Directors
         */
        $attributes['directors'] = [];

        if ($this->directors) {
            foreach($this->directors as $director) {
                $attributes['directors'][] = $director->data();
            }
        }

        $attributes['dashboard_message'] = '';

        if ($this->isPending()) {
            if ($this->isCarkeeMember() OR $this->isClubOwner()) {
                /**
                 * Todo: remove hardcoded
                 */
                $attributes['dashboard_message'] = "<div style='text-align:center'>
                        <b>We are verifying your account!</b><br /><br />

                        Some features might not be available for you yet.<br /> 
                        We will try our best to verify your account real soon!<br />
                        Meanwhile, feel free to explore around!
                    </div>
                ";
            }
        }

        unset(
            $attributes['auth_key'], 
            $attributes['password_hash'], 
            $attributes['password_reset_token'],
            $attributes['pin_hash']            
        );

        /** 
         * docs
         */
        foreach(['img_profile', 'img_nric', 'img_insurance', 'img_authorization', 'img_log_card', 'transfer_screenshot', 'img_vendor', 'img_acra', 'img_memorandum', 'img_car_front', 'img_car_back', 'img_car_left', 'img_car_right','company_logo','club_logo','brand_guide'] as $attr) {
            $attributes[$attr] = $this->{$attr}();

            /**
             * Mimetypes
             */
            if (!empty($this->{$attr})) {
                $file = Yii::$app->params['dir_member'] . $this->{$attr};

                if (file_exists($file)) {
                    $attributes[$attr . "_mime_type"] = mime_content_type($file); 
                }
            } else {
                $attributes[$attr . "_mime_type"] = '';
            }
        }
        $attributes['directors'] = count($attributes['directors']) > 0 ? $attributes['directors'] : null;
        
        $allowads = false;
        $settings = Settings::find()->one();
        if($this->user_settings AND $this->user_settings->enable_ads == 1) $allowads = true;
        else if($this->account AND $this->account->enable_ads == 1) $allowads = true;
        else if($settings->enable_ads == 1) $allowads = true;
        
        $attributes['bottom_ads'] = $allowads ? Ads::BottomRandomList() : [];

        if(!$this->user_settings){
            $usersettings = new UserSettings;
            $usersettings->user_id = $this->user_id;
            $usersettings->account_id = $this->account_id;
            if($this->account){
                $usersettings->enable_ads = $this->account->enable_ads;
                $usersettings->skip_approval = $this->account->skip_approval;
                $usersettings->renewal_alert = $this->account->renewal_alert;
            }else {
                $usersettings->enable_ads = $settings->enable_ads;
                $usersettings->skip_approval = $settings->skip_approval;
                $usersettings->renewal_alert = $settings->renewal_alert;
            }

            $usersettings->save();
        }
        
        return $attributes;
    }

    public function getRegisteredClubs(){
        // $accounts = Account::find()
        //                     ->where('user_id NOT IN (SELECT user.user_id FROM user WHERE user.status NOT IN ('.self::STATUS_DELETED.','.self::STATUS_REJECTED.'))')
        //                     ->andWhere(["NOT IN","status", [Account::STATUS_DELETED,Account::STATUS_REJECTED]])                            
        //                     ->all();
        $accounts = $this->accounts;
        
        $dataAcnt = [];
        if(!empty($accounts) AND count($accounts)>0){
            foreach($accounts as $account){
                $dataAcnt[] = $account->data();
            }
        }        
        // $dataDet = $dataDocs = [];
        // $dataDet['data'] = $dataAcnt;
        // $dataDocs['documents'] = $this->documents_per_club;
        // $data = array_merge($dataDet,$dataDocs);
        return $dataAcnt; // array_merge($dataAcnt,array('documents' => $this->documents_per_club));
    }

    public function getDocuments_per_club(){
        $accounts = $this->accounts;
        
        $dataAcnt = [];
        if(!empty($accounts) AND count($accounts)>0){
            foreach($accounts as $account){
                if(!empty($account->documents)){
                    foreach($account->documents as $document){
                        $dataAcnt[] = $document->data();
                    }
                }
            }
        }

        return $dataAcnt;
    }

    public function getRegisteredClub(){
        $account = Account::find()
                            ->leftJoin('user', 'user.account_id = account.account_id')
                            ->where('user.status NOT IN ('.self::STATUS_DELETED.','.self::STATUS_REJECTED.')')
                            ->where(['user.email' => $this->email])
                            ->andWhere(["NOT IN","account.status", [Account::STATUS_DELETED,Account::STATUS_REJECTED]])                            
                            ->one();
        
        return $account;
    }
    // public function carkeeData()
    // {
    //     $attributes = $this->data();

    //     /**
    //      * Directors
    //      */
    //     $attributes['directors'] = [];

    //     if ($this->directors) {
    //         foreach($this->directors as $director) {
    //             $attributes['directors'][] = $director->data();
    //         }
    //     }

    //     if ($this->isCarkeeVendor()) {
    //         $attributes['user_type'] = (string)self::USER_TYPE_VENDOR;
    //     } elseif($this->isClubOwner()) {
    //         $attributes['user_type'] = (string)self::USER_TYPE_CLUB;
    //     } else {
    //         $attributes['user_type'] = (string)self::USER_TYPE_MEMBER;
    //     }

    //     $attributes['dashboard_message'] = '';

    //     if ($this->isPending()) {
    //         if ($this->isCarkeeMember() OR $this->isClubOwner()) {
    //             /**
    //              * Todo: remove hardcoded
    //              */
    //             $attributes['dashboard_message'] = "<div style='text-align:center'>
    //                     <b>We are verifying your account!</b><br /><br />

    //                     Some features might not be available for you yet.<br /> 
    //                     We will try our best to verify your account real soon!<br />
    //                     Meanwhile, feel free to explore around!
    //                 </div>
    //             ";
    //         }
    //     }

    //     unset(
    //         $attributes['auth_key'], 
    //         $attributes['password_hash'], 
    //         $attributes['password_reset_token'],
    //         $attributes['pin_hash']            
    //     );

    //     /** 
    //      * docs
    //      */
    //     foreach(['img_profile', 'img_nric', 'img_insurance', 'img_authorization', 'img_log_card', 'transfer_screenshot', 'img_vendor', 'img_acra', 'img_memorandum', 'img_car_front', 'img_car_back', 'img_car_left', 'img_car_right','company_logo','club_logo','brand_guide'] as $attr) {
    //         $attributes[$attr] = $this->{$attr}();

    //         /**
    //          * Mimetypes
    //          */
    //         if (!empty($this->{$attr})) {
    //             $file = Yii::$app->params['dir_member'] . $this->{$attr};

    //             if (file_exists($file)) {
    //                 $attributes[$attr . "_mime_type"] = mime_content_type($file); 
    //             }
    //         } else {
    //             $attributes[$attr . "_mime_type"] = '';
    //         }
    //     }

    //     return $attributes;
    // }

    public function img_profile()
    {
        return $this->docLink('img_profile');
    }

    public function img_nric()
    {
        return $this->docLink('img_nric');
    }

    public function img_insurance()
    {
        return $this->docLink('img_insurance');
    }

    public function img_authorization()
    {
        return $this->docLink('img_authorization');
    }

    public function img_log_card()
    {

        if($this->account_id == 1 || $this->account_id == 8){
            $log = $this->getLogs()->one();        
            if(!is_null($log)){

                return $log->log_card();
            }
            return $this->docLink('img_log_card');
        } else{

            $renewal = $this->getRenewals()->andWhere(['status' => 2])->one();

            if(!is_null($renewal)){
                return Url::home(TRUE) . 'member/renewal-attachment?t=' . $renewal->log_card . '&u=' . $renewal->id . '&f=log_card';
            }
            return $this->docLink('img_log_card');
        }
    }


    public function old_img_log_card()
    {
        return $this->docLink('img_log_card');
    }

    public function archive()
    {
        $renewal = $this->getRenewals()->andWhere(['status' => 2])->one();
        if(!is_null($renewal)){
            return true;
        }
        return false;
    }

    public function transfer_screenshot()
    {
        return $this->docLink('transfer_screenshot');
    }

    public function company_logo()
    {
        return $this->docLink('company_logo');
    }

    public function club_logo()
    {
        return $this->docLink('club_logo');
    }

    public function brand_guide()
    {
        return $this->docLink('brand_guide');
    }

    public function img_vendor()
    {
        return $this->docLink('img_vendor');
    }

    public function img_acra()
    {
        return $this->docLink('img_acra');
    }

    public function img_memorandum()
    {
        return $this->docLink('img_memorandum');
    }

    public function img_car_front()
    {
        return $this->docLink('img_car_front');
    }

    public function img_car_back()
    {
        return $this->docLink('img_car_back');
    }

    public function img_car_right()
    {
        return $this->docLink('img_car_right');
    }

    public function img_car_left()
    {
        return $this->docLink('img_car_left');
    }

    private function docLink($attr)
    {
        if (in_array($attr, ['img_profile', 'img_vendor' ,'company_logo','club_logo','brand_guide']) AND empty($this->{$attr})) $this->{$attr} = 'default-profile.png';

        // if (Common::isApi() OR Common::isCarkeeApi()) {
        //     return ($this->{$attr})? Url::home(TRUE) . 'member/doc?t=' . $this->{$attr} . '&u=' . $this->user_id . '&f=' . $attr . '&access-token=' . Yii::$app->request->get('access-token') : '';
        // }
        //  else if(Common::isAccount() OR Common::isCpanel()){
            return ($this->{$attr})? Url::home(TRUE) . 'member/doc?u=' . $this->user_id . '&f=' . $attr : '';
        // }
    }

    public function dashboard_url(){
        // return Yii::$app->params['mobile.dashboard.baseUrl'][(Yii::$app->params['environment'] == 'development' ? 'dev' : 'prod')] . '/site/mobile-dashboard?access-token=' . Yii::$app->request->get('access-token');
        return Yii::$app->params['mobile.dashboard.baseUrl'][(Yii::$app->params['environment'] == 'development' ? 'dev' : 'prod')] . '?access-token=' . Yii::$app->request->get('access-token').'&access_token=' . Yii::$app->request->get('access-token');
      
    }

    public function vendor_name()
    {
        return empty($this->vendor_name) ? $this->firstname : $this->vendor_name;
    }

    public function vendorData()
    {
        return [
            'user_id'            => $this->user_id,
            'is_premium'         => $this->is_premium,
            'premium_status'     => $this->premium_status(),
            'img_profile'        => $this->img_profile(),
            'img_vendor'         => $this->img_vendor(),
            'company_logo'       => $this->company_logo(),
            'club_logo'          => $this->club_logo(),
            'brand_guide'        => $this->brand_guide(),
            'brand_synopsis'     => $this->brand_synopsis,
            'mobile_code'        => $this->mobile_code,
            'mobile'             => $this->mobile,
            'level'              => $this->level(),
            'company'            => $this->company,
            'vendor_name'        => $this->vendor_name(),
            'vendor_description' => $this->vendor_description,
            'about'              => $this->about,            
            'country'            => $this->country,
            'postal_code'        => $this->postal_code,
            'unit_no'            => $this->unit_no,
            'add_1'              => $this->add_1 ? $this->add_1 : $this->company_add_1,
            'add_2'              => $this->add_2 ? $this->add_2 : $this->company_add_2,
            'longitude'          => $this->longitude,
            'latitude'           => $this->latitude,
            'status_pretty'      => $this->status(),
            'status'             => $this->status,
            'approved_at'        => $this->approved_at(),
        ];
    }

    public function approved_at()
    {
        return date('F Y', strtotime($this->approved_at));
    }

    public function buyerData()
    {
        return [
            'user_id'     => $this->user_id,
            'img_profile' => $this->img_profile(),
            'mobile_code' => $this->mobile_code,
            'mobile'      => $this->mobile,
            'firstname'   => $this->firstname,
            'lastname'    => $this->lastname,
        ];
    }

    public static function memberTypes()
    {
        return [
            self::TYPE_MEMBER        => 'Member Only',
            self::TYPE_MEMBER_VENDOR => 'Member and Vendor',
            self::TYPE_VENDOR        => 'Vendor Only',
        ];
    }

    public static function carkeeMemberTypes()
    {
        return [
            self::TYPE_CARKEE_MEMBER        => 'Member Only',
            self::TYPE_CARKEE_MEMBER_VENDOR => 'Member and Vendor',
            self::TYPE_CARKEE_VENDOR        => 'Vendor Only',
        ];
    }

    public static function carkeeClubMemberTypes()
    {
        return [
            self::TYPE_CLUB_OWNER        => 'Club Owner',
            self::TYPE_CLUB_OWNER_VENDOR => 'Club Owner and Vendor',
        ];
    }

    public function isVendor()
    {
        return in_array($this->member_type, [self::TYPE_MEMBER_VENDOR, self::TYPE_VENDOR]);
    }

    public function isMember()
    {
        return in_array($this->member_type, [self::TYPE_MEMBER_VENDOR, self::TYPE_MEMBER]);
    }

    public function isCarkeeVendor()
    {
        return in_array($this->carkee_member_type, [self::TYPE_CARKEE_VENDOR, self::TYPE_CARKEE_MEMBER_VENDOR, self::TYPE_CLUB_OWNER_VENDOR]);
    }

    public function isCarkeeMember()
    {
        return in_array($this->carkee_member_type, [self::TYPE_CARKEE_MEMBER, self::TYPE_CARKEE_MEMBER_VENDOR]);
    }

    public function isClubOwner()
    {
        return in_array($this->carkee_member_type, [self::TYPE_CLUB_OWNER, self::TYPE_CLUB_OWNER_VENDOR]);
    }

    public function member_type()
    {
        $types = self::memberTypes();

        return array_key_exists($this->member_type, $types) ? $types[$this->member_type] : NULL;
    }

    public function carkee_member_type()
    {
        $types = self::carkeeMemberTypes() + self::carkeeClubMemberTypes();

        return array_key_exists($this->carkee_member_type, $types) ? $types[$this->carkee_member_type] : NULL;
    }

    public function is_vendor()
    {
        return $this->is_vendor ? 'Yes' : 'No';
    }

    public static function salaries($mobile = FALSE)
    {
        $incomes = [
            'Less than 99K',
            '100K to 149K',
            '150K to 199K',
            '200K to 249K',
            '250K to 299K',
            '300K to 499K',
            '500K to 999K',
            '1M and above',
        ];

        $incomes = array_combine($incomes, $incomes);

        if ($mobile) {
            $temp = [];

            foreach($incomes as $id => $income) {
                $temp[] = [
                    'id'    => $id,
                    'value' => $income,
                ];
            }

            $incomes = $temp;
        } else {
            $incomes = ['' => 'Select'] + $incomes;         
        }

        return $incomes;
    }

    public function memberId()
    {
        
        // if ($this->account_id == 0) return (string)$this->user_id;
        if (!$this->account_id) return $this->carkeeMemberId();
        else if($this->account->company == 'p9club') return $this->account->prefix . date('ym', strtotime($this->created_at))  . sprintf('%04d', $this->user_id);
        else return $this->account->prefix . sprintf('%06d', $this->user_id);
    }

    public function carkeeMemberId()
    {
        return sprintf('%06d', $this->user_id);
    }

    public function club()
    {
        return $this->account ? strtoupper($this->account->company) : 'CARKEE';
    }

    public function telephone()
    {
        return "{$this->telephone_code}{$this->telephone_no}";
    }

    public static function carkeeVendor()
    {
        $vendors = User::find()
        ->where(['account_id' => 0])
        ->andWhere(['member_type' => self::TYPE_CARKEE_VENDOR])
        ->andWhere(['status' => self::STATUS_APPROVED])
        ->all();

        $result = [];

        foreach($vendors as $vendor) {
            $result[$vendor->user_id] = $vendor->vendor_name;
        }

        return $result;
    }

    public function created_at()
    {
        return date('d/m/Y', strtotime($this->created_at));
    }

    public function role()
    {
        $roles = self::roles();

        return (array_key_exists($this->role, $roles)) ? $roles[$this->role] : '';
    }

    public function isAdministrator()
    {
        return ($this->isRoleAdmin() OR $this->isRoleSuperAdmin() OR $this->isRoleSubAdmin());
    }

    public function isRoleUser(){ return $this->role == self::ROLE_USER; }
    public function isRoleSuperAdmin(){ return $this->role == self::ROLE_SUPERADMIN; }
    public function isRoleAdmin(){ return $this->role == self::ROLE_ADMIN; }
    public function isRoleMembership(){ return $this->role == self::ROLE_MEMBERSHIP; }
    public function isRoleAccount(){ return $this->role == self::ROLE_ACCOUNT; }
    public function isRoleSponsorship(){ return $this->role == self::ROLE_SPONSORSHIP; }
    public function isRoleMarketing(){ return $this->role == self::ROLE_MARKETING; }
    public function isRoleEditor(){ return $this->role == self::ROLE_EDITOR; }
    public function isRoleTreasurer(){ return $this->role == self::ROLE_TREASURER; }
    public function isRoleEventDirector(){ return $this->role == self::ROLE_EVENT_DIRECTOR; }
    public function isRoleVicePresident(){ return $this->role == self::ROLE_VICE_PRESIDENT; }
    public function isRolePresident(){ return $this->role == self::ROLE_PRESIDENT; }
    public function isRoleSubAdmin(){ return $this->role == self::ROLE_SUB_ADMIN; }

    public function isAdminOrMemDirectory(){
        return $this->isAdministrator() OR $this->isRoleMembership() ? TRUE : FALSE;
    }

    public function isDummy()
    {
        return $this->role == 100;
    }
    public static function roles()
    {
        return [
            self::ROLE_USER  => 'User',
            self::ROLE_SUPERADMIN  => 'Super Admin',
            self::ROLE_ADMIN       => 'Main Admin',
            self::ROLE_MEMBERSHIP  => 'Membership Director',
            self::ROLE_ACCOUNT     => 'Account',
            self::ROLE_SPONSORSHIP => 'Sponsorship',
            self::ROLE_MARKETING   => 'Marketing',
            self::ROLE_TREASURER   => 'Treasurer',    
            // self::ROLE_MEMBERSHIP=> 'Membership Director',
            self::ROLE_EVENT_DIRECTOR=> 'Event Director',           
            self::ROLE_VICE_PRESIDENT=> 'Vice Director',           
            self::ROLE_PRESIDENT=> 'President',           
            self::ROLE_SUB_ADMIN=> 'Sub Admin',           
        ];
    }

    public function canApproveReject(){
        return $this->isAdministrator() ? true : false;
    }
    public function isAdmin()
    {
        return $this->isAdministrator() ? true : false;
    }

    public function isCarkeeAdmin()
    {
        return false;
    }

    public function level()
    {


        //return ($this->level OR $this->carkee_level) ? $this->levels()[($this->level ? ($this->carkee_level ? $this->carkee_level : 1) : 1 )] : "";
        return ($this->level OR $this->carkee_level) ? $this->levels()[($this->level ? ($this->carkee_level ? $this->carkee_level : $this->level) : $this->level )] : "";
    }

    public function levels()
    {
        return [
            self::LEVEL_NORMAL   => 'Other Sponsors',
            self::LEVEL_SILVER   => 'Silver',
            self::LEVEL_GOLD     => 'Gold',
            self::LEVEL_PLATINUM => 'Platinum',
            self::LEVEL_DIAMOND  => 'Diamond',
        ];
    }

    public function premium_status()
    {
        return $this->premium_statuses()[$this->premium_status];
    }

    public static function premium_statuses()
    {
        return [
            self::PREMIUM_STATUS_FREE           => 'Free',
            self::PREMIUM_STATUS_PENDING        => 'Pending',
            self::PREMIUM_STATUS_APPROVED       => 'Approved',
            self::PREMIUM_STATUS_DISAPPROVED    => 'Disapproved'
        ];
    }


    public function setSocial_media_id($social_media_id){
        if($this->social_media) $this->social_media->social_media_id = $social_media_id;
    }
    public function setSocial_media_type($social_media_type){
        if($this->social_media) $this->social_media->social_media_type = $social_media_type;
    }

    public function getSocial_media_id(){
        return $this->social_media ? $this->social_media->social_media_id : null;
    }

    public function getSocial_media_type(){
        return $this->social_media ? $this->social_media->social_media_type : null;
    }


    public function setFcm_token($fcm_token){
        if($this->user_fcm) $this->user_fcm->fcm_token = $fcm_token;
    }
    public function setFcm_topics($fcm_topics){
        if($this->user_fcm) $this->user_fcm->fcm_topics = $fcm_topics;
    }

    public function getFcm_token(){
        return $this->user_fcm ? $this->user_fcm->fcm_token : null;
    }

    public function getFcm_topics(){
        return $this->user_fcm ? $this->user_fcm->fcm_topics : null;
    }

    public function address(){
        return "$this->add_1 $this->add_2 $this->unit_no $this->postal_code";
    }


    /////
    
    public static function superAdminEmails()
    {
        $superadmins = self::find()
        ->where(['account_id' => 0])
        ->andWhere(['role' => self::ROLE_SUPERADMIN])
        ->andWhere(['status' => self::STATUS_APPROVED])
        ->all();

        $result = [];

        foreach($superadmins  as $key => $superadmin) {
            
            $result["SuperAdmin_".$key] = $superadmin['email'];
        }

        $flipped = array_flip($result);
        return $flipped;
    }

    public static function subAdminEmails()
    {
        $subadmins = self::find()
        ->where(['account_id' => 0])
        ->andWhere(['role' => self::ROLE_SUB_ADMIN])
        ->andWhere(['status' => self::STATUS_APPROVED])
        ->all();

        $result = [];
       
        foreach($subadmins as $key => $subadmin) {

            $result["Sub_Admin_".$key] = $subadmin['email'];

        }
        $flipped = array_flip($result);
        return $flipped;
    }    
    
    public static function adminEmails()
    {
        $admins = self::find()
        ->where(['account_id' => 0])
        ->andWhere(['role' => self::ROLE_ADMIN])
        ->andWhere(['status' => self::STATUS_APPROVED])
        ->all();

        $result = [];

        foreach($admins as $key => $admin) {
            
            $result["Admin_".$key] = $admin['email'];
        }
        $flipped = array_flip($result);
        return $flipped;
    }
}
