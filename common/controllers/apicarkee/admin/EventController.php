<?php
namespace common\controllers\apicarkee\admin;

use Yii;
use yii\bootstrap\ActiveForm;
use yii\web\UploadedFile;
use yii\helpers\Url;    
use common\models\Email;

use common\models\Media;
use common\models\Event;
use common\models\EventGallery;
use common\models\EventAttendee;

use common\forms\MediaForm;
use common\forms\EventForm;
use common\forms\EventGalleryForm;

use common\helpers\Common;
use common\lib\Helper;
use yii\data\Pagination;
use common\models\User;
use common\models\UserFcmToken;

class EventController extends Controller
{
    private function eventSave($event_id = null)
    {
        $notifType = Yii::$app->request->post('notification_type', UserFcmToken::NOTIF_TYPE_NEWS);
        $account = Yii::$app->user->identity;

        $img_field = 'files';
        $tmp = [];
        if(!is_null($_FILES) AND count($_FILES) > 0){
            foreach($_FILES as $file) {
                $tmp['EventForm'] = [
                    'name'     => [$img_field => $file['name']],
                    'type'     => [$img_field => $file['type']],
                    'tmp_name' => [$img_field => $file['tmp_name']],
                    'error'    => [$img_field => $file['error']],
                    'size'     => [$img_field => $file['size']],
                ];
            }

            $_FILES = $tmp;
        }

        $form = new EventForm(['scenario' => 'admin-carkee-edit']);
        $form = $this->postLoad($form);
        $form->event_id = $event_id;
        
        $form->account_id = Common::isCpanel() ? 0 : $account->account_id;
        if (!is_null($form->event_date)) $form->event_time = $form->event_date;
        if (!is_null($_FILES) AND count($_FILES) > 0) $form->image = UploadedFile::getInstance($form, $img_field);
        if (!is_null($form->image) AND count($_FILES) > 0) $filename = hash('crc32', $form->image->name) . time() . '.' . $form->image->extension;
        
        if (!$form->validate()){
            $error = self::getFirstError(ActiveForm::validate($form));
            return Helper::errorMessage($error['message'],true);
        }
        
        $event = Event::findOne($form->event_id);

        $isNew = false;

        $timeNow = new \DateTime();
        $timeEvent = new \DateTime($form->event_time);
        $timeCutoff = new \DateTime($form->cut_off_at);
        $formattedTimeNow = $timeNow->format('Y/m/d H:i:s');
        $formattedTimeEvent = $timeEvent->format('Y/m/d H:i:s');
        $formattedTimeCutoff = $timeCutoff->format('Y/m/d H:i:s');

        if (!$event) {
            $event = new Event;
            $isNew = true;
            if (new \DateTime($formattedTimeNow) > new \DateTime($formattedTimeEvent)) return Helper::errorMessage('Can not add event if current date lapse the event date ',true);
        }else{
            $timeEventPrev = new \DateTime($event->event_time);
            $formattedTimeEventPrev = $timeEventPrev->format('Y/m/d H:i:s');
            if (new \DateTime($formattedTimeNow) > new \DateTime($formattedTimeEvent)
                OR 
                new \DateTime($formattedTimeNow) > new \DateTime($formattedTimeEventPrev)
            ) {
                return Helper::errorMessage('Can not edit event if already lapsed the event date ',true);
            }else if(count($event->attendees) > 0) return Helper::errorMessage('Can not edit event if someone has already joined ',true);            
        }

        if (!empty($form->cut_off_at) AND new \DateTime($formattedTimeCutoff) > new \DateTime($formattedTimeEvent)) {
            return Helper::errorMessage('Cutt Off should be on or before event date ',true);                            
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            
            $dir = Yii::$app->params['dir_event'];
            if (!is_null($form->image) AND count($_FILES) > 0){
                if ($form->image->saveAs($dir . $filename)){
                    $event->image = $filename;
                }
            }
            $lresponse = "";
            // Send notification if slot limit were increased
            if(!$isNew AND $event->limit < $form->limit AND $event->is_closed == Event::EVENT_IS_CLOSED){

                $title = "Slots were opened for Event Id: ".$event->event_id;
                $desc = "Hello Admin/Event Director, Informing you that slots were opened. It seems that slot limits were updated for Event Id: ".$event->event_id." ".(!empty($form->title) ? " ,Title: ".$form->title : "").". Head Count: ".count($event->attendees)." attendee(s) out of ".$event->limit." expected attendee(s). ".
                " Confirmed Attendee(s) is ".$event->confirmed_attendees()." and Cancelled Attendee(s) is ".$event->cancelled_attendees().". ".($this->pending_attendees() > 0 ? "Pending Attendee(s) Confirmation from Event Director is ".$event->pending_attendees() : "");
                $lresponse = Helper::pushNotificationFCM_Events($title,$desc,($account->account_id > 0 ? $account->account->company : "Karkee"),$account->account_id);
           
                $event->is_closed = Event::EVENT_NOT_CLOSED;
            }
            // =========================

            $event->account_id = Common::isCpanel() ? 0 : $account->account_id;
            $event->created_by = $account->id;
            $event->summary    = $form->summary;
            $event->title      = $form->title;
            $event->content    = $form->content;
            $event->order      = $form->order;
            $event->is_public  = $form->is_public;
            $event->place      = $form->place;
            $event->event_time = $form->event_time;
            $event->cut_off_at = $form->cut_off_at;
            if(!empty($form->limit)) $event->limit = $form->limit;
            $event->is_paid    = $form->is_paid;
            $event->event_fee  = $form->event_fee;
            $event->save();
            
            if($isNew){
                $fcm_status = Helper::pushNotificationFCM($notifType, $form->title, $form->summary);
            }
            
            $transaction->commit();

            return [
                'code'    => self::CODE_SUCCESS,
                'success'  => TRUE,
                'message'  => 'Successfully saved.',
                'data'     => $event->data($account)
            ];
        } catch (\Exception $e) {
            $transaction->rollBack();
            
            $error = $e->getMessage();
            return Helper::errorMessage($error,true);
        }
    }

    private function eventList(){
        $user = Yii::$app->user->identity;

        $page    = Yii::$app->request->get('page', 1);
        $keyword = Yii::$app->request->get('keyword',null);
        $status = Yii::$app->request->get('status',null);
        $account_id = Yii::$app->request->get('account_id',null);
        $is_closed = Yii::$app->request->get('is_closed',null);
        $is_public = Yii::$app->request->get('is_public',null);
        $is_paid = Yii::$app->request->get('is_paid',null);

        $page_size = Yii::$app->request->get('size',10);

        $qry = Event::find()->where("1=1");

        if (!is_null($status)) $qry->andWhere(['status'=>$status]);
        if (!is_null($account_id)) $qry->andWhere(['account_id'=>$account_id]);
        if (!is_null($is_closed)) $qry->andWhere(['is_closed'=>$is_closed]);
        if (!is_null($is_public)) $qry->andWhere(['is_public'=>$is_public]);
        if (!is_null($is_paid)) $qry->andWhere(['is_paid'=>$is_paid]);
        
        if (!is_null($keyword) AND !empty($keyword)){
            $qry->andFilterWhere([
                'or',
                ['LIKE', 'title', $keyword],
                ['LIKE', 'content', $keyword],
                ['LIKE', 'summary', $keyword]
            ]);
        }

        $totalCount = (int)$qry->count();
        $pages    = new Pagination(['totalCount' => $totalCount, 'defaultPageSize' => $page_size]);        
        $offset   = ($page - 1) * $pages->limit;
        
        $events = $qry->orderBy(['event_id' => SORT_DESC])->offset($offset)->limit($pages->limit)->all();
        
        $data = [];

        foreach($events as $event){
            $data[] = $event->data($user);
        }

        return [
            'data'          => $data,
            // 'current_page'  => $page,
            // 'page_count'    => ceil($pages->totalCount / $pages->pageSize),
            // 'current_page_size' => count($ads),
            'total' => $totalCount,
            'code'  => self::CODE_SUCCESS
        ];
    }

    public function actionIndex()
    {
        return $this->eventList();
    }    
    public function actionList()
    {
        return $this->eventList();
    }
    public function actionView($id=0)
    {
        $user = Yii::$app->user->identity;
        $event = Event::findOne($id);

        if (!$event) return Helper::errorMessage("No Event Found!",true);

        return [
            'success' => TRUE,
            'message' => 'Successfully Retrieved.',
            'data' => $event->data($user)
        ];
    }
    public function actionAttendees()
    {
        $user = Yii::$app->user->identity;

        $page    = Yii::$app->request->get('page', 1);
        $page_size = Yii::$app->request->get('size',10);

        $event_id = Yii::$app->request->get('event_id', NULL);
        $keyword = Yii::$app->request->get('keyword', NULL);
        $attendee_status = Yii::$app->request->get('status', NULL);

        $qry = EventAttendee::find()->where("1=1");


        if(!is_null($attendee_status)) $qry->andWhere(['status'=>$attendee_status]);
        if(!is_null($event_id)) $qry->andWhere(['event_id' => $event_id]);
        if(!is_null($keyword) AND !empty($keyword)){
            $qry->andWhere("user_id IN (SELECT user.user_id FROM user WHERE (user.fullname LIKE '%".$keyword."%' OR user.email LIKE '%".$keyword."%') AND user.status NOT IN (".User::STATUS_DELETED.",".User::STATUS_REJECTED."))")
                ->orWhere("event_id IN (SELECT event.event_id FROM event WHERE (event.title LIKE '%".$keyword."%' OR event.content LIKE '%".$keyword."%' OR event.summary LIKE '%".$keyword."%') AND event.event_id = ".$event_id.")");           
        }

        $totalCount = $qry->count();
        $pages    = new Pagination(['totalCount' => $totalCount, 'defaultPageSize' => $page_size]);        
        $offset   = ($page - 1) * $pages->limit;
        
        $attendees = $qry->orderBy(['id' => SORT_DESC])->offset($offset)->limit($pages->limit)->all();
                
        $data = [];

        foreach($attendees as $attendee){
            $data[] = $attendee->data();
        }

        return [
            'data'          => $data,
            // 'current_page'  => $page,
            // 'page_count'    => ceil($pages->totalCount / $pages->pageSize),
            // 'current_page_size' => count($ads),
            'total' => $totalCount,
            'code'          => self::CODE_SUCCESS
        ];
    }

    public function actionUpdate($id){
        return $this->eventSave($id);
    }

    public function actionCreate(){
        return $this->eventSave();
    }
    
    public function actionSetDefaultSettings($id){

        $account = Yii::$app->user->identity;
        $event = Event::findOne($id);

        if (!$event ) return Helper::errorMessage('Event not found.',true);

        $form = new EventForm(['scenario' => 'set-default-settings']);
        $form = $this->postLoad($form);
        $form->event_id = $id;

        $timeNow = new \DateTime();
        $timeEvent = new \DateTime($form->event_time);
        $timeCutoff = new \DateTime($form->cut_off_at);
        $formattedTimeNow = $timeNow->format('Y/m/d H:i:s');
        $formattedTimeEvent = $timeEvent->format('Y/m/d H:i:s');
        $formattedTimeCutoff = $timeCutoff->format('Y/m/d H:i:s');

        $timeEventPrev = new \DateTime($event->event_time);
        $formattedTimeEventPrev = $timeEventPrev->format('Y/m/d H:i:s');
        if (new \DateTime($formattedTimeNow) > new \DateTime($formattedTimeEvent)
            OR 
            new \DateTime($formattedTimeNow) > new \DateTime($formattedTimeEventPrev)
        ) {
            return Helper::errorMessage('Can not edit event already done or lapse the event date ',true);                
        }

        if (!empty($form->cut_off_at) AND new \DateTime($formattedTimeCutoff) > new \DateTime($formattedTimeEvent)) {
            return Helper::errorMessage('Cutt Off should be on or before event date ',true);                            
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            
            // $dir = Yii::$app->params['dir_event'];

            // if ($form->image->saveAs($dir . $filename)){
            //     $event->image = $filename;
            // }

            $lresponse = "";
            // Send notification if slot limit were increased
            if($event->limit < $form->limit AND $event->is_closed == Event::EVENT_IS_CLOSED){

                $title = "Slots were opened for Event Id: ".$event->event_id;
                $desc = "Hello Admin/Event Director, Informing you that slots were opened. It seems that slot limits were updated for Event Id: ".$event->event_id." ".(!empty($form->title) ? " ,Title: ".$form->title : "").". Head Count: ".count($event->attendees)." attendee(s) out of ".$event->limit." expected attendee(s). ".
                " Confirmed Attendee(s) is ".$event->confirmed_attendees()." and Cancelled Attendee(s) is ".$event->cancelled_attendees().". ".($this->pending_attendees() > 0 ? "Pending Attendee(s) Confirmation from Event Director is ".$event->pending_attendees() : "");
                $lresponse = Helper::pushNotificationFCM_Events($title,$desc,($account->account_id > 0 ? $account->account->company : "Karkee"),$account->account_id);
           
                $event->is_closed = Event::EVENT_NOT_CLOSED;
            }
            // =========================
            $event->order      = $form->order;
            $event->is_public  = $form->is_public;
            $event->place      = $form->place;
            $event->event_time = $form->event_time;
            $event->cut_off_at = $form->cut_off_at;
            $event->limit      = $form->limit;
            $event->is_paid    = $form->is_paid;
            $event->event_fee  = $form->event_fee;
            $event->save();
                
            $transaction->commit();

            return [
                'code'    => self::CODE_SUCCESS,
                'success'  => TRUE,
                'message'  => 'Successfully saved.',
                'data'     => $event->data($account)
            ];
        } catch (\Exception $e) {
            $transaction->rollBack();
            
            $error = $e->getMessage();
            return Helper::errorMessage($error,true);
        }
    }

    // public function actionUpdate()
    // {
    //     $token   = Yii::$app->request->post('token');
    //     $notifType = Yii::$app->request->post('notification_type', UserFcmToken::NOTIF_TYPE_EVENTS);
    //     $account = Yii::$app->user->identity;
    //     $form    = new EventForm(['scenario'=>'admin-carkee-edit']);
    //     $form = $this->postLoad($form);
        
    //     $error = (!empty(self::getFirstError(ActiveForm::validate($form))) AND count(self::getFirstError(ActiveForm::validate($form))) > 0) ? self::getFirstError(ActiveForm::validate($form)) : ["message"=>"An Error Occurs"];
    //     if (!$form->validate()) return Helper::errorMessage($error['message'], null, null,101, 1);

    //     /**
    //      * Save event
    //      */
    //     $event = Event::findOne($form->event_id);

    //     $isNew = false;

    //     $timeNow = new \DateTime();
    //     $timeEvent = new \DateTime($form->event_time);
    //     $timeCutoff = new \DateTime($form->cut_off_at);
    //     $formattedTimeNow = $timeNow->format('Y/m/d H:i:s');
    //     $formattedTimeEvent = $timeEvent->format('Y/m/d H:i:s');
    //     $formattedTimeCutoff = $timeCutoff->format('Y/m/d H:i:s');

    //     if (!$event) {
    //         $event = new Event;
    //         $isNew = true;
    //         if (new \DateTime($formattedTimeNow) > new \DateTime($formattedTimeEvent)) return Helper::errorMessage('Can not add event if current date lapse the event date ');
    //     }else{
    //         $timeEventPrev = new \DateTime($event->event_time);
    //         $formattedTimeEventPrev = $timeEventPrev->format('Y/m/d H:i:s');
    //         if (new \DateTime($formattedTimeNow) > new \DateTime($formattedTimeEvent)
    //             OR 
    //             new \DateTime($formattedTimeNow) > new \DateTime($formattedTimeEventPrev)
    //         ) {
    //             return Helper::errorMessage('Can not edit event already done or lapse the event date ');                
    //         }
    //     }

    //     if (!empty($form->cut_off_at) AND new \DateTime($formattedTimeCutoff) > new \DateTime($formattedTimeEvent)) {
    //         return Helper::errorMessage('Cutt Off should be on or before event date ');                            
    //     }

    //     $transaction = Yii::$app->db->beginTransaction();

    //     try {        
    //         $uploadedFiles = [];
    //         $dir = Yii::$app->params['dir_event'];

    //         $file = UploadedFile::getInstance($form, 'image');

    //         if ($file) {
    //             $filename = date('Ymd') . '_' . time() . '.' . $file->getExtension();
                
    //             if ($file->saveAs($dir . $filename)){
    //                 $event->image = $filename;        
    //             }
    //         }
    //         $lresponse = "";
    //         // Send notification if slot limit were increased
    //         if(!$isNew AND $event->limit < $form->limit AND $event->is_closed == Event::EVENT_IS_CLOSED){

    //             $title = "Slots were opened for Event Id: ".$event->event_id;
    //             $desc = "Hello Admin/Event Director, Informing you that slots were opened. It seems that slot limits were updated for Event Id: ".$event->event_id." ".(!empty($form->title) ? " ,Title: ".$form->title : "").". Head Count: ".count($event->attendees)." attendee(s) out of ".$event->limit." expected attendee(s). ".
    //             " Confirmed Attendee(s) is ".$event->confirmed_attendees()." and Cancelled Attendee(s) is ".$event->cancelled_attendees().". ".($this->pending_attendees() > 0 ? "Pending Attendee(s) Confirmation from Event Director is ".$event->pending_attendees() : "");
    //             $lresponse = Helper::pushNotificationFCM_Events($title,$desc,($account->account_id > 0 ? $account->account->company : "Karkee"),$account->account_id);
           
    //             $event->is_closed = Event::EVENT_NOT_CLOSED;
    //         }
    //         // =========================

    //         $event->account_id = Common::isCpanel() ? 0 : $account->account_id;
    //         $event->summary    = $form->summary;
    //         $event->title      = $form->title;
    //         $event->content    = $form->content;
    //         $event->order      = $form->order;
    //         $event->is_public  = $form->is_public;
    //         $event->place      = $form->place;
    //         $event->event_time = $form->event_time;
    //         $event->cut_off_at = $form->cut_off_at;
    //         $event->limit      = $form->limit;
    //         $event->save();

    //         /**
    //          * Save gallery
    //          */
    //         $session = Yii::$app->session;
    //         $eventGallery = $session->get('newsGallery', []);

    //         if (isset($eventGallery[$token])){
    //             foreach($eventGallery[$token] as $gallery){
    //                 $gallery->event_id = $event->event_id;
    //                 $gallery->save();
    //             }

    //             unset($eventGallery[$token]);
    //             $session->set('newsGallery', $eventGallery);
    //         }

    //         $transaction->commit();
    //         if($isNew){
    //             $fcm_status = Helper::pushNotificationFCM($notifType, $event->title, $event->summary);
    //         }
            
    //         return [
    //             'success'   => TRUE,
    //             'message'   => 'Successfully saved.',
    //             'event_id'  => $event->event_id,
    //             'account_id'=> $event->account_id,
    //             'redirect'  => Url::home() . 'event/edit/' . $event->event_id,
    //             // 'response_fcm'       => $fcm_status,
    //             // 'response_limit'       => $lresponse
    //         ];
    //     } catch (\Exception $e) {
    //         $transaction->rollBack();

    //         return Helper::errorMessage($e->getMessage());
    //     } 
    // }

    public function actionMediaUpload()
    {
        $froala = Yii::$app->request->post('froala', 0);

        if ($froala){
            foreach($_FILES['file'] as $key => $val){
                $_FILES['MediaForm'][$key]['filename'] = $val;
            }

            unset($_FILES['file']);
        }

        $form = new MediaForm(['scenario'=>'admin-carkee-add']);
        $form = $this->postLoad($form);

        if (!$form->validate()){
            $error = self::getFirstError(ActiveForm::validate($form));
            return Helper::errorMessage($error['message'], true);
        }

        
        /**
         * Save media
         */

        if (!is_null($form->files) AND count($_FILES) > 0) $uploadFile = UploadedFile::getInstance($form, 'filename');

        if (!$uploadFile) return Helper::errorMessage('Please attach a file.',true);

        $newFilename = hash('crc32', $uploadFile->name) . '.' . $uploadFile->getExtension();
        
        $fileDestination = Yii::$app->params['dir_media'] . $newFilename;
        $thumbnail = Yii::$app->params['dir_media'] . 'thumbnail/' . $newFilename;

        if (!is_null($form->files) AND count($_FILES) > 0 AND $uploadFile->saveAs($fileDestination)) {

            /**
             * Create thumbnail
             */
            $file = new Media;
            $file->filename = $newFilename;
            $file->title = $form->title;
            $file->created_by = Yii::$app->user->getId();
            $file->save();

            if ($froala){
                return [
                    'link' => $file->url(),
                ];
            }
            return [
                'code' => self::CODE_SUCCESS,
                'success' => TRUE,
                'message' => 'Successfully added.',
            ];
        } else {
            return Helper::errorMessage('Error uploading the file.',true);
        }
        
    }

    public function actionListImageGallery(){
        $user = Yii::$app->user->identity;

        $page    = Yii::$app->request->get('page', 1);

        $page_size = Yii::$app->request->get('size',10);

        $qry = EventGallery::find()
                            ->andWhere(['NOT IN', 'status', [EventGallery::STATUS_DELETED]]);

        $totalCount = (int)$qry->count();
        $pages    = new Pagination(['totalCount' => $totalCount, 'defaultPageSize' => $page_size]);        
        $offset   = ($page - 1) * $pages->limit;
        
        $newsgallery = $qry->orderBy(['gallery_id' => SORT_DESC])->offset($offset)->limit($pages->limit)->all();
        
        $data = [];

        foreach($newsgallery as $n){
            $data[] = $n->data();
        }

        return [
            'data'          => $data,
            // 'current_page'  => $page,
            // 'page_count'    => ceil($pages->totalCount / $pages->pageSize),
            // 'current_page_size' => count($ads),
            'total' => $totalCount
        ];
    }

    public function actionCreateGallery($id)
    {
        $user = Yii::$app->user->identity;
        $event = Event::findOne($id);

        if (!$event ) return Helper::errorMessage('Event not found.',true);

        $img_field = 'files';

        $files = [];
        if(!is_null($_FILES) AND count($_FILES) > 0) $files = $_FILES[$img_field];

        $temp = [];

        if (!is_null($_FILES) AND count($files) > 0) {         

            foreach($files['name'] as $key => $fname) {
                $temp['name'][$img_field][] = $files['name'][$key];
                $temp['type'][$img_field][] = $files['type'][$key];
                $temp['tmp_name'][$img_field][] = $files['tmp_name'][$key];
                $temp['error'][$img_field][] = $files['error'][$key];
                $temp['size'][$img_field][] = $files['size'][$key];                
            }
            $_FILES['EventGalleryForm'] = $temp;
        }
        
        $form = new EventGalleryForm(['scenario' => 'admin-carkee-gallery']);
        $form = $this->postLoad($form);

        $form->event_id = $id;
        if (!is_null($_FILES) AND count($_FILES) > 0) $form->image = UploadedFile::getInstancesByName($img_field);        
        
        if (!$form->validate()){
            $error = self::getFirstError(ActiveForm::validate($form));
            return Helper::errorMessage($error['message'], true);
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $dir = Yii::$app->params['dir_event'];
            if (!is_null($form->image) AND count($_FILES) > 0){
                foreach ($form->image as $key => $file) {
                    $filename_noext = date('Ymd') . '_' . time() . "_{$key}";
                    $form->filename = $filename_noext . '.' . $file->extension;

                    if ($file->saveAs($dir . $form->filename)) EventGallery::Create($form, $user);                   
                }
            }

            $transaction->commit();

            return [
                'code' => self::CODE_SUCCESS,
                'success' => TRUE,
                'message' => 'Successfully Created Event Gallery.',
                'data'    => $event->data($user)
            ];
           
        } catch (\Exception $e) {
            $transaction->rollBack();
            
            $error = $e->getMessage();
            return Helper::errorMessage($error,true);
        }
    }

    public function actionRemoveImageGallery($id)
    {
        $gallery = EventGallery::findOne($id);

        if (!$gallery ) return Helper::errorMessage('Events Gallery not found.',true);
        
        @unlink(Yii::$app->params['dir_event'].$gallery->filename);
            
        $gallery->delete();

        return [
            'success' => TRUE,
            'message' => 'Successfully Deleted Image from Gallery.',
        ];
    }
    public function actionViewImageGallery($id)
    {
        $gallery = EventGallery::findOne($id);

        if (!$gallery ) return Helper::errorMessage('Events Gallery not found.',true);

        return [
            'code' => self::CODE_SUCCESS,
            'success' => TRUE,
            'message' => 'Successfully Retrieved News Gallery.',
            'data'    => $gallery->data()
        ];
    }

    public function actionReplaceImageGallery($id)
    {
        $gallery = EventGallery::findOne($id);

        if (!$gallery ) return Helper::errorMessage('News Gallery not found.',true);
        
        $img_field = 'files';
        $tmp = [];
        if (!is_null($_FILES) AND count($_FILES) > 0){
            foreach($_FILES as $file) {
                $tmp['EventGalleryForm'] = [
                    'name'     => [$img_field => $file['name']],
                    'type'     => [$img_field => $file['type']],
                    'tmp_name' => [$img_field => $file['tmp_name']],
                    'error'    => [$img_field => $file['error']],
                    'size'     => [$img_field => $file['size']],
                ];
            }

            $_FILES = $tmp;
        }

        $form = new EventGalleryForm(['scenario' => 'admin-carkee-replace-img']);
        $form = $this->postLoad($form);
        
        if(!is_null($_FILES) AND count($_FILES) > 0) $form->files = UploadedFile::getInstance($form, $img_field);
        if(!is_null($form->files) AND count($_FILES) > 0) $form->filename = hash('crc32', $form->files->name) . time() . '.' . $form->files->extension;
        
        $form->gallery_id = $id;

        if (!$form->validate()){
            $error = self::getFirstError(ActiveForm::validate($form));
            return Helper::errorMessage($error['message'], true);
        }
        
        $transaction = Yii::$app->db->beginTransaction();

        try {

            if (!is_null($form->files) AND count($_FILES) > 0) $saved_img = Helper::saveImage($this, $form->files, $form->filename, Yii::$app->params['dir_event']);
            if (!empty($saved_img) AND !$saved_img['success']) return $saved_img; 
            
            @unlink(Yii::$app->params['dir_event'].$gallery->filename);

            $gallery->filename = $form->filename;
            $gallery->save();

            return [
                'code' => self::CODE_SUCCESS,
                'success' => TRUE,
                'message' => 'Successfully Replaced Image from Gallery.',
                'data'    => $gallery->data()
            ];
           
        } catch (\Exception $e) {
            $transaction->rollBack();
            
            $error = $e->getMessage();
            return Helper::errorMessage($error,true);
        }
    }
    
    public function actionGalleryDelete($id)
    {
        $user = Yii::$app->user->identity;

        $gallery = EventGallery::findOne($id);

        if (!$gallery) return Helper::errorMessage('Gallery not found.',true);

        @unlink(Yii::$app->params['dir_event'].$gallery->filename);
        $gallery->delete();

        return [
            'code' => self::CODE_SUCCESS,
            'success' => TRUE,
            'message' => 'Successfully deleted.',
        ];
    }

    public function actionGalleryUploadByToken()
    {
        $user = Yii::$app->user->identity;
        $token = Yii::$app->request->get('token');

        foreach($_FILES['file'] as $key => $val){
            $_FILES['EventGalleryForm'][$key]['filename'] = $val;
        }

        unset($_FILES['file']);

        $form = new EventGalleryForm(['scenario'=>'admin-carkee-add']);
        $form = $this->postLoad($form);
        
        if (!$form->validate()){
            $error = self::getFirstError(ActiveForm::validate($form));
            return Helper::errorMessage($error['message'], true);
        }

        /**
         * Save media
         */
        $uploadFile = UploadedFile::getInstance($form, 'filename');

        if (!$uploadFile) return Helper::errorMessage('Please attach a file.',true);

        $dir = Yii::$app->params['dir_event'];
        $filename = hash('crc32', $uploadFile->name) . '.' . $uploadFile->getExtension();

        $session = Yii::$app->session;

        $eventGallery = $session->get('newsGallery', []);

        if (!is_null($uploadFile) AND count($_FILES) > 0 AND $uploadFile->saveAs($dir . $filename)) {
            if (!isset($eventGallery[$token])) $eventGallery[$token] = [];

            $gallery = EventGallery::add(new Event, $user, $filename, FALSE);

            $eventGallery[$token][] = $gallery;

            $session->set('newsGallery', $eventGallery);

            return [
                'link'    => $gallery->filelink(),
                'success' => TRUE,
                'id'      => $gallery->gallery_id,
                'model'   => 'event',
                'message' => 'Successfully added.',
            ];
        } else {
            return Helper::errorMessage('Error uploading the file.',true);
        }
    }

    public function actionGalleryUpload($id)
    {

        $user = Yii::$app->user->identity;
        $form = new EventGalleryForm(['scenario'=>'admin-carkee-add']);
        $form = $this->postLoad($form);
        
        if (!$form->validate()){
            $error = self::getFirstError(ActiveForm::validate($form));
            return Helper::errorMessage($error['message'], true);
        }


        $event = Event::findOne($id);

        if (!$event) return Helper::errorMessage('Event not found.',true);

        /**
         * Save media
         */
        $uploadFile = UploadedFile::getInstance($form, 'filename');

        if (!$uploadFile) return Helper::errorMessage('Please attach a file.',true);

        $dir = Yii::$app->params['dir_event'];
        $filename = hash('crc32', $uploadFile->name) . '.' . $uploadFile->getExtension();
        
        if (!is_null($uploadFile) AND count($_FILES) > 0 AND $uploadFile->saveAs($dir . $filename)) {

            /**
             * Create thumbnail
             */

            $gallery = EventGallery::add($event, $user, $filename);
         
            return [
                'link'    => $gallery->filelink(),
                'success' => TRUE,
                'id'      => $gallery->gallery_id,
                'model'   => 'event',
                'message' => 'Successfully added.',
            ];
        } else {
            return Helper::errorMessage('Error uploading the file.',true);
        }
        
    }

    public function actionMediaList()
    {
        $page   = Yii::$app->request->post('page', 1);
        $keyword = Yii::$app->request->get('keyword',NULL);

        $page_size = Yii::$app->request->get('size',10);

        $qry = Media::find()->where('1=1');
        
        if ($keyword) {
            $qry->andFilterWhere([
                'or', 
                ['LIKE', 'username', $keyword],
                ['LIKE', 'email', $keyword],
            ]);
        }

        $totalCount = (int)$qry->count();
        $pages    = new Pagination(['totalCount' => $totalCount, 'defaultPageSize' => $page_size]);        
        $offset   = ($page - 1) * $pages->limit;
        
        $medialists = $qry->orderBy(['id' => SORT_DESC])->offset($offset)->limit($pages->limit)->all();

        $data = [];

        foreach($medialists as $media){
            $data[] = $media->data();
        }

        return [
            'data'          => $data,
            // 'current_page'  => $page,
            // 'page_count'    => ceil($pages->totalCount / $pages->pageSize),
            // 'current_page_size' => count($ads),
            'total' => $totalCount
        ];
    }

    public function actionDeleteMedia()
    {
        $id      = Yii::$app->request->post('id');

        $media = Media::findOne($id);

        if (!$media) return Helper::errorMessage('Media not found.',true);

        $media->status = Media::STATUS_DELETED;
        $media->save();

        return [
            'code' => self::CODE_SUCCESS,
            'success' => TRUE,
            'message' => 'Successfully deleted.',
        ];
    }
    public function actionCancel($id)
    {
        $account = Yii::$app->user->identity;

        $event = Event::findOne($id);

        if (!$event) return Helper::errorMessage('Event not found.',true);
         
        $event->status = Event::STATUS_CANCELLED;
        $event->save();

        return [
            'code' => self::CODE_SUCCESS,
            'success' => TRUE,
            'message' => 'Successfully Cancelled.',
        ];
    }
    public function actionDelete($id)
    {
        $account = Yii::$app->user->identity;

        $event = Event::findOne($id);

        if (!$event) return Helper::errorMessage('Event not found.',true);
        else if(count($event->attendees) > 0) return Helper::errorMessage('Can not delete event if someone has already joined ',true);
        
        $event->status = Event::STATUS_DELETED;
        $event->save();

        return [
            'code' => self::CODE_SUCCESS,
            'success' => TRUE,
            'message' => 'Successfully deleted.',
        ];
    }
    public function actionHardDelete($id)
    {
        $account = Yii::$app->user->identity;

        $event = Event::findOne($id);

        if (!$event) return Helper::errorMessage('Event not found.',true);
        else if(count($event->attendees) > 0) return Helper::errorMessage('Can not delete event if someone has already joined ',true);
        
        $eventsgallery = EventGallery::find()->where(['event_id'=>$id])->all();
        foreach($eventsgallery as $gallery){

            @unlink(Yii::$app->params['dir_event'].$gallery->filename);
            
            $gallery->delete();
        }

        $event->delete();

        return [
            'code' => self::CODE_SUCCESS,
            'success' => TRUE,
            'message' => 'Successfully deleted.',
        ];
    }
    public function actionConfirmAttendee()
    {
        $attendee_id = Yii::$app->request->post('attendee_id');

        $attendee = EventAttendee::findOne($attendee_id);
        $user = User::findOne($attendee->user_id);

        if (!$attendee) return Helper::errorMessage('Attendee not found.',true);

        $attendee->status = EventAttendee::STATUS_CONFIRMED;
        $attendee->save();

        $title = "Approved Event Application Request";
        $desc = "Your request to join ".$attendee->event->title." event has been approved.";
        Helper::pushNotificationFCM_ToApproveOrCancel($title, $desc,  $user);


        Email::sendSendGridAPI($user->email, 'KARKEE - Event Notification', 'approve-reject', $params=[
            'name'          => !empty($user->fullname) ? $user->fullname : $user->firstname,
            'event'         => $attendee->event->title,
            'status'        => "Confirmed",
            'client_email'  => $user->email,
            'club_email'    => "admin@carkee.sg",
            'club_name'     => "KARKEE",
            'club_link'     => "http://cpanel.carkee.sg",
            'club_logo'     => "http://qa.carkeeapi.carkee.sg/logo-edited.png",
            'api_link'      => (Yii::$app->params['environment'] == 'production' ? Yii::$app->params['api.carkee.endpoint']['prod'] : Yii::$app->params['api.carkee.endpoint']['dev'])
        ]);

        return [
            'user' =>  $attendee->event->title,
            'code'      => self::CODE_SUCCESS,
            'message'   => 'Successfully Confirmed.',
        ];
    }

    public function actionCancelAttendee()
    {
        
        $attendee_id = Yii::$app->request->post('attendee_id');

        $attendee = EventAttendee::findOne($attendee_id);
        $user = User::findOne($attendee->user_id);

        if (!$attendee) return Helper::errorMessage('Attendee not found.',true);

        if($attendee->cancelAttendee()){

            $title = "Cancelled Event Application Request";
            $desc = "Your request to join ".$attendee->event->title." event has been Cancelled.";
            Helper::pushNotificationFCM_ToApproveOrCancel($title, $desc,  $user);

            Email::sendSendGridAPI($user->email, 'KARKEE - Event Notification', 'approve-reject', $params=[
                'name'          => !empty($user->fullname) ? $user->fullname : $user->firstname,
                'event'         => $attendee->event->title,
                'status'        => "Cancelled",
                'client_email'  => $user->email,
                'club_email'    => "admin@carkee.sg",
                'club_name'     => "KARKEE",
                'club_link'     => "http://cpanel.carkee.sg",
                'club_logo'     => "http://qa.carkeeapi.carkee.sg/logo-edited.png",
                'api_link'      => (Yii::$app->params['environment'] == 'production' ? Yii::$app->params['api.carkee.endpoint']['prod'] : Yii::$app->params['api.carkee.endpoint']['dev'])
            ]);

            return [
                'code'        => self::CODE_SUCCESS,
                'message'     => "Cancelled an attendee successfully",
                'is_attendee' => false,
            ];
        }else if($attendee->paid > 0) return Helper::errorMessage('Can not cancel event attendee if already paid ',true);
    }
}