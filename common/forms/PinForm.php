<?php
namespace common\forms;

use Yii;
use yii\base\Model;
use common\models\Service;
use common\helpers\Common;

class PinForm extends Model
{
    public $user_id = 0;
    public $pin;
    public $pin_confirm;

    public function rules()
    {
        return [
            [['pin', 'pin_confirm'], 'required'],
            [['pin', 'pin_confirm'], 'string', 'length' => 4],
            ['pin_confirm', 'validatePin', 'when' => function($model) {
                return !empty($this->pin);
            }],            
            [['user_id', 'pin', 'pin_confirm'], 'safe'],
        ];
    }

    public function validatePin()
    {
        if ($this->pin !== $this->pin_confirm){
            $this->addError('pin_confirm', Yii::t('app', 'Please confirm pin code.'));
        }
    }
}