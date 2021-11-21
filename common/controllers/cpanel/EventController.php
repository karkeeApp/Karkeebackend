<?php
namespace common\controllers\cpanel;

use Yii;
use yii\web\View;

use common\models\Media;
use common\models\Event;

use common\forms\MediaForm;
use common\forms\EventForm;
use common\helpers\Common;

use common\assets\CkeditorAsset;
use common\assets\DropzoneAsset;
use common\models\EventAttendee;

class EventController extends Controller
{	
    public function actionIndex()
    {
        
        $data['menu'] = $this->menu;

        return $this->render('@common/views/event/list.tpl', $data);
    }

    public function actionMediaLibrary()
    {
        $medias = Media::find()->where(['status'=>1])->orderBy(['media_id' => SORT_DESC])->all();

        $data = [];

        if ($medias){
            foreach($medias as $media){
                $data[] = [
                    'thumb'   => Yii::$app->params['frontend.baseUrl'] . "file/media/{$media->media_id}",
                    'url' => Yii::$app->params['frontend.baseUrl'] . "file/media/{$media->media_id}?size=large",
                    'name'  => $media->title,
                    'type'  => $media->extension(),
                    'id'    => $media->media_id,
                    'tag'   => 'media'
                ];  
            }
        }

        return $data;
    }
   
    public function actionAdd()
    {
        global $data;

        CkeditorAsset::register($this->view);
        DropzoneAsset::register($this->view);

        $data['menu'] = $this->menu;
        $data['event'] = null;

        $data['eventForm'] = new EventForm(['scenario' => 'account_add']);

        Yii::$app->view->on(View::EVENT_END_BODY, function () {
            global $data;

            $data['mediaForm'] = new MediaForm(['scenario' => 'account_add']);

            echo $this->renderPartial('@common/views/event/modals.tpl', $data);  
        });

        return $this->render('@common/views/event/form.tpl', $data);
    }

    public function actionEdit($id=0)
    {
        global $data;

        $user = Yii::$app->user->identity;
        $data['event'] = Event::findOne($id);

        if (!$data['event']){ //} OR $data['event']->account_id != $user->account_id) {
            throw new \yii\web\HttpException(404, 'Event not found.');
        }

        CkeditorAsset::register($this->view);
        DropzoneAsset::register($this->view);

        $data['menu'] = $this->menu;

        $data['eventForm'] = new EventForm(['scenario' => 'account_add']);
        $data['eventForm']->setAttributes($data['event']->attributes, FALSE);

        Yii::$app->view->on(View::EVENT_END_BODY, function () {
            global $data;

            $data['mediaForm'] = new MediaForm(['scenario' => 'account_add']);

            echo $this->renderPartial('@common/views/event/modals.tpl', $data);  
        });

        return $this->render('@common/views/event/form.tpl', $data);
    }

    public function actionView($id=0)
    {
        global $data;

        $user = Yii::$app->user->identity;
        $data['event'] = Event::findOne($id);

        if (!$data['event']){ // OR $data['event']->account_id != $user->account_id) {
            throw new \yii\web\HttpException(404, 'Event not found.');
        }

        $data['menu'] = $this->menu;

        return $this->render('@common/views/event/details.tpl', $data);
    }

    public function actionDownloadAttendees()
    {
        $attendeesQry = Yii::$app->session['attendeesQry'];

        $event = $attendeesQry['event'];

        $file = $event->title . '-Attendees@' . $event->place . ',' . $event->event_time . '-' . time();

        \moonland\phpexcel\Excel::export([
            'models' => $attendeesQry['qry']->all(),
            'setFirstTitle' => $event->title . ' Event Attendees @ ' . $event->place . ' ' . $event->event_time,
            'asAttachment'  => true,
            'autoSize'      => true,
            'columns' => [
                'user_id',
                [
                    'attribute' => 'event.user.fullname',
                    'header' => 'Attendee',
                    'format' => 'text',
                    'value' => function($model) {
                        return $model->user->fullname();
                    },
                ],
                [
                    'attribute' => 'event.user.email',
                    'header' => 'Email',
                    'format' => 'text',
                    'value' => function($model) {
                        return $model->user->email;
                    },
                ],
                [
                    'attribute' => 'status',
                    'header' => 'Status',
                    'format' => 'text',
                    'value' => function($model) {
                        return $model->status();
                    },
                ],
                [
                    'attribute' => 'created_at',
                    'header' => 'Join Date',
                    'format' => 'date'
                ],
            ],
        ]);
    }
}
