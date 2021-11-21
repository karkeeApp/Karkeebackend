<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

use yii\helpers\Url;
use common\helpers\Common;
use common\behaviors\TimestampBehavior;
class UserLog extends ActiveRecord
{    

	public static function tableName()
    {
        return '{{%user_logs}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

	public static function create($data = [])
	{
		if(count($data) > 0){

			$log = new self;
			$log->user_id = $data['user_id'];
			if($data['type'] == 2){
				$log->renewal_id = $data['renewal_id'];
			}
			$log->log_card = $data['log_card'];
			$log->type = $data['type'];
			$log->save();

			return true;
		} else{
			return false;
		}
	}

	public function log_card()
    {
        
        if ($this->type == '1') {
        	if (Common::isApi() OR Common::isCarkeeApi()){

	            return ($this->log_card)? Url::home(TRUE) . 'member/log?t=' . $this->log_card . '&u=' . $this->user_id . '&f=img_log_card' . '&access-token=' . Yii::$app->request->get('access-token') : '';
        	} else{
	            return ($this->log_card)? Url::home(TRUE) . 'member/log?t=' . $this->log_card . '&u=' . $this->user_id . '&f=img_log_card' : '';
        	}
        } else {
        	if (Common::isApi() OR Common::isCarkeeApi()){
	            return ($this->log_card)? Url::home(TRUE) . 'member/renewal-attachment?t=' . $this->log_card . '&u=' . $this->renewal_id . '&f=log_card' . '&access-token=' . Yii::$app->request->get('access-token') : '';
        	} else{
	            return ($this->log_card)? Url::home(TRUE) . 'member/renewal-attachment?t=' . $this->log_card . '&u=' . $this->renewal_id . '&f=log_card' : '';
        	}
        }
    }
}