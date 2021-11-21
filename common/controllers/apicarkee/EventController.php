<?php
namespace common\controllers\apicarkee;

use common\forms\EventForm;
use common\forms\UserPaymentForm;
use Yii;
use yii\widgets\ActiveForm;
use yii\web\UploadedFile;

use common\models\Event;
use common\models\Account;
use common\models\Email;
use common\helpers\Common;
use common\helpers\Helper;
use common\lib\PaginationLib;
use common\helpers\MyActiveDataProvider;
use common\helpers\Sort;
use common\lib\CrudAction;
use common\lib\Helper as LibHelper;
use common\models\EventAttendee;
use common\models\User;
use common\models\UserPayment;

class EventController extends Controller
{


    private function adminEmails(){

        $data = User::adminEmails();
        return $data;
    }    
    private function subEmails(){

        $data = User::subAdminEmails();
        return $data;
    }    
    private function superEmails(){

        $data = User::superAdminEmails();
        return $data;
    }


    public function actionPast()
    {
        return $this->filter(['<', 'event_time', date('Y-m-d')]);
    }

    public function actionOngoing()
    {
        return $this->filter(['>=', 'event_time', date('Y-m-d')]);
    }

    private function filter($condition = NULL)
    {
        $page       = Yii::$app->request->get('page', 1);
        $keyword    = Yii::$app->request->get('keyword');
        $user       = Yii::$app->user->identity;

        $_GET['hashUrl'] = '#list/filter/';

        foreach(['status', 'sort', 'page'] as $key) {
            $_GET[$key] = Yii::$app->request->get($key);
        }

        if (empty($_GET['sort'])) $_GET['sort'] = '-id';

        $sort = new Sort([
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

        $sort->route = 'Event#list/filter';

        $qry = Event::find()->where(['status' => Event::STATUS_ACTIVE]);

        if (!empty($keyword)) {
            $qry->andWhere(['LIKE', 'title', $keyword]);
        }

        if ($condition) {
            $qry->andWhere($condition);
        }
        
        if (empty($user)) $qry->andWhere(['is_public'=>1]);

        $qry->orderBy($sort->orders);

        $dataProvider = new MyActiveDataProvider([
            'query' => $qry,
            'pagination' => [
                'pageSize' => 5,
            ],
        ]);

        /**
         * Parse data
         */
        $data = [];
        

        $models = $dataProvider->getModels();

        $pageCount = ceil($dataProvider->pagination->totalCount / $dataProvider->pagination->pageSize);
        
        if ($page <= $pageCount) {
            foreach($models as $event) {
                $data[] = $event->data($user);
            }
        }
        // Yii::info(count($data),"carkee");
        return [
            'success'     => TRUE,
            'data'        => $data,
            'count'       => $dataProvider->getCount(),
            'currentPage' => (int)$page,
            'pageCount'   => ceil($dataProvider->pagination->totalCount / $dataProvider->pagination->pageSize),
            'code'        => self::CODE_SUCCESS,
        ];
    }

    public function actionView()
    {
        $user_id    = Yii::$app->request->get('id', -1);
    	$user       = !empty(Yii::$app->user->identity) ? Yii::$app->user->identity : User::findOne($user_id);
        $event_id    = Yii::$app->request->get('event_id');

        $data['event'] = Event::findOne($event_id);
        $data['user'] = $user;

        return $this->render('@common/views/event/view.tpl', $data);        
    }

    public function actionViewPrivate()
    {
        $user_id  = Yii::$app->request->get('id');
        $event_id = Yii::$app->request->get('event_id');
        $user = User::findOne($user_id);
        $data['event'] = Event::findOne($event_id);
        $data['user'] = $user;

        $tpl = (($data['event'] AND $data['event']->is_public) OR $user->isApproved())? 'view_full.tpl' : 'view.tpl';

        return $this->render('@common/views/event/' . $tpl, $data);
    }

    public function actionGallery()
    {
        $event_id    = Yii::$app->request->get('event_id');

        $event = Event::findOne($event_id);

        if (!$account OR !$event OR $event->account_id != 0) {
            echo "Invalid file";
            return;
        }

        try{
            $dir = Yii::$app->params['dir_event'];

            Yii::$app->response->sendFile($dir . $event->image, $event->image, ['inline' => TRUE]);
        } catch(\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function actionAttachment()
    {
        // $account_id = Yii::$app->request->get('account_id');
        // $event_id    = Yii::$app->request->get('event_id');

        // $account = Account::findOne([
        //     'hash_id' => $account_id
        // ]);

        // $event = Event::findOne($event_id);

        // if (!$account OR !$event OR $event->account_id != $account->account_id) {
        //     echo "Invalid file";
        //     return;
        // }

        $image = "";

        try{
            $dir = Yii::$app->params['dir_Event'];

            Yii::$app->response->sendFile($dir . $image, $image, ['inline' => TRUE]);
        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }
    
    public function actionJoin()    
    {        
        $user  = Yii::$app->user->identity;
        $event_id = Yii::$app->request->post('event_id');
        $num_guest_brought = Yii::$app->request->post('num_guest_brought',0);
        $paid = Yii::$app->request->post('paid',0);

        $event = Event::findOne($event_id);

        if (!$event OR $event->account_id != 0){
            return [
                'code'  => self::CODE_ERROR,   
                'error' => 'Event not found.'
            ];
        }
        
        $timeNow = new \DateTime();
        $timeEvent = new \DateTime($event->event_time);
        $timeCutoff = new \DateTime($event->cut_off_at);
        $formattedTimeNow = $timeNow->format('Y/m/d H:i:s');
        $formattedTimeEvent = $timeEvent->format('Y/m/d H:i:s');
        $formattedTimeCutoff = $timeCutoff->format('Y/m/d H:i:s');

        if (new \DateTime($formattedTimeNow) > new \DateTime($formattedTimeEvent)) {

            if($event->is_closed == Event::EVENT_NOT_CLOSED){
                $event->is_closed = Event::EVENT_IS_CLOSED;
                $event->save();
            }

            return [
                'success' => FALSE,
                'code'  => self::CODE_ERROR,
                'error' => 'Can not join event already done or lapse the event date',
                'message' => 'Can not join event already done or lapse the event date'
            ];
        }

        if (!empty($event->cut_off_at) AND new \DateTime($formattedTimeNow) > new \DateTime($formattedTimeCutoff)) {
            return [
                'success' => FALSE,
                'code'  => self::CODE_ERROR,
                'error' => 'Cutt Off already lapse. Contact us for clarification ',
                'message' => 'Cutt Off already lapse. Contact us for clarification'
            ];
        }

        $attendee = $event->attendee($user);

        // if( $event->is_closed == Event::EVENT_IS_CLOSED 
        if( $event->limit <= count($event->attendees)
            AND 
            ( !$attendee OR ( $attendee AND $attendee->status == EventAttendee::STATUS_CANCELLED ) )
        ){
            return [
                'code'  => self::CODE_ERROR,   
                'error' => 'So Sorry but it seems that event is now closed. Continue using your app for possible open slots. Thank You!',
                'message' => 'So Sorry but it seems that event is now closed. Continue using your app for possible open slots. Thank You!'
            ];
        }

        $join_response = $event->attend($user,$num_guest_brought,$paid);
        if($join_response['status']){


            Email::sendEmailNotification(Yii::$app->params['admin.email'], 'KARKEE Event', 'admin-notification', $this->adminEmails(), $this->subEmails(), $this->superEmails(), $params=[
                'name'       => !empty($user->fullname) ? $user->fullname : $user->firstname,
                'heading'      => 'The following user booked '. $event->title.' event',
                'email'         => $user->email,
                'client_email'  => $user->email,
                'club_email'    => "admin@carkee.sg",
                'club_name'     => "KARKEE",
                'club_link'     => "http://cpanel.carkee.sg",
                'club_logo'     => "http://qa.carkeeapi.carkee.sg/logo-edited.png",
                'api_link'      => (Yii::$app->params['environment'] == 'production' ? Yii::$app->params['api.carkee.endpoint']['prod'] : Yii::$app->params['api.carkee.endpoint']['dev'])
            ]);

            return [
                'code'        => self::CODE_SUCCESS,
                'message'     => 'Our team will get in touch with you soon!',
                'title'       => 'We appreciate your interest!',
                'is_attendee' => true,
                'response'    => $join_response
            ];
        }else if(!$join_response['status']){
            $cancel_response = $event->cancelAttendee($user);

            Email::sendEmailNotification(Yii::$app->params['admin.email'], 'KARKEE Event', 'admin-notification', $this->adminEmails(), $this->subEmails(), $this->superEmails(), $params=[
                'name'       => !empty($user->fullname) ? $user->fullname : $user->firstname,
                'heading'      => 'The following user has canceled attending '. $event->title.' event',
                'email'         => $user->email,
                'client_email'  => $user->email,
                'club_email'    => "admin@carkee.sg",
                'club_name'     => "KARKEE",
                'club_link'     => "http://cpanel.carkee.sg",
                'club_logo'     => "http://qa.carkeeapi.carkee.sg/logo-edited.png",
                'api_link'      => (Yii::$app->params['environment'] == 'production' ? Yii::$app->params['api.carkee.endpoint']['prod'] : Yii::$app->params['api.carkee.endpoint']['dev'])
            ]);

            return [
                'code'        => self::CODE_SUCCESS,
                'message'     => 'You have cancelled to attend this event.',
                'is_attendee' => false,
                'response'    => $cancel_response
            ];
        }
    }
    
    public function actionJoinWithImage()    
    {        
        $user  = Yii::$app->user->identity;
        $event_id = Yii::$app->request->post('event_id');
        $num_guest_brought = Yii::$app->request->post('num_guest_brought',0);
        $paid = Yii::$app->request->post('paid',0);
        $uploadFile = UploadedFile::getInstanceByName('file');  
        if(!$uploadFile){
            return [
                'code'  => self::CODE_ERROR,   
                'error' => 'Please upload proof of event fee'
            ];
        }

        $dir_payment = Yii::$app->params['dir_payment'];
        $image_filename = hash('crc32', $uploadFile->name) . time() . '.' . $uploadFile->extension;
        $fileDestination = $dir_payment.$image_filename;

        $event = Event::findOne($event_id);

        if (!$event OR $event->account_id != 0){
            return [
                'code'  => self::CODE_ERROR,   
                'error' => 'Event not found.'
            ];
        }
        
            $timeNow = new \DateTime();
            $timeEvent = new \DateTime($event->event_time);
            $timeCutoff = new \DateTime($event->cut_off_at);
            $formattedTimeNow = $timeNow->format('Y/m/d H:i:s');
            $formattedTimeEvent = $timeEvent->format('Y/m/d H:i:s');
            $formattedTimeCutoff = $timeCutoff->format('Y/m/d H:i:s');

            if (new \DateTime($formattedTimeNow) > new \DateTime($formattedTimeEvent)) {

                if($event->is_closed == Event::EVENT_NOT_CLOSED){
                    $event->is_closed = Event::EVENT_IS_CLOSED;
                    $event->save();
                }

                return [
                    'success' => FALSE,
                    'code'  => self::CODE_ERROR,
                    'error' => 'Can not join event already done or lapse the event date',
                    'message' => 'Can not join event already done or lapse the event date'
                ];
            }

            if (!empty($event->cut_off_at) AND new \DateTime($formattedTimeNow) > new \DateTime($formattedTimeCutoff)) {
                return [
                    'success' => FALSE,
                    'code'  => self::CODE_ERROR,
                    'error' => 'Cutt Off already lapse. Contact us for clarification ',
                    'message' => 'Cutt Off already lapse. Contact us for clarification'
                ];
            }

            $attendee = $event->attendee($user);

            // if( $event->is_closed == Event::EVENT_IS_CLOSED 
            if( $event->limit <= count($event->attendees)
                AND 
                ( !$attendee OR ( $attendee AND $attendee->status == EventAttendee::STATUS_CANCELLED ) )
            ){
                return [
                    'code'  => self::CODE_ERROR,   
                    'error' => 'So Sorry but it seems that event is now closed. Continue using your app for possible open slots. Thank You!',
                    'message' => 'So Sorry but it seems that event is now closed. Continue using your app for possible open slots. Thank You!'
                ];
            }
            
            $join_response = $event->attend($user,$num_guest_brought,$paid,$image_filename);
            if($join_response['status']){             
                
                $uploadFile = UploadedFile::getInstanceByName('file');  
                if (!empty($uploadFile)){

                    if($uploadFile->saveAs($fileDestination)){}
                    
                    $userpayment = UserPayment::find()->where([ 'event_id' => $event->event_id ])->andWhere(['user_id'  => $user->user_id])->one();
                    if(!$userpayment){
                        $userpayment = new UserPayment;
                        $userpayment->event_id = $event->event_id;
                        $userpayment->user_id = $user->user_id;
                        $userpayment->account_id = $user->account_id;
                    }
                    $userpayment->amount = 0;
                    $userpayment->description = $user->fullname . " event fee";
                    $userpayment->filename = $image_filename;
                    $userpayment->name = $user->fullname . " fee";
                    $userpayment->payment_for = UserPayment::PAYMENT_FOR_EVENT;
                    $userpayment->save();
                }
                // Email::sendEmailNotification(Yii::$app->params['admin.email'], 'KARKEE Event', 'admin-notification', $this->adminEmails(), $this->subEmails(), $this->superEmails(), $params=[
                //     'name'       => !empty($user->fullname) ? $user->fullname : $user->firstname,
                //     'heading'      => 'The following user booked '. $event->title.' event',
                //     'email'         => $user->email,
                //     'client_email'  => $user->email,
                //     'club_email'    => "admin@carkee.sg",
                //     'club_name'     => "KARKEE",
                //     'club_link'     => "http://cpanel.carkee.sg",
                //     'club_logo'     => "http://qa.carkeeapi.carkee.sg/logo-edited.png",
                //     'api_link'      => (Yii::$app->params['environment'] == 'production' ? Yii::$app->params['api.carkee.endpoint']['prod'] : Yii::$app->params['api.carkee.endpoint']['dev'])
                // ]);

                return [
                    'code'        => self::CODE_SUCCESS,
                    'message'     => 'Our team will get in touch with you soon!',
                    'title'       => 'We appreciate your interest!',
                    'is_attendee' => true,
                    'response'    => $join_response
                ];
            }else if(!$join_response['status']){
                $cancel_response = $event->cancelAttendee($user);

                Email::sendEmailNotification(Yii::$app->params['admin.email'], 'KARKEE Event', 'admin-notification', $this->adminEmails(), $this->subEmails(), $this->superEmails(), $params=[
                    'name'       => !empty($user->fullname) ? $user->fullname : $user->firstname,
                    'heading'      => 'The following user has canceled attending '. $event->title.' event',
                    'email'         => $user->email,
                    'client_email'  => $user->email,
                    'club_email'    => "admin@carkee.sg",
                    'club_name'     => "KARKEE",
                    'club_link'     => "http://cpanel.carkee.sg",
                    'club_logo'     => "http://qa.carkeeapi.carkee.sg/logo-edited.png",
                    'api_link'      => (Yii::$app->params['environment'] == 'production' ? Yii::$app->params['api.carkee.endpoint']['prod'] : Yii::$app->params['api.carkee.endpoint']['dev'])
                ]);
                return [
                    'code'        => self::CODE_SUCCESS,
                    'message'     => 'You have cancelled to attend this event.',
                    'is_attendee' => false,
                    'response'    => $cancel_response
                ];
            }
    }
}
