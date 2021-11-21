<?php
namespace common\forms;

use Yii;
use yii\base\Model;
use common\models\User;
use common\helpers\Common;

/**
 * Login form
 */
class MobileForm extends Model
{
    public $user_id;
    public $mobile;
    public $mobile_code;

    public function rules()
    {
        return [
            [['mobile', 'mobile_code'], 'trim'],
            ['mobile', 'validateMobile', 'on' => ['add', 'admin_add', 'account_add']],
            [['user_id'], 'safe'],
        ];
    }

    public function validateMobile($attribute, $params)
    {   
        $user = User::findOne($this->user_id);

        if (!$user ) {
            $this->addError($attribute, 'Member not found.');
            return;
        }

        $check = User::find()
            ->where(['mobile' => $this->mobile])
            ->andWhere(['mobile_code' => $this->mobile_code])
            ->andWhere(['<>', 'user_id', $user->user_id])
            ->andWhere(['account_id' => $user->account_id])
            ->one();

        if ($check) {
            $this->addError($attribute, 'Mobile number already exists.');
        }
    }

}
