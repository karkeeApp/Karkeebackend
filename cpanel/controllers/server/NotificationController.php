<?php
namespace cpanel\controllers\server;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\widgets\ActiveForm;

use common\models\Notification;
use common\models\User;

use common\helpers\Common;
use common\lib\PaginationLib;

use common\forms\MFINotificationForm;

class NotificationController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => [
                            'list', 'view', 'send', 'addrecipient', 'removerecipient', 'searchstaff'
                        ],
                        'allow' => true,
                    ],
                ],
            ],
        ];
    }

    public function actionList()
    {
        $cpage = Yii::$app->request->post('page', 1);
        $keyword = Yii::$app->request->post('keyword', NULL);
        $filter = Yii::$app->request->post('filter', NULL);

        $qry = Notification::find()->where('1=1');

        if (isset($keyword)) {
            $qry->andFilterWhere([
                'or', 
                ['LIKE', 'title', $keyword],
            ]);
        }

        $total = $qry->count();

        $page = new PaginationLib( $total, $cpage, 10);
        $page->url = '#list';

        $data['notifications'] = $qry->limit($page->limit())->offset($page->offset())->all();
        $data['page'] = $page;

        return [
            'success' => TRUE,
            'content' => $this->renderPartial('/notification/_ajax_list.tpl', $data),
        ];
    }

    public function actionView()
    {
        $id = Yii::$app->request->post('id', 0);
        
        $data['notification'] = Notification::findOne($id);

        if (!$data['notification']) {
            return [
                'success' => FALSE,
                'error' => 'Notification not found.',
            ];
        } else {
            return [
                'success' => TRUE,
                'content' => $this->renderPartial('/notification/_ajax_view.tpl', $data),
            ];
        }
    }

    public function actionSend()
    {
        $action = Yii::$app->request->post('action');

        $form = Common::form("common\\forms\\MFINotificationForm");
        $form->load(Yii::$app->request->post());

        if ($form->recipient == '[]') $form->recipient = '';

        $errors = [];

        if (!$form->validate()) {
             $errors['notification-form'] = ActiveForm::validate($form);
        }

        if (!empty($errors)) {
            return [
                'success' => FALSE,
                'errorFields' => $errors,
            ];
        } else {
            $notification = Notification::create($form);

            $notification->send();

            return [
                'success' => TRUE,
                'message' => 'Successfully sent',
            ];
        } 
    }

    public function actionAddrecipient()
    {
        $current_recipient     = Yii::$app->request->post('current_recipient');
        $recipientList         = Yii::$app->request->post('recipientList');
        $recipientHRList       = Yii::$app->request->post('recipientHRList');
        $recipientHRFilterList = Yii::$app->request->post('recipientHRFilterList');
        $recipientStaffId      = json_decode(Yii::$app->request->post('recipientStaffId', '[]'));

        if (!$recipientList) {
            return [
                'success' => FALSE,
                'error' => 'Please select recipient.',
            ]; 
        } elseif($recipientList == Notification::RECIPIENT_SPECIFIC_HR AND !$recipientHRList) {
            return [
                'success' => FALSE,
                'error' => 'Please select HR.',
            ]; 
        } elseif ($recipientList == Notification::RECIPIENT_SPECIFIC_HR AND $recipientHRList AND !$recipientHRFilterList) {
            return [
                'success' => FALSE,
                'error' => 'Please select from HR or staff.',
            ]; 
        } elseif ($recipientList == Notification::RECIPIENT_SPECIFIC_HR AND $recipientHRList AND $recipientHRFilterList == Notification::RECIPIENT_HR_SPECIFIC_STAFF AND empty($recipientStaffId)) {
            return [
                'success' => FALSE,
                'error' => 'Please select staff.',
            ]; 
        }

        if (!isset($current_recipient)) {
            $recipients = [];
        } else {
            $recipients = json_decode($current_recipient, TRUE);
        }

        if ($recipientList == Notification::RECIPIENT_ALL_HR_STAFF) {
            $recipients[] = 'all hr';
            $recipients[] = 'all staff';
        } elseif($recipientList == Notification::RECIPIENT_ALL_HR) {
            $recipients[] = 'all hr';            
        } elseif($recipientList == Notification::RECIPIENT_ALL_STAFF) {
            $recipients[] = 'all staff';
        } else {
            if ($recipientHRFilterList == Notification::RECIPIENT_HR_ALL) {
                $recipients[] = "hr:{$recipientHRList} all hr";
                $recipients[] = "hr:{$recipientHRList} all staff";
            } elseif ($recipientHRFilterList == Notification::RECIPIENT_HR_ALL_HR) {
                $recipients[] = "hr:{$recipientHRList} all hr";
            } elseif ($recipientHRFilterList == Notification::RECIPIENT_HR_ALL_STAFF) {
                $recipients[] = "hr:{$recipientHRList} all staff";
            } elseif (!empty($recipientStaffId)) {
                foreach($recipientStaffId as $staffId) {
                    $recipients[] = "staff:{$staffId}";
                }
            }
        }

        $recipients = array_unique($recipients);

        return [
            'success'    => TRUE,
            'message'    => 'Successfully added',
            'recipients' => json_encode($recipients),
            'recipientHtml' => Notification::parseRecipients($recipients),
        ];
    }

    public function actionRemoverecipient()
    {
        $current_recipient = Yii::$app->request->post('current_recipient');
        $recipient         = Yii::$app->request->post('recipient');

        if (!isset($current_recipient)) {
            $recipients = [];
        } else {
            $recipients = json_decode($current_recipient, TRUE);
        }

        if (in_array($recipient, $recipients)) {
            $key = array_search($recipient, $recipients);

            unset($recipients[$key]);

            $recipients = array_values($recipients);
        }

        return [
            'success'    => TRUE,
            'message'    => 'Successfully removed',
            'recipients' => json_encode($recipients),
            'recipientHtml' => Notification::parseRecipients($recipients),
        ];
    }


    public function actionSearchstaff()
    {
        $keyword = Yii::$app->request->post('staffKeyword');
        $account_id   = (int)Yii::$app->request->post('account_id', 0);        
    
        $data['users'] = User::find()
            ->where(['account_id' => $account_id])
            ->andFilterWhere([
                'or', 
                ['LIKE', 'username', $keyword],
                ['LIKE', 'firstname', $keyword],
                ['LIKE', 'lastname', $keyword],
                ['LIKE', 'email', $keyword],
            ])
            ->all();

        return [
            'success' => TRUE,
            'content' => $this->renderPartial('/notification/_ajax_search_user_list.tpl', $data),
        ];
    } 
}