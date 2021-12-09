<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

use common\helpers\Common;
use common\lib\Helper;
use yii\helpers\Url;

class Event extends ActiveRecord
{    
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;
    const STATUS_CANCELLED = 3;

    const EVENT_NOT_CLOSED = 0;
    const EVENT_IS_CLOSED = 1;

    public static function tableName()
    {
        return '{{%event}}';
    }

    public function insert($runValidation = true, $attributes = NULL)
    {
        $this->created_at = date('Y-m-d H:i:s');
        $this->created_by = Yii::$app->user->getId();
        
        return parent::insert($runValidation, $attributes);
    }

    public function getAccount()
    {
        return $this->hasOne(Account::class, ['account_id' => 'account_id']);
    }

    public function getGalleries()
    {
        return $this->hasMany(EventGallery::class, ['event_id' => 'event_id']);
    }

    public function getAttendees(){
        return $this->hasMany(EventAttendee::class,['event_id' => 'event_id'])->where(['<>','status', EventAttendee::STATUS_CANCELLED]);
    }

    public function getConfirmed_attendees(){
        return $this->hasMany(EventAttendee::class,['event_id' => 'event_id'])->where(['status' => EventAttendee::STATUS_CONFIRMED]);
    }
    public function getPending_attendees(){
        return $this->hasMany(EventAttendee::class,['event_id' => 'event_id'])->where(['status' => EventAttendee::STATUS_PENDING]);
    }


    public function status()
    {
        return self::statuses()[$this->status];
    }

    public static function statuses()
    {
        return [
            self::STATUS_ACTIVE        => "Active",
            self::STATUS_DELETED        => "Deleted",
            self::STATUS_CANCELLED        => "Cancelled"
        ];
    }

    public function confirmed_attendees(){
        return $this->confirmed_attendees ? count($this->confirmed_attendees) : 0; 
    }
    public function getCancelled_attendees(){
        return $this->hasMany(EventAttendee::class,['event_id' => 'event_id'])->where(['status' => EventAttendee::STATUS_CANCELLED]);
    }
    public function cancelled_attendees(){
        return $this->cancelled_attendees ? count($this->cancelled_attendees) : 0; 
    }
    public function pending_attendees(){
        return $this->pending_attendees ? count($this->pending_attendees) : 0; 
    }
    public function imagelink($hash_id = NULL)
    {
        if (Common::isApi() OR Common::isCarkeeApi()) {
            return ($this->image)? Url::home(TRUE) . 'file/event?id=' . $this->event_id . "&t={$this->image}" . '&access-token=' . Yii::$app->request->get('access-token') : '';
        } else if(Common::isAccount() OR Common::isCpanel()){
            return ($this->image)? Url::home(TRUE) . 'file/event?id=' . $this->event_id . "&t={$this->image}" : '';
        }

        return ($this->image)? Url::home(TRUE) . 'file/event/' . $this->event_id . "?t={$this->image}" . ($hash_id ? '&account_id=' . $hash_id : NULL) : NULL;
    }

    public function data($user = NULL)
    {
        // $token = ($user) ? "&access-token={$user->auth_key}" : '';
        $action = !$user ? 'view' : 'view-private';

        $attendee = ($user) ? $this->attendee($user) : NULL;
        $master_settings = Settings::find()->one();
        $data = [
            'event_id'    => $this->event_id,
            'account_id'  => $this->account_id,
            'club_account'=> (!empty($this->account->company) ? $this->account->company : (!empty($master_settings) ? $master_settings->company : "D Karkee")),
            'title'       => $this->title,
            'created_at'  => $this->created_at,
            'content'  => $this->content,
            'summary'     => $this->summary,
            'image'       => $this->imagelink($this->account ? $this->account->hash_id : NULL),
            'url'         => Url::home(TRUE) . 'event/' . $action . '?event_id=' . $this->event_id . ($user ? '&id=' . $user->user_id : NULL), // . $token,
            'is_attendee' => ($attendee AND $attendee->isActive()) ? true : false,
            'is_closed'   => $this->is_closed,
            'is_paid'     => (int)$this->is_paid,
            'event_fee'   => $this->event_fee ? $this->event_fee : 0,
            'event_date'  => $this->event_time,
            'cut_off_at'  => $this->cut_off_at ? $this->cut_off_at : $this->event_time,
            'place'       => $this->place,
            'limit'       => $this->limit ? $this->limit : "",
            'num_guest_brought_limit' => $this->num_guest_brought_limit,
            'status'      => $this->status
        ];

        // $data['is_public'] = (!$user OR !$user->isApproved())? false : true;
        $data['is_public'] = ($this->is_public == 1 ? true : false);
        
        $data['is_past'] = ($this->event_time < date('Y-m-d'))? true : false;

        $data['btn_book_label'] = 'Book Now!';
        $data['btn_cancel_label'] = 'Cancel';

        $data['galleries'] = [];

        if ($this->galleries) {
            foreach ($this->galleries as $key => $gallery) {
                $data['galleries'][] = [
                    'id'  => $gallery->gallery_id,
                    'url' => $gallery->filelink(),
                ];
            }
        }               
        return $data;
    }

    public function isCarkeeEvent()
    {
        return $this->account_id == 0;
    }
    public function isPaid(){
        return $this->is_paid == 1 ? "YES" : "NO";
    }

    public function attendee($user)
    {
        return EventAttendee::find()->where([ 'event_id' => $this->event_id ])->andWhere(['user_id'  => $user->user_id])->one();
    }

    public function attend($user,$num_guest_brought = 0, $paid = 0, $image_filename = NULL)
    {
        $response = "";

        $attendee = $this->attendee($user);
        
        if($attendee){
            if(!in_array($attendee->status,[EventAttendee::STATUS_CONFIRMED,EventAttendee::STATUS_CANCELLED,EventAttendee::STATUS_DELETED])){
                $attendee->status = EventAttendee::STATUS_PENDING;
                if(!empty($image_filename)) $attendee->filename = $image_filename;
                $attendee->save();

                $response = $this->notifyEventConfirmAttendees($user,$num_guest_brought,$paid);

                return [ 'status' => true, 'attendee_status' => $attendee->status, 'response' => $response];
            }else{
                if($attendee->status == EventAttendee::STATUS_CONFIRMED) $response = "You are already confirmed by the Admin!";
                else if($attendee->status == EventAttendee::STATUS_CANCELLED) $response = "Seems You have cancelled to attend this event.!";
                else if($attendee->status == EventAttendee::STATUS_DELETED) $response = "Seems You were join status is deleted! Please contact admin for further details on how to restore status";
                return [ 'status' => false, 'attendee_status' => $attendee->status, 'response' => $response];
            }
        }

        $attendee           = new EventAttendee;
        $attendee->event_id = $this->event_id;
        $attendee->user_id  = $user->user_id;
        $attendee->num_guest_brought = $num_guest_brought;
        $attendee->paid     = $paid;
        if(!empty($image_filename)) $attendee->filename = $image_filename;
        $attendee->save();

        $response = $this->notifyEventConfirmAttendees($user,$num_guest_brought,$paid);

        return [ 'status' => true, 'attendee_status' => $attendee->status, 'response' => $response];
    }

    public function cancelAttendee($user)
    {
        $response = "";

        $attendee = $this->attendee($user);
        
        if($attendee AND $attendee->isPending()){
            $attendee->status = EventAttendee::STATUS_CANCELLED;
            $attendee->save();

            $response = $this->notifyEventCancelledAttendees($user);

            return [ 'status' => true, 'response' => $response];
        }

        return [ 'status' => false, 'response' => $response];
    }

    private function notifyEventConfirmAttendees($user, $num_guest_brought = 0, $paid = 0){
        // Send notification to Admin/Event Director for every confirmed attendee
        $ltitle = "";
        $ldesc = "";
        $lresponse = "";
        $title ="One (1) Confirmed to Attend. Event Id: ".$this->event_id." ".(!empty($this->title) ? " ,Title: ".$this->title : "");
        $desc =($user->fullname ? $user->fullname : $user->firstname)." confirmed to attend. That would bring our head count to ".count($this->attendees)." attendee(s) out of ".$this->limit." expected attendee(s). ". ($num_guest_brought > 0 ? ", But take note, Attendee is bringing ".$num_guest_brought." guest(s) with him " : "") .
        " Confirmed Attendee(s) is ".$this->confirmed_attendees()." and Cancelled Attendee(s) is ".$this->cancelled_attendees().". ".($this->pending_attendees() > 0 ? "Pending Attendee(s) Confirmation from Event Director is ".$this->pending_attendees() : "");
        $response = Helper::pushNotificationFCM_Events($title,$desc,($user->account_id > 0 ? $user->account->company : "Karkee"),$user->account_id);
        // =========================

        // Checked event if it's to be closed due to slots are full already.
        if(count($this->attendees) >= $this->limit AND $this->is_closed == self::EVENT_NOT_CLOSED){
            $ltitle = "Slots are full already for Event Id: ".$this->event_id;
            $ldesc = "Hello Admin/Event Director, It seems that no more available slots for Event Id: ".$this->event_id." ".(!empty($this->title) ? " ,Title: ".$this->title : "").". Head Count: ".count($this->attendees)." attendee(s) out of ".$this->limit." expected attendee(s). ".
            " Confirmed Attendee(s) is ".$this->confirmed_attendees()." and Cancelled Attendee(s) is ".$this->cancelled_attendees().". ".($this->pending_attendees() > 0 ? "Pending Attendee(s) Confirmation from Event Director is ".$this->pending_attendees() : "");
            $lresponse = Helper::pushNotificationFCM_Events($ltitle,$ldesc,($user->account_id > 0 ? $user->account->company : "Karkee"),$user->account_id);
            
            // Close upcoming event due to slot limits are full already.
            $this->is_closed = self::EVENT_IS_CLOSED;
            $this->save();
            // =========================
        }
        // =========================

        return [
            'title'             => $title,
            'desc'              => $desc,
            'title_limit'      => $ltitle,
            'desc_limit'       => $ldesc,
            'response'          => $response,
            'response_limit'   => $lresponse,
        ];

    }

    private function notifyEventCancelledAttendees($user){
        // Send notification to Admin/Event Director for every cancelled attendee
        $ctitle = "";
        $cdesc = "";
        $cresponse = "";
        $title ="One (1) had cancelled to Attend. Event id: ".$this->event_id." ".(!empty($this->title) ? " ,Title: ".$this->title : "");
        $desc =($user->fullname ? $user->fullname : $user->firstname).
                " had cancelled to attend the event. That would bring our head count to ".count($this->attendees)." out of ".$this->limit." expected attendee(s). ".
                " Confirmed Attendee(s) is ".$this->confirmed_attendees()." and Cancelled Attendee(s) is ".$this->cancelled_attendees().". ".($this->pending_attendees() > 0 ? "Pending Attendee(s) Confirmation from Event Director is ".$this->pending_attendees() : "");
        $response = Helper::pushNotificationFCM_Events($title,$desc,($user->account_id > 0 ? $user->account->company : "Karkee"),$user->account_id);
        // =========================

        // Checked event if where previously closed due to slots are full and it's reopened again because someone have cancelled.
        if($this->is_closed == Event::EVENT_IS_CLOSED){
            $ctitle = "One Slot were opened for Event Id: ".$this->event_id;
            $cdesc = "Hello Admin/Event Director, Informing you that a slot were opened. It seems that someone cancelled its attendance for Event Id: ".$this->event_id." ".(!empty($this->title) ? " ,Title: ".$this->title : "").". Head Count: ".count($this->attendees)." attendee(s) out of ".$this->limit." expected attendee(s). ".
            " Confirmed Attendee(s) is ".$this->confirmed_attendees()." and Cancelled Attendee(s) is ".$this->cancelled_attendees().". ".($this->pending_attendees() > 0 ? "Pending Attendee(s) Confirmation from Event Director is ".$this->pending_attendees() : "");
            $cresponse = Helper::pushNotificationFCM_Events($ctitle,$cdesc,($user->account_id > 0 ? $user->account->company : "Karkee"),$user->account_id);
       
            // Reopen upcoming event due to slot limits were increased
            $this->is_closed = Event::EVENT_NOT_CLOSED;
            $this->save();
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
    // public static function create(\common\forms\EventForm $form, $user_id)
    // {
    //     $event              = new self;
    //     $event->created_by  = $user_id;
    //     $event->title       = $form->title;
    //     $event->content     = $form->content;
    //     $event->account_id  = $form->account_id;
    //     $event->category_id = $form->category_id;
    //     $event->summary     = $form->summary;
    //     $event->order       = $form->order;
    //     $event->is_news     = $form->is_news;
    //     $event->is_guest    = $form->is_guest;
    //     $event->is_trending = $form->is_trending;
    //     $event->is_event    = $form->is_event;
    //     $event->is_happening= $form->is_happening;
    //     $event->is_public   = $form->is_public;
        
    //     $event->save();

    //     return $event;
    // }
}