<?php
namespace common\forms;

use Yii;
use yii\base\Model;
use common\helpers\Common;

class MapSettingsForm extends Model
{
    public $user_id;
    public $longitude;
    public $latitude;

    public function rules()
    {
        return [
            [['longitude', 'latitude'], 'trim'],
            [['longitude', 'latitude'], 'required', 'on' => ['admin_add', 'account_add']],
            [['longitude', 'latitude'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
        ];
    }
}
