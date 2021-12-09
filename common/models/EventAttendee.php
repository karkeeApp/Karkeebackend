<?php
namespace common\models;

use common\helpers\Common;
use common\lib\Helper;
use Yii;
use yii\db\ActiveRecord;

use yii\helpers\Url;

class EventAttendee extends ActiveRecord
{    
    const STATUS_PENDING = 1;
    const STATUS_CANCELLED = 2;
    const STATUS_CONFIRMED = 3;
    const STATUS_DELETED = 4;

    public static function tableName()
    {
        return '{{%event_attendee}}';
    }

    public function insert($runValidation = true, $attributes = NULL)
    {
        $this->created_at = date('Y-m-d H:i:s');
        $this->updated_at = date('Y-m-d H:i:s');
        
        return parent::insert($runValidation, $attributes);
    }

    public function update($runValidation = true, $attributes = NULL)
    {
        $this->updated_at = date('Y-m-d H:i:s');
        return parent::update($runValidation, $attributes);
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['user_id' => 'user_id']);
    }
    public function getCreatedby()
    {
        return $this->hasOne(User::class, ['user_id' => 'created_by']);
    }
    public function getEvent()
    {
        return $this->hasOne(Event::class, ['event_id' => 'event_id']);
    }
    public function getPayments()
    {
        return $this->hasMany(UserPayment::class, ['event_id' => 'event_id' ,'user_id' => 'user_id']); //->where(['user_id' => 'user_id']); //->andWhere(['payment_for' => UserPayment::PAYMENT_FOR_EVENT]);
    }
    public function getPayment()
    {
        return $this->hasOne(UserPayment::class, ['event_id' => 'event_id' ,'user_id' => 'user_id']); //->orderBy(['id' => SORT_DESC]); //->where(['user_id' => 'user_id']); //->andWhere(['payment_for' => UserPayment::PAYMENT_FOR_EVENT]);
    }

    public function getIs_image_payment()
    {
        $dir = Yii::$app->params['dir_renewal'];
        $filename = $this->filename;
        if(file_exists($dir . $filename)){

            $mimeType = mime_content_type($dir . $filename);
            if(preg_match("/image/", $mimeType)){
                return true;
            }
        }
        return false;
    }

    public function getScreenshot_mime_type(){
        if (!empty($this->filename)) {
            $file = Yii::$app->params['dir_payment'] . $this->filename;

            if (file_exists($file)) {
                return mime_content_type($file); 
            }
        } 
        return "";
    }

    public function filelink(){ return !empty($this->filename)? Url::home(TRUE) . 'file/payment?event_id=' . $this->event_id . '&user_id=' . $this->user_id . '&f=' . $this->filename : NULL; }  //. '?access-token=' . Yii::$app->request->get('access-token') : NULL;
    
    public function isActive()
    {
        return ($this->isPending() OR $this->isConfirmed());
    }

    public function isPending()
    {
        return $this->status == self::STATUS_PENDING;
    }

    public function isDeleted()
    {
        return $this->status == self::STATUS_DELETED;
    }

    public function isConfirmed()
    {
        return $this->status == self::STATUS_CONFIRMED;
    }

    public function isCancelled()
    {
        return $this->status == self::STATUS_CANCELLED;
    }

    public function status()
    {
        return self::statuses()[$this->status];
    }

    public static function statuses()
    {
        return [
            self::STATUS_PENDING   => 'Pending',
            self::STATUS_CANCELLED => 'Cancelled',
            self::STATUS_CONFIRMED => 'Confirmed',
            self::STATUS_DELETED   => 'Deleted'
        ];
    }
    public function getEvent_payments(){
        $data = [];
        if(!empty($this->payments)){
            foreach($this->payments as $payment) $data[] = $payment->data();
        }

        return $data;
    }

    public function data()
    {
        $data = [];
        $data = $this->attributes;
        $data['payment'] = ($this->payment ? $this->payment->data() : null);
        // $data['payment'] = [
        //                         'link' => $this->filelink(),
        //                         'mime_type' => $this->screenshot_mime_type,
        //                         'is_image' => $this->is_image_payment
        //                     ];
        $data['event'] = $this->event->data();
        $data['user'] = $this->user->data();
        // $data['payments'] = (!empty($this->event_payments) ? $this->event_payments : []);
        return $data;
    }
       
    public function cancelAttendee()
    {
        $response = "";
        if($this->isPending()){
            $this->status = EventAttendee::STATUS_CANCELLED;
            $this->save();

            $response = $this->notifyEventCancelledAttendees();

            return [ 'status' => true, 'response' => $response];
        }

        return [ 'status' => false, 'response' => $response];
    }

    private function notifyEventCancelledAttendees(){
        // Send notification to Admin/Event Director for every cancelled attendee
        $ctitle = "";
        $cdesc = "";
        $cresponse = "";
        $title ="One (1) had cancelled to Attend. Event id: ".$this->event_id." ".(!empty($this->event->title) ? " ,Title: ".$this->event->title : "");
        $desc =($this->user->fullname ? $this->user->fullname : $this->user->firstname).
                " had cancelled to attend the event. That would bring our head count to ".count($this->event->attendees)." out of ".$this->event->limit." expected attendee(s). ".
                " Confirmed Attendee(s) is ".$this->event->confirmed_attendees()." and Cancelled Attendee(s) is ".$this->event->cancelled_attendees().". ".($this->event->pending_attendees() > 0 ? "Pending Attendee(s) Confirmation from Event Director is ".$this->event->pending_attendees() : "");
        $response = Helper::pushNotificationFCM_Events($title,$desc,($this->user->account_id > 0 ? $this->user->account->company : "Karkee"),$this->user->account_id);
        // =========================

        // Checked event if where previously closed due to slots are full and it's reopened again because someone have cancelled.
        if($this->event->is_closed == Event::EVENT_IS_CLOSED){
            $ctitle = "One Slot were opened for Event Id: ".$this->event_id;
            $cdesc = "Hello Admin/Event Director, Informing you that a slot were opened. It seems that someone cancelled its attendance for Event Id: ".$this->event_id." ".(!empty($this->event->title) ? " ,Title: ".$this->event->title : "").". Head Count: ".count($this->event->attendees)." attendee(s) out of ".$this->event->limit." expected attendee(s). ".
            " Confirmed Attendee(s) is ".$this->event->confirmed_attendees()." and Cancelled Attendee(s) is ".$this->event->cancelled_attendees().". ".($this->event->pending_attendees() > 0 ? "Pending Attendee(s) Confirmation from Event Director is ".$this->event->pending_attendees() : "");
            $cresponse = Helper::pushNotificationFCM_Events($ctitle,$cdesc,($this->user->account_id > 0 ? $this->user->account->company : "Karkee"),$this->user->account_id);
       
            // Reopen upcoming event due to slot limits were increased
            $this->event->is_closed = Event::EVENT_NOT_CLOSED;
            $this->event->save();
            // =========================
        }
        // =========================

        return [
            'title'             => $title,
            'desc'              => $desc,
            'title_cancel'      => $ctitle,
            'desc_cancel'       => $cdesc,
            'response'          => $response,
            'response_cancel'   => $cresponse,
        ];

    }
}