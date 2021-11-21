<?php
namespace common\forms;

use Yii;
use yii\base\Model;

class UserNotificationForm extends Model {

   public $notification_id;
   public $message;
   public $title;
   public $user_id;
   public $is_read = 0;

   public function rules()
   {
       return [

           [['user_id','title','message'], 'safe'],
           [['title','message'], 'trim'],
           [['title'], 'string','max'=>255],
           [['notification_id','user_id'], 'integer'],
           
       ];
   }
}