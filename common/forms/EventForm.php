<?php
namespace common\forms;

use Yii;
use yii\base\Model;
use common\helpers\Common;
use common\models\Event;

class EventForm extends Model
{
    public $event_id;
    public $content;
    public $title;
    public $summary;
    public $image;
    public $order = 0;
    public $is_public;
    public $place;
    public $event_time;
    public $event_date;
    public $cut_off_at;
    public $limit;
    public $account_id = 0;
    public $notification_type;
    public $is_paid;
    public $event_fee;

    public function rules()
    {
        return [
            [['title','summary','place'], 'trim'],
            [['title', 'summary'], 'string','max'=>255],
            [['place'], 'string','max'=>255],
            [['content', 'title', 'summary'], 'required', 'on' => ['account_add', 'account_edit', 'admin_add', 'admin_edit', 'admin-carkee-add', 'admin-carkee-edit']],
            // ['event_time', 'validatedEventTime', 'on' => ['account_add', 'account_edit', 'admin_add', 'admin_edit']],
            ['image', 'file', 'skipOnEmpty' => TRUE, 'extensions' => 'png, jpg, jpeg', 'maxSize' => 1024 * 1024 * 20],
            ['is_public', 'default', 'value' => 0],
            ['event_fee', 'validateEventFee', 'on' => ['account_add', 'account_edit', 'admin_add', 'admin_edit', 'admin-carkee-add', 'admin-carkee-edit'] ],
            // ['event_fee', 'required', 'when' => function() {
            //         return $this->is_paid > 0;
            //     }, 'on' => ['account_add', 'account_edit', 'admin_add', 'admin_edit', 'admin-carkee-add', 'admin-carkee-edit']
            // ],
            [['event_id', 'order', 'is_public', 'place', 'event_time', 'limit', 'cut_off_at', 'is_paid','event_fee'], 'required', 'on' => ['set-default-settings']],
            [['account_id','event_id', 'content', 'title', 'summary', 'order', 'is_public', 'place', 'event_time', 'limit',
                'notification_type','cut_off_at', 'is_paid','event_fee','event_date'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'image'     => 'Featured Image',
            'place'     => 'Event Location',
            'limit'     => 'No. of Pax',
            'is_public' => 'Enable Public Preview',
            'cut_off_at'=> 'Cut Off At'
        ];
    }
    public function validateEventFee($attr, $params){
        if($this->is_paid > 0 AND $this->event_fee <= 0){
            $this->addError('is_paid', 'Event is Paid, Is this a paid event? then Event Fee should not be zero or blank ');
            return;
        }
        if(empty($this->is_paid) AND $this->event_fee > 0){
            $this->addError('event_fee', 'Event fee is not zero, Do you intend it as paid event? then enable Is Paid switch ');
            return;
        }

    }
}
