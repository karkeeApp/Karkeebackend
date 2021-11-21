<?php
namespace common\forms;

use Yii;
use yii\base\Model;
use common\models\User;
use common\models\Account;
use common\helpers\Common;
// use yii\helpers\ArrayHelper;
// use yii\helpers\Json;

class UserForm extends Model
{

    public $user_id = 0;
    public $username;
    public $password;
    public $password_confirm;
    public $email;
    public $account_id = 0;

    /**
     * Profile fields
     */
    public $mobile_code;
    public $mobile;
    public $firstname;
    public $lastname;

    /**
     * Address
     */
    public $country;
    public $postal_code;
    public $unit_no;
    public $add_1;
    public $add_2;
    
    /**
     * Personal
     */
    public $gender;
    public $birthday;
    public $nric;
    public $profession;
    public $company;
    public $annual_salary;

    /**
     * Car Info
     */
    public $chasis_number;
    public $plate_no;
    public $car_model;
    public $registration_code;
    public $are_you_owner;

    /**
     * Emergency contact
     */
    public $contact_person;
    public $emergency_no;
    public $emergency_code;
    public $relationship;

    /**
     * Documents
     */

    public $img_nric;
    public $img_insurance;
    public $img_authorization;
    public $img_log_card;
    public $img_profile;

    public $transfer_no;
    public $transfer_screenshot;
    public $transfer_banking_nick;
    public $transfer_date;
    public $transfer_amount;

    public $vendor_name;
    public $vendor_description;
    public $about;
    public $telephone_no;
    public $telephone_code;    
    public $founded_date;
    public $uiid;
    public $device_type;
    public $club_name;
    public $status;
    public $fullname;
    public $eun;
    public $number_of_employees;
    public $level;
    public $member_expire;

    public $model;

    public $company_mobile_code;
    public $company_mobile;
    public $company_email;
    public $company_country;
    public $company_postal_code;
    public $company_unit_no;
    public $company_add_1;
    public $company_add_2;
    public $insurance_date;

    public $brand_synopsis;
    public $brand_guide;
    public $club_logo;
    public $club_logo_file;

    public $social_media_id;
    public $social_media_type = 0;

    public $fcm_token;
    public $fcm_topics;
    public $notification_type;

    public $role;
    public $member_type;

    public $no_approval;
    public $club_code;
    public $verification_code;

    // public $directors = [];

    public function rules()
    {
        return [
     
            // [['email', 'password', 'password_confirm', 'account_id', 'mobile', 'mobile_code', 'fullname', 'uiid', 'device_type'], 'required', 'on' => ['register']],
            [['email', 'password', 'password_confirm', 'account_id', 'fullname', 'uiid', 'device_type'], 'required', 'on' => ['register']],
            // [['email', 'password', 'password_confirm', 'mobile', 'mobile_code', 'fullname', 'uiid', 'device_type'], 'required', 'on' => ['register-carkee-member', 'register-carkee-vendor']],
            
            [['email', 'password', 'password_confirm', 'fullname'], 'required', 'on' => ['register-carkee-member', 'register-carkee-vendor','admin-add-carkee-member', 'admin-add-carkee-vendor']],
            // [['company', 'email', 'password', 'password_confirm', 'mobile', 'mobile_code', 'fullname', 'uiid', 'device_type'], 'required', 'on' => ['register-carkee-club', 'register-vendor']],
            [['company', 'email', 'password', 'password_confirm', 'fullname', 'uiid', 'device_type'], 'required', 'on' => ['register-carkee-club', 'register-vendor']],
            [['firstname','lastname','add_1','add_2','profession','company','contact_person','company_add_1','company_add_2','vendor_name','vendor_description'], 'string','max'=>255],
            ['company', 'unique', 'targetClass' => Account::class, 'filter' => function ($query) {
                $model = $this->getModel();

                if (!$model->isNewRecord) {
                    $query->andWhere(['not', ['account_id' => $model->account_id]]);
                }
            }, 'on' => ['register-carkee-club',]],

            ['account_id', 'validateAccount', 'on' => ['register', 'register-vendor']],
            ['device_type', 'validateDevice', 'on' => ['register', 'register-carkee-member', 'register-carkee-vendor', 'register-carkee-club', 'register-vendor']],
            ['email', 'email', 'on' => ['register', 'register-carkee-member', 'register-carkee-vendor', 'register-carkee-club', 'admin_edit_vendor', 'register-vendor','admin-add-carkee-member', 'admin-add-carkee-vendor','admin-carkee-vendor-add']],
            ['email', 'validateEmail', 'on' => ['register', 'register-carkee-member', 'register-carkee-vendor', 'register-carkee-club', 'account_add_vendor', 'account_add_sponsor', 'account_edit_vendor', 'account_edit_sponsor','admin_add_vendor', 'admin_edit_vendor', 'register-vendor','admin-add-carkee-member', 'admin-add-carkee-vendor','admin-carkee-sponsor-edit','admin-carkee-sponsor-edit']],

            // [['email'], 'required', 'on' => ['admin_edit_vendor', 'account_edit_vendor', 'account_edit_sponsor',]],
            [['email'], 'required', 'on' => ['account_edit_sponsor',]],
            [['role'], 'required', 'on' => ['account_add_sponsor', 'account_edit_sponsor']],

            [['email', 'password'], 'required', 'on' => ['account_add_vendor', 'account_add_sponsor', 'admin_add_vendor','admin-carkee-sponsor-add']],
            ['email', 'email', 'on' => ['account_add_vendor','account_add_sponsor', 'account_edit_vendor', 'account_edit_sponsor', 'admin_add_vendor', 'admin_edit_vendor','admin-carkee-vendor-edit','admin-carkee-sponsor-edit']],

            [['password_confirm'], 'compare', 'compareAttribute' => 'password', 'on' => ['register', 'register-carkee-member', 'register-carkee-vendor', 'register-carkee-club', 'register-vendor']],

            [['country', 'postal_code', 'add_1', 'gender', 'birthday', 'nric'], 'trim', 'on' => ['carkee-member-step1']],
            [['country', 'postal_code', 'add_1', 'gender', 'birthday', 'nric'], 'required', 'on' => ['step1-carkee', 'step1-mclub', 'step1-p9club']],
            [['gender', 'birthday', 'nric', 'company', 'about', 'number_of_employees', 'country', 'postal_code', 'add_1'], 'required', 'on' => ['club-step1']],

            ['gender', 'in', 'range' => self::genders(TRUE), 'strict'=>FALSE, 'on' => ['step1-mclub', 'step1-p9club', 'carkee-member-step1', 'club-step1']],

            // ['annual_salary', 'validateAnnualSalary', 'on' => ['step1-mclub']],

            ['nric', 'validateNRIC', 'on' => ['step1-carkee', 'step1-mclub', 'step1-p9club', 'carkee-member-step1', 'club-step1', 'edit_member-mclub', 'edit_member-p9club', 'carkee_edit_member', 'admin_edit_member', 'edit_club', 'admin_edit_vendor','admin-carkee-vendor-edit']],
            ['postal_code', 'number', 'on' => ['step1-carkee', 'step1-mclub', 'step1-p9club', 'carkee-member-step1', 'club-step1', 'edit_member-mclub', 'edit_member-p9club', 'carkee_edit_member', 'admin_edit_member', 'edit_club', 'admin_edit_vendor','admin-carkee-vendor-edit']],
            ['postal_code', 'string', 'length' => 6, 'notEqual' => 'Postal Code should contain 6 digits.', 'on' => ['step1-carkee', 'step1-mclub', 'step1-p9club', 'carkee-member-step1', 'club-step1', 'edit_member-mclub', 'edit_member-p9club', 'carkee_edit_member', 'admin_edit_member', 'edit_club', 'admin_edit_vendor','admin-carkee-vendor-edit']],

            ['mobile', 'number', 'on' => ['register', 'register-vendor', 'register-carkee-member', 'register-carkee-vendor', 'register-carkee-club', 'account_add_vendor', 'account_add_sponsor', 'account_edit_vendor','account_edit_sponsor', 'edit_vendor', 'admin_add_vendor', 'admin_edit_vendor','admin-add-carkee-member', 'admin-add-carkee-vendor','admin-carkee-vendor-add','admin-carkee-vendor-edit','admin-carkee-sponsor-add','admin-carkee-sponsor-edit']],
            ['mobile', 'string', 'length' => 8, 'notEqual' => 'Mobile should contain 8 digits.', 'on' => ['register', 'register-vendor', 'register-carkee-member', 'register-carkee-vendor', 'register-carkee-club', 'account_add_vendor', 'account_edit_vendor', 'edit_vendor', 'admin_add_vendor', 'admin_edit_vendor','admin-add-carkee-member', 'admin-add-carkee-vendor','admin-carkee-vendor-add','admin-carkee-vendor-edit']],

            // [['mobile_code', 'mobile'], 'validateMobile', 'on' => ['register', 'register-vendor', 'register-carkee-member', 'register-carkee-vendor', 'register-carkee-club','admin-add-carkee-member', 'admin-add-carkee-vendor']],

            /**
             * for mclub only
             */
            [['chasis_number'], 'required', 'on' => ['step2-carkee', 'step2-mclub']],
            [['plate_no', 'car_model', 'registration_code', 'are_you_owner'], 'required', 'on' => ['step2-carkee', 'step2-mclub', 'step2-p9club']],

            /**
             * for p9club only
             */
            [['insurance_date'], 'required', 'on' => ['step2-p9club']],
            [['insurance_date'], 'date', 'format' => 'yyyy-mm-dd', 'on' => ['step2-p9club']],

            [['plate_no', 'car_model', 'registration_code', 'are_you_owner'], 'trim', 'on' => ['carkee-member-step2']],
            [['contact_person', 'emergency_no', 'emergency_code', 'relationship'], 'trim', 'on' => ['step3-carkee', 'step3-mclub', 'step3-p9club', 'carkee-member-step3']],

            [['emergency_no','emergency_code'], 'number', 'on' => ['step3-carkee', 'step3-mclub', 'step3-p9club', 'carkee-member-step3','admin_edit_member']],
            ['emergency_no', 'string', 'length' => 8, 'notEqual' => 'Mobile should contain 8 digits.', 'on' => ['step3-carkee', 'step3-mclub', 'step3-p9club', 'carkee-member-step3','admin_edit_member']],

            [['transfer_amount'], 'trim', 'on' => ['step5-carkee', 'step5-mclub', 'step5-p9club', 'vendor-step3', 'carkee-member-step5']],
            // ['transfer_amount', 'compare', 'compareValue' => 0, 'operator' => '>', 'type' => 'number', 'on' => ['step5-mclub']],

            [['fullname', 'country', 'postal_code', 'add_1', 'gender', 'birthday', 'nric', 'contact_person', 'emergency_no', 'emergency_code', 'relationship'], 'required', 'on' => ['edit_member-mclub', 'edit_member-p9club']], /* club api */
            [['fullname', 'country', 'postal_code', 'add_1', 'gender', 'birthday', 'nric', 'contact_person', 'emergency_no', 'emergency_code', 'relationship'], 'required', 'on' => ['account_edit_member']], /* carkee api */
            [['fullname', 'nric', 'birthday', 'gender', 'country', 'postal_code', 'add_1', 'contact_person', 'emergency_no', 'emergency_code', 'relationship'], 'required', 'on' => ['admin_edit_member']], /* carkee admin */
            [['fullname', 'gender', 'birthday', 'nric', 'company', 'about', 'number_of_employees', 'country', 'postal_code', 'add_1' ], 'required', 'on' => ['edit_club']],

            // [['vendor_name', 'country', 'postal_code', 'add_1', 'mobile_code', 'mobile', 'about', 'founded_date'], 'required', 'on' => ['account_add_vendor', 'account_add_sponsor', 'account_edit_vendor', 'account_edit_sponsor', 'admin_add_vendor', 'admin_edit_vendor']],
            [['vendor_name', 'country', 'postal_code', 'add_1', 'about', 'founded_date'], 'required', 'on' => ['account_add_vendor', 'account_add_sponsor', 'account_edit_vendor', 'account_edit_sponsor', 'admin_add_vendor', 'admin_edit_vendor','admin-carkee-sponsor-add','admin-carkee-vendor-edit','admin-carkee-sponsor-edit']],
            [['vendor_name', 'add_1', 'founded_date'], 'required', 'on' => ['admin-carkee-vendor-add']],
            ['vendor_name', 'unique', 'targetClass' => User::class, 'on' => ['admin-carkee-vendor-add']],
            
            [[
                'company', 'fullname', 'telephone_code', 'telephone_no', 'company_email', 'company_country', 'company_postal_code', 'company_add_1',
                'gender', 'birthday', 'nric', 'country', 'postal_code', 'add_1',
                'about'
            ], 'required', 'on' => ['edit_vendor']],

            [['chasis_number'], 'required', 'on' => ['edit_vehicle-mclub']],
            [['plate_no', 'car_model', 'registration_code'], 'required', 'on' => ['edit_vehicle-mclub', 'edit_vehicle-p9club','edit_vehicle-carkee']],

            [['insurance_date'], 'required', 'on' => ['edit_vehicle-p9club']],
            // [['plate_no', 'car_model', 'registration_code'], 'required', 'on' => ['carkee-member-edit-vehicle']],

            [[
                'user_id', 'add_2', 'telephone_no', 'telephone_code', 'founded_date', 'about', 'status', 'password', 'eun', 
                'vendor_description', 'unit_no', 'level', 'company_unit_no', 'company_add_2',
                'profession', 'company', 'annual_salary', 'chasis_number', 'insurance_date','role',

                'company', 'fullname', 'telephone_code', 'telephone_no', 'company_email',
                'company_country', 'company_postal_code', 'company_add_1',
                'gender', 'birthday', 'nric', 'country', 'postal_code', 'add_1',
                'about',
                // 'user_id', 'add_2', 'founded_date', 'status', 'eun',
                // 'vendor_description', 'unit_no', 'level', 'company_unit_no', 'company_add_2',
                // 'profession', 'annual_salary', 'chasis_number', 'insurance_date',
                'plate_no', 'car_model', 'registration_code', 'club_code', 'verification_code',
                'club_logo_file', 'club_name',
                // 'uiid', 'device_type',

                'no_approval','social_media_id','social_media_type','fcm_token', 'fcm_topics', 'notification_type','member_expire','member_type'
            ], 'safe'],

            // [[
            //     'user_id', 'add_2', 'telephone_no', 'telephone_code', 'founded_date', 'about', 'status', 'password', 'eun', 'vendor_description', 'unit_no', 'level', 'company_unit_no', 'company_add_2',
            //     'profession', 'company', 'annual_salary', 'chasis_number', 'insurance_date','role',

            //     'company', 'fullname', 'telephone_code', 'telephone_no', 'company_email',
            //     'company_country', 'company_postal_code', 'company_add_1',
            //     'gender', 'birthday', 'nric', 'country', 'postal_code', 'add_1',
            //     'about',
            //     'user_id', 'add_2', 'founded_date', 'status', 'eun',
            //     'vendor_description', 'unit_no', 'level', 'company_unit_no', 'company_add_2',
            //     'profession', 'annual_salary', 'chasis_number', 'insurance_date',
            //     'plate_no', 'car_model', 'registration_code',
                
            // ], 'safe',''],
        
            /**
             * Club Company update
             */
            [['telephone_code', 'telephone_no', 'company_email', 'company_country', 'company_postal_code', 'company_add_1'], 'required', 'on' => ['vendor-step1']],
            // [['gender', 'birthday', 'nric', 'country', 'postal_code', 'add_1'], 'trim', 'on' => ['vendor-step2']],
            [['gender', 'birthday', 'nric', 'country', 'postal_code', 'add_1'], 'required', 'on' => ['vendor-step2']],
            ['gender', 'in', 'range' => self::genders(TRUE), 'strict'=>FALSE, 'on' => ['vendor-step2']],


            [['gender', 'birthday', 'nric','company', 'eun', 'about', 'company_add_1', 'company_add_2'], 'trim', 'on' => ['company-step1'] ],
            
            [['user_id'], 'required', 'on' => ['update-company']],
            
            [['gender', 'birthday', 'nric'], 'required', 'on' => ['company-step1','update-company']],
            [['company', 'about', 'number_of_employees'], 'required', 'on' => ['company-step1']],
            [['company', 'eun', 'about', 'number_of_employees'], 'required', 'on' => ['update-company']],
            [['company_country', 'company_postal_code', 'company_unit_no', 'company_add_1'], 'required', 'on' => ['company-step1']],
            [['company_country', 'company_postal_code', 'company_unit_no', 'company_add_1', 'company_add_2'], 'required', 'on' => ['update-company']],
                       
            ['brand_synopsis', 'string','on' => ['brand-synopsis']],
            ['club_logo', 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg','on' => ['request-new-club']],
            [['club_name'], 'string','on' => ['request-new-club']],
            [['club_name','club_logo'], 'required','on' => ['request-new-club']],
            // [['directors'], 'validateDirectors','on' => ['register-vendor']],
            // [['social_media_id','social_media_type','fcm_token', 'fcm_topics', 'notification_type','member_expire','member_type'],'safe'],
            //  proof of payment
            ['transfer_screenshot', 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg', 'on' => ['admin_edit_member']],


            //Documents
            [['img_nric', 'img_insurance', 'img_authorization', 'img_log_card', 'img_profile'], 'safe'],
            [['img_nric', 'img_insurance', 'img_authorization', 'img_log_card'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, pdf', 'maxSize' => 1024 * 1024 * 20, 'on' => ['edit_documents']],
           
            // ==================            
            // [['email', 'password', 'password_confirm', 'mobile', 'mobile_code', 'fullname'], 'required', 'on' => ['carkee-add-member', 'carkee-add-vendor']],
        ];
    }
    
    public function getModel()
    {
        if (!$this->model) $this->model = new Account();

        return $this->model;
    }

    public function validateNRIC($attr)
    {
        if (!empty($this->nric) AND !preg_match("/^[a-zA-Z]{1}\d{7}[a-zA-Z]{1}$/", $this->nric)){
            return $this->addError('nric', 'Invalid NRIC format');
        }
    }

    public function validateDevice($attr)
    {
        if (!in_array($this->device_type, ['ios', 'android'])) {
            return $this->addError('device_type', 'Invalid device type');   
        }
    }

    // public function validateAnnualSalary($attr)
    // {
    //     if ((float)$this->annual_salary <= 0) {
    //         $this->addError('annual_salary', 'Invalid annual salary.');
    //     }
    // }

    public function validatePassword($attr)
    {
        if ($this->password !== $this->password_confirm){
            $this->addError('password', 'Please confirm password.');            
        }
    }

    public function validateAccount($attr)
    {
        /**
         * Don't validate if app is Carkee API
         */
        if (Common::isCarkeeApi()) return;

        $account = Account::find($this->account_id);

        if (!$account) {
            $this->addError('account_id', 'Invalid account.');                        
        }
    }

    public function validateEmail($attribute, $params)
    {
        /**
         * Only validate if using club app
         */
        if (Common::isClubApi()){
            $account = Account::findOne($this->account_id);

            if (!$account) {
                return $this->addError($attribute, 'Invalid account.');                        
            }
        }

        $user = User::find()
            ->where(['email' => $this->email])
            ->andWhere(['account_id' => $this->account_id])
            ->andWhere(['<>', 'user_id', $this->user_id])
            ->one();

        if ($user) {
            return $this->addError($attribute, "Email already exists.");
        }
    }

    public function validateMobile($attribute, $params)
    {
        /**
         * Only validate if using club app
         */
        if (Common::isClubApi()){
            $account = Account::findOne($this->account_id);

            if (!$account) {
                $this->addError($attribute, 'Invalid account.');                        
            }
        }

        $user = User::find()
            ->where(['mobile' => $this->mobile])
            ->andWhere(['mobile_code' => $this->mobile_code])
            ->andWhere(['account_id' => $this->account_id])
            ->andWhere(['!=', 'user_id', $this->user_id])
            ->one();

        if ($user) {
            $this->addError($attribute, "Mobile already exists.");
        }
    }


    public function attributeLabels()
    {
        $user = new User;
        
        return $user->attributeLabels();
    }

    public static function genders($keys = FALSE)
    {
        $genders = [
            'm' => 'Male',
            'f' => 'Female',
        ];

        if ($keys) $genders = array_flip($genders);

        return $genders;
    }

    public static function maritals()
    {
        return [
           's' => 'Single',
           'm' => 'Married', 
           'w' => 'Widow/ Widower',
           'd' => 'Divorcee',
        ];
    }

    public static function yesNo()
    {
        return [
            0 => 'No',
            1 => 'Yes',
        ];
    }


    public function isUniqueVendor($attr)
    {
        if (!$this->user) {
            return $this->addError('vendor_name', 'vendor not found.');
        }

        $found = User::find()
        ->where(['account_id' => $this->account_id])
        ->andWhere(['<>', 'user_id', $this->user_id])
        ->andWhere(['vendor_name' => $this->vendor_name])
        ->one();
        
        if ($found) {
            return $this->addError('email', 'Vendor Name already exists.');
        }
    }





    // public function validateDirectors(){
    //     try {
           
    //         if(!empty($this->directors)){
    //             $index = 0;
    //             foreach($this->directors as $value){
    //                 $directorform = new DirectorForm;
    //                 // $directorsarr = json_decode( $value , true );
    //                 $directorsarr = Json::decode($value, true );
    //                 // $directorsarr = ArrayHelper::toArray($value);
    //                 // Yii::info($value, 'api-carkee');
    //                 // Yii::info($directorsarr, 'api-carkee');
    //                 foreach($directorsarr as $key => $item){
    //                     if(!empty($item)) $directorform->{$key} = $item;
    //                 }
    //                 if (!$directorform->validate()) {

    //                     $resultErrors       = $directorform->getFirstErrors();
    //                     $resultErrorField   = "";
    //                     $resultErrorValue   = "";

    //                     foreach($resultErrors as $key => $error){
    //                         $resultErrorField = $key;
    //                         $resultErrorValue = $error;
    //                         break;                                
    //                     }

    //                     $this->addError($resultErrorField . "_" . $index, 'Director Details: #' . ($index+1) . ' ' . $resultErrorValue);
    //                 }
    //                 $index++;
    //             }
    //         }
    //     } catch (\Exception $e) {
    //         $this->addError('directors', $e->getMessage());
    //     }
    // }
}
