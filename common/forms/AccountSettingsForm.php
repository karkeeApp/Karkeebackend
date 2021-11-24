<?php
namespace common\forms;

use common\models\Account;
use Yii;
use yii\base\Model;

class AccountSettingsForm extends Model
{
    public $default_interest = 1.0;
    public $renewal_fee = 100.0;
    public $company;
    public $title;
    public $file;
    public $logo;
    public $content;
    public $email;
    public $contact_name;
    public $address;
    public $member_expiry;
    public $num_days_expiry;
    public $club_code;
    public $enable_ads;
    public $enable_banner;
    public $is_one_approval;
    public $renewal_alert;
    public $skip_approval;
    public $days_unverified_reg;
    public $account_id = 0;
    public $master_account_id = 0;

    public function rules()
    {
        return [

            [['member_expiry','num_days_expiry','account_id','club_code','enable_ads','is_one_approval','renewal_alert','skip_approval',
                'days_unverified_reg', 'num_days_expiry', 'enable_banner', 'club_code','master_account_id','address','contact_name','email',
                'content','logo','title','company','default_interest','renewal_fee'], 'safe'],
            [['account_id','master_account_id','club_code','enable_ads', 'enable_banner','is_one_approval','renewal_alert','skip_approval',
                'days_unverified_reg','num_days_expiry'], 'integer'],
            [['default_interest','renewal_fee'],'double'],
            ['company', 'unique', 'targetClass' => Account::class],
            ['file', 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg', 'maxSize' => 1024 * 1024 * 20]
            // [['account_id','club_code','enable_ads','is_one_approval','renewal_alert','skip_approval'], 'required', 'on' => ['carkee-add-payment','add-payment']],

        ];
    }
}
