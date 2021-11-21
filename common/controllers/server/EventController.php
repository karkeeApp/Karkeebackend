<?php
namespace common\controllers\server;

use Yii;
use yii\bootstrap\ActiveForm;
use yii\web\UploadedFile;
use yii\helpers\Url;    

use common\models\Media;
use common\models\Event;
use common\models\EventGallery;
use common\models\EventAttendee;

use common\forms\MediaForm;
use common\forms\EventForm;
use common\forms\EventGalleryForm;

use yii\imagine\Image;
use yii\helpers\FileHelper;
use common\helpers\Common;
use common\helpers\MyActiveDataProvider;
use common\helpers\Sort;
use common\lib\Helper;
use common\lib\PaginationLib;
use common\models\User;
use common\models\UserFcmToken;

class EventController extends Controller
{
    public function actionList()
    {
        $user    = Yii::$app->user->identity;
        $keyword = Yii::$app->request->post('keyword', NULL);

        $_GET['hashUrl'] = '#list/filter/';

        foreach(['status', 'keyword', 'sort', 'page'] as $key) {
            $_GET[$key] = Yii::$app->request->post($key);
        }

        if (empty($_GET['sort'])) $_GET['sort'] = '-id';

        $data['sort'] = new Sort([
            'attributes' => [
                'title' => [
                    'desc'    => ['title' => SORT_DESC],
                    'asc'     => ['title' => SORT_ASC],
                    'default' => SORT_DESC,
                    'label'   => 'Title',
                ],
                'id' => [
                    'desc'    => ['event_id' => SORT_DESC],
                    'asc'     => ['event_id' => SORT_ASC],
                    'default' => SORT_DESC,
                    'label'   => 'ID',
                ],
            ],
            'defaultOrder' => ['id' => SORT_DESC],
        ]);

        $data['sort']->route = 'event#list/filter';

        $qry = Event::find()->where(['account_id' => Common::isClub() ? $user->account_id : 0]);

        $qry->andWhere(['NOT IN', 'status', [Event::STATUS_DELETED]]);
        
        if (isset($keyword)) {
            $qry->andFilterWhere([
                'or', 
                ['LIKE', 'title', $keyword],
            ]);
        }

        $qry->orderBy($data['sort']->orders);

        $data['dataProvider'] = new MyActiveDataProvider([
            'query' => $qry,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        // $total = $qry->count();

        // $page = new PaginationLib( $total, $cpage, 10);

        // $page->url = '#list';
        // if ($filter) $page->url .= '/filter/' . urlencode($filter);

        // $data['events'] = $qry->limit($page->limit())->offset($page->offset())->all();
        // $data['page'] = $page;

        return [
            'success' => TRUE,
            'content' => $this->renderPartial('@common/views/event/_ajax_list.tpl', $data),
        ];
    }

    public function actionAttendees()
    {
        $user    = Yii::$app->user->identity;
        $id      = Yii::$app->request->post('id');
        $keyword = Yii::$app->request->post('keyword', NULL);

        $_GET['hashUrl'] = '#list/filter/';

        foreach(['status', 'keyword', 'sort', 'page'] as $key) {
            $_GET[$key] = Yii::$app->request->post($key);
        }

        if (empty($_GET['sort'])) $_GET['sort'] = '-id';

        $event = Event::findOne($id);

        if ($event AND $user->account_id != $event->account_id){
            return [
                'success' => FALSE,
                'error' => 'Event not found.'
            ];
        }

        $data['sort'] = new Sort([
            'attributes' => [
                'fullname' => [
                    'desc'    => ['user.fullname' => SORT_DESC],
                    'asc'     => ['user.fullname' => SORT_ASC],
                    'default' => SORT_DESC,
                    'label'   => 'Full Name',
                ],
                'id' => [
                    'desc'    => ['user.user_id' => SORT_DESC],
                    'asc'     => ['user.user_id' => SORT_ASC],
                    'default' => SORT_DESC,
                    'label'   => 'ID',
                ],
                'email' => [
                    'desc'    => ['user.email' => SORT_DESC],
                    'asc'     => ['user.email' => SORT_ASC],
                    'default' => SORT_DESC,
                    'label'   => 'Email',
                ],
                'status' => [
                    'desc'    => ['event_attendee.status' => SORT_DESC],
                    'asc'     => ['event_attendee.status' => SORT_ASC],
                    'default' => SORT_DESC,
                    'label'   => 'Status',
                ],
            ],
            'defaultOrder' => ['id' => SORT_DESC],
        ]);

        $data['sort']->route = 'event/' . $event->event_id . '#list/filter';

        $qry = EventAttendee::find()
        ->innerJoin('user', 'user.user_id = event_attendee.user_id')
        ->where(['account_id' => Common::isClub() ? $user->account_id : 0])
        ->andWhere(['event_id' => $event->event_id]);
        // ->andWhere(['NOT IN', 'event_attendee.status', [EventAttendee::STATUS_DELETED]]);
        
        if (isset($keyword)) {
            $qry->andFilterWhere([
                'or', 
                ['LIKE', 'user.fullname', $keyword],
                ['LIKE', 'user.vendor_name', $keyword],
                ['LIKE', 'user.email', $keyword],
                ['LIKE', 'user.mobile', $keyword],
            ]);
        }

        $qry->orderBy($data['sort']->orders);

        Yii::$app->session['attendeesQry'] = [
            'event' => $event,
            'qry'   => $qry,
        ];

        $data['dataProvider'] = new MyActiveDataProvider([
            'query' => $qry,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return [
            'success' => TRUE,
            'content' => $this->renderPartial('@common/views/event/_ajax_attendees_list.tpl', $data),
        ];
    }

    public function actionUpdate()
    {
        $token   = Yii::$app->request->post('token');
        $notifType = Yii::$app->request->post('notification_type', UserFcmToken::NOTIF_TYPE_EVENTS);
        $account = Yii::$app->user->identity;
        $form    = Common::form("common\\forms\\EventForm");
        $form->load(Yii::$app->request->post());
        
        $errors = [];

        if (!$form->validate()) {
            $errors['event-form'] = ActiveForm::validate($form);
        }

        if (!empty($errors)) {
            return [
                'success' => FALSE,
                'errorFields' => $errors,
            ];
        }

        /**
         * Save event
         */
        $event = Event::findOne($form->event_id);

        if ($event AND $account->account_id != $event->account_id){
            return [
                'success' => FALSE,
                'error' => 'Event not found.'
            ];
        }

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
            if (new \DateTime($formattedTimeNow) > new \DateTime($formattedTimeEvent)) {
                return [
                    'success' => FALSE,
                    'error' => 'Can not add event if current date lapse the event date ',
                    'message' => 'Can not add event if current date lapse the event date '
                ];
            }
        }else{
            $timeEventPrev = new \DateTime($event->event_time);
            $formattedTimeEventPrev = $timeEventPrev->format('Y/m/d H:i:s');
            if (new \DateTime($formattedTimeNow) > new \DateTime($formattedTimeEvent)
                OR 
                new \DateTime($formattedTimeNow) > new \DateTime($formattedTimeEventPrev)
            ) {
                return [
                    'success' => FALSE,
                    'error' => 'Can not edit event already done or lapse the event date ',
                    'message' => 'Can not edit event already done or lapse the event date '
                ];
            }
        }

        if (!empty($form->cut_off_at) AND new \DateTime($formattedTimeCutoff) > new \DateTime($formattedTimeEvent)) {
            return [
                'success' => FALSE,
                'error' => 'Cutt Off should be on or before event date ',
                'message' => 'Cutt Off should be on or before event date '
            ];
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {        
            $uploadedFiles = [];
            $dir = Yii::$app->params['dir_event'];

            $file = UploadedFile::getInstance($form, 'image');

            if ($file) {
                $filename = date('Ymd') . '_' . time() . '.' . $file->getExtension();
                
                if ($file->saveAs($dir . $filename)){
                    $event->image = $filename;        
                }
            } else{
                if($isNew){
                    $event->image = 'default.png';
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
            $event->summary    = $form->summary;
            $event->title      = $form->title;
            $event->content    = $form->content;
            $event->order      = $form->order;
            $event->is_public  = $form->is_public;
            $event->place      = $form->place;
            $event->event_time = $form->event_time;
            $event->cut_off_at = $form->cut_off_at;
            $event->limit      = $form->limit;
            $event->is_paid    = $form->is_paid;
            $event->event_fee  = $form->event_fee;
            $event->save();

            /**
             * Save gallery
             */
            $session = Yii::$app->session;
            $eventGallery = $session->get('newsGallery', []);

            if (isset($eventGallery[$token])){
                foreach($eventGallery[$token] as $gallery){
                    $gallery->event_id = $event->event_id;
                    $gallery->save();
                }

                unset($eventGallery[$token]);
                $session->set('newsGallery', $eventGallery);
            }

            $transaction->commit();
            if((Yii::$app->params['environment'] == 'production' AND $isNew) OR Yii::$app->params['environment'] == 'development'){
                $fcm_status = Helper::pushNotificationFCM_Club($notifType, $event->title, $event->summary, $event->account_id);
            }

            return [
                'success'       => TRUE,
                'message'       => 'Successfully saved.',
                'event_id'      => $event->event_id,
                'account_id'    => $event->account_id,
                'redirect'      => Url::home() . 'event/edit/' . $event->event_id,
                // 'response_fcm'    => $fcm_status,
                // 'response_limit'    => $lresponse
            ];
        } catch (\Exception $e) {
            $transaction->rollBack();

            return [
                'success' => FALSE,
                'error' => $e->getMessage(),
            ];
        } 
    }

    public function actionMediaupload()
    {
        $froala = Yii::$app->request->post('froala', 0);

        if ($froala){
            foreach($_FILES['file'] as $key => $val){
                $_FILES['MediaForm'][$key]['filename'] = $val;
            }

            unset($_FILES['file']);
        }

        $form = Common::form("common\\forms\\MediaForm");
        $form->load(Yii::$app->request->post());
        
        $errors = [];

        if (!$form->validate()) {
            $errors['media-form'] = ActiveForm::validate($form);
        }

        if (!empty($errors)) {
            return [
                'success' => FALSE,
                'errorFields' => $errors,
            ];
        } else {
            /**
             * Save media
             */

            $uploadFile = UploadedFile::getInstance($form, 'filename');

            if (!$uploadFile) {
                return [
                    'success' => FALSE,
                    'error' => 'Please attach a file.'
                ];
            }

            $newFilename = hash('crc32', $uploadFile->name) . '.' . $uploadFile->getExtension();
            
            $fileDestination = Yii::$app->params['dir_media'] . $newFilename;
            $thumbnail = Yii::$app->params['dir_media'] . 'thumbnail/' . $newFilename;

            if ($uploadFile->saveAs($fileDestination)) {

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
                    'success' => TRUE,
                    'message' => 'Successfully added.',
                ];
            } else {
                return [
                    'success' => FALSE,
                    'error' => 'Error uploading the file.'
                ];
            }
        }
    }

    public function actionGalleryDelete()
    {
        $user = Yii::$app->user->identity;
        $id = Yii::$app->request->post('id');

        $gallery = EventGallery::findOne($id);

        if (!$gallery OR $gallery->event->account_id != $user->account_id){
            return [
                'success' => FALSE,
                'error' => 'Gallery not found.'
            ];
        }

        $gallery->delete();

        return [
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

        $form = Common::form("common\\forms\\EventGalleryForm");
        $form->load(Yii::$app->request->post());
        
        $errors = [];

        if (!$form->validate()) {
            $errors['event-gallery-form'] = ActiveForm::validate($form);
        }

        if (!empty($errors)) {
            return [
                'success' => FALSE,
                'errorFields' => $errors,
            ];
        } 

        /**
         * Save media
         */
        $uploadFile = UploadedFile::getInstance($form, 'filename');

        if (!$uploadFile) {
            return [
                'success' => FALSE,
                'error' => 'Please attach a file.'
            ];
        }

        $dir = Yii::$app->params['dir_event'];
        $filename = hash('crc32', $uploadFile->name) . '.' . $uploadFile->getExtension();

        $session = Yii::$app->session;

        $eventGallery = $session->get('newsGallery', []);

        if ($uploadFile->saveAs($dir . $filename)) {
            if (!isset($eventGallery[$token])) $eventGallery[$token] = [];

            $gallery = EventGallery::add(new Event, $user, $filename, FALSE);

            $eventGallery[$token][] = $gallery;

            $session->set('newsGallery', $eventGallery);

            return [
                'link'    => $gallery->filelink(),
                'success' => TRUE,
                'message' => 'Successfully added.',
            ];
        } else {
            return [
                'success' => FALSE,
                'error' => 'Error uploading the file.'
            ];
        }
    }

    public function actionGalleryUpload($id)
    {
        $user = Yii::$app->user->identity;

        foreach($_FILES['file'] as $key => $val){
            $_FILES['EventGalleryForm'][$key]['filename'] = $val;
        }

        unset($_FILES['file']);

        $form = Common::form("common\\forms\\EventGalleryForm");
        $form->load(Yii::$app->request->post());
        
        $errors = [];

        if (!$form->validate()) {
            $errors['event-gallery-form'] = ActiveForm::validate($form);
        }

        if (!empty($errors)) {
            return [
                'success' => FALSE,
                'errorFields' => $errors,
            ];
        } 

        $event = Event::findOne($id);

        if (!$event OR $event->account_id != $user->account_id){
            return [
                'success' => FALSE,
                'error' => 'Event not found.'
            ];
        }

        /**
         * Save media
         */
        $uploadFile = UploadedFile::getInstance($form, 'filename');

        if (!$uploadFile) {
            return [
                'success' => FALSE,
                'error' => 'Please attach a file.'
            ];
        }

        $dir = Yii::$app->params['dir_event'];
        $filename = hash('crc32', $uploadFile->name) . '.' . $uploadFile->getExtension();
        
        if ($uploadFile->saveAs($dir . $filename)) {

            /**
             * Create thumbnail
             */

            $gallery = EventGallery::add($event, $user, $filename);
         
            return [
                'link'    => $gallery->filelink(),
                'success' => TRUE,
                'message' => 'Successfully added.',
            ];
        } else {
            return [
                'success' => FALSE,
                'error' => 'Error uploading the file.'
            ];
        }
        
    }

    public function actionMedialist()
    {
        $cpage   = Yii::$app->request->post('page', 1);
        $keyword = Yii::$app->request->post('keyword', NULL);
        $filter  = Yii::$app->request->post('filter', NULL);

        $qry = Media::find()->where('1=1');

        if (isset($keyword)) {
            $qry->andFilterWhere([
                'or', 
                ['LIKE', 'username', $keyword],
                ['LIKE', 'email', $keyword],
            ]);
        }

        $total = $qry->count();

        $page = new PaginationLib( $total, $cpage, 10);
        $page->url = '#list';

        $data['medias'] = $qry->limit($page->limit())->offset($page->offset())->all();
        $data['page'] = $page;

        return [
            'success' => TRUE,
            'content' => $this->renderPartial('@common/views/media/_ajax_list.tpl', $data),
        ];
    }

    public function actionDeleteMedia()
    {
        $id      = Yii::$app->request->post('id');

        $media = Media::findOne($id);

        if (!$media){
            return [
                'success' => FALSE,
                'error' => 'Media not found.'
            ];
        }

        $media->status = Media::STATUS_DELETED;
        $media->save();

        return [
            'success' => TRUE,
            'message' => 'Successfully deleted.',
        ];
    }
    public function actionDelete()
    {
        $account = Yii::$app->user->identity;
        $id      = Yii::$app->request->post('id');

        $event = Event::findOne($id);

        if (!$event OR $account->account_id != $event->account_id){
            return [
                'success' => FALSE,
                'error' => 'Event not found.'
            ];
        }

        $event->status = Event::STATUS_DELETED;
        $event->save();

        return [
            'success' => TRUE,
            'message' => 'Successfully deleted.',
        ];
    }
    public function actionConfirmAttendee()
    {
        $account = Yii::$app->user->identity;
        $attendee_id = Yii::$app->request->post('attendee_id');
        $event_id    = Yii::$app->request->post('event_id');

        $attendee = EventAttendee::findOne(['event_id' => $event_id, 'user_id' => $attendee_id]);

        if (!$attendee){
            return [
                'success' => FALSE,
                'error' => 'Attendee not found.'
            ];
        }

        $attendee->status = EventAttendee::STATUS_CONFIRMED;
        $attendee->save();

        return [
            'success' => TRUE,
            'message' => 'Successfully Confirmed.',
        ];
    }

    public function actionCancelAttendee()
    {
        $account = Yii::$app->user->identity;
        $attendee_id = Yii::$app->request->post('attendee_id');
        $event_id    = Yii::$app->request->post('event_id');
        $event = Event::findOne(['event_id' => $event_id]);
        $attendee = EventAttendee::findOne(['event_id' => $event_id, 'user_id' => $attendee_id]);

        if (!$attendee){
            return [
                'success' => FALSE,
                'error' => 'Attendee not found.'
            ];
        }

        if($event->cancelAttendee($attendee->user)){
            return [
                'success'        => self::CODE_SUCCESS,
                'message'     => 'You have cancelled to attend this event.',
                'is_attendee' => false,
            ];
        }
    }
}