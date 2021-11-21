<?php
namespace common\forms;

use Yii;
use yii\base\Model;
use common\helpers\Common;
use common\models\Event;

class EventAttendeeForm extends Model
{
    public $id;
    public $event_id;
    public $user_id;
    public $name;
    public $description;
    public $filename;
    public $paid;
    public $num_guest_brought;

    public function rules()
    {
        return [
            [['name','description'], 'trim'],
            [['name'], 'string','max'=>255],
            [['id','event_id','user_id','num_guest_brought','paid','name','description','filename'], 'safe'],
        ];
    }
}
