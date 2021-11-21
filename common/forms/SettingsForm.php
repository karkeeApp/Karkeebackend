<?php
namespace common\forms;

use Yii;
use yii\base\Model;
use common\helpers\Common;

/**
 * SettingsForm form
 */
class SettingsForm extends Model
{
    public $setting_id;
    public $default_interest;
    public $content;
    public $renewal_fee;
    public $account_id;
    public $user_id;
    public $member_expiry;
    public $enable_ads;
    public $skip_approval;
    public $renewal_alert;
    public $club_code;
    public $is_one_approval;
    public $days_unverified_reg;

    public function rules()
    {
        return [
            [['renewal_fee'], 'required', 'on' => ['edit-settings','add-settings']],
            [['enable_ads','skip_approval','renewal_alert', 'club_code','is_one_approval'],
                'integer', 'on' => ['update-default-settings']],
            [['setting_id','default_interest', 'content', 'renewal_fee','account_id',
                'user_id','member_expiry','enable_ads','skip_approval','renewal_alert',
                'club_code','is_one_approval','days_unverified_reg'
            ], 'safe'],
        ];
    }
}
