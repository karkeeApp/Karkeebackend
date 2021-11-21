<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\helpers\Common;

class Watchdog extends ActiveRecord
{
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public static function primaryKey()
    {
        return ['wid'];
    }

    public static function tableName()
    {
        return '{{%watchdog}}';
    }
    
    public function insert($runValidation = true, $attributes = NULL)
    {
        $this->created_at = date('Y-m-d H:i:s');
        return parent::insert($runValidation, $attributes);
    }

    public static function carkeeLog($message, $variables = [], $user = NULL)
    {
        return self::log($message, $variables, $user, 0);
    }

    public static function log($message, $variables = [], $user = NULL, $account_id =0)
    {
        if (!$user) $user = Yii::$app->user->identity;

        $user_id = $user ? $user->user_id : 0;

        $dog            = new self;
        $dog->user_id   = $user_id;
        $dog->message   = $message;
        $dog->variables = json_encode($variables);
        $dog->referer   = NULL;
        $dog->hostname  = 'ip';
        $dog->account_id = $account_id;
        $dog->save();

        /**
         * Send Telegram here
         */
    }

    public function message()
    {
        $variables = json_decode($this->variables, TRUE);

        if (!empty($variables)){
            $search = [];
            $replace = [];

            foreach($variables as $key => $val){
                $search = [$key];
                $replace = [$val];
            }

            $this->message = str_replace($search, $replace, $this->message);
        }

        return $this->message;
    }
}