<?php
namespace common\forms;

use Yii;
use yii\base\Model;

class AccountSettingsForm extends Model
{
    public $member_expiry;
    public $num_days_expiry;
    public $club_code;
    public $enable_ads;
    public $is_one_approval;
    public $renewal_alert;
    public $skip_approval;
    public $days_unverified_reg;
    public $account_id = 0;

    public function rules()
    {
        return [

            [['member_expiry','num_days_expiry','account_id','club_code','enable_ads','is_one_approval','renewal_alert','skip_approval','days_unverified_reg'], 'safe'],
            [['account_id','club_code','enable_ads','is_one_approval','renewal_alert','skip_approval','days_unverified_reg'], 'integer'],
            // [['account_id','club_code','enable_ads','is_one_approval','renewal_alert','skip_approval'], 'required', 'on' => ['carkee-add-payment','add-payment']],

        ];
    }
}
