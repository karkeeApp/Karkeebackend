<?php
namespace common\forms;

use Yii;
use yii\base\Model;
use common\models\Settings;
use common\helpers\Common;

class UserSettingsForm extends Model
{
    public $user_id;
    public $account_id;
    public $member_type;
    public $carkee_member_type;
    public $level;
    public $carkee_level;
    public $club_code;
    public $enable_ads;
    public $is_one_approval;
    public $renewal_alert;
    public $skip_approval;
    public $member_expiry;
    
    public function rules()
    {
        return [
            [['carkee_level', 'level','account_id','club_code','member_expiry','enable_ads','is_one_approval','renewal_alert','skip_approval'], 'safe'],
            [['club_code','enable_ads','is_one_approval','renewal_alert','skip_approval'], 'integer', 'on' => ['update-default-settings']],
            [['user_id','account_id'], 'required', 'on' => ['update-default-settings']],
            [['carkee_level'], 'trim', 'on' => ['account_add', 'admin_add']]
        ];
    }

    public function attributeLabels()
    {
        return [
            'member_type' => 'Membership Type', 
        ];
    }
}
