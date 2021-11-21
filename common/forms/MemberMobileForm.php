<?php
namespace common\forms;

use Yii;
use yii\base\Model;
use common\models\User;
use common\helpers\Common;

class MemberMobileForm extends Model
{
    public $user;
    public $mobile_code;
    public $mobile;

    public function rules()
    {
        return [
            [['mobile_code', 'mobile'], 'required'],
            [['mobile_code', 'mobile'], 'isUnique'],
        ];
    }

    public function isUnique($attr)
    {
        if (!$this->user) {
            return $this->addError('mobile', 'User not found.');
        }

        $found = User::find()
        ->where(['account_id' => $this->user->account_id])
        ->andWhere(['<>', 'user_id', $this->user->user_id])
        ->andWhere(['mobile_code' => $this->mobile_code])
        ->andWhere(['mobile' => $this->mobile])
        ->one();
        
        if ($found) {
            return $this->addError('mobile', 'Mobile already exists.');
        }
    }
}