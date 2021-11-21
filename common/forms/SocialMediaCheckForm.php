<?php
namespace common\forms;

use Yii;
use yii\base\Model;

class SocialMediaCheckForm extends Model
{
    public $sm_token;
    public $login_type = 1;
    public $account_id = 0;

    public function rules()
    {
        return [

            [['account_id','sm_token','login_type'], 'safe'],
            ['sm_token', 'trim'],
            [['sm_token','login_type','account_id'], 'required']
        ];
    }
}
