<?php
namespace common\forms;

use Yii;
use yii\base\Model;
use common\models\Settings;
use common\helpers\Common;

class ClubSettingsForm extends Model
{
    public $user_id;
    public $carkee_member_type;

    public function rules()
    {
        return [
            [['carkee_member_type'], 'required', 'on' => ['admin_add']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'carkee_member_type' => 'Membership Type', 
        ];
    }
}
