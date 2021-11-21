<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\helpers\Common;

class Notification extends ActiveRecord
{
    const RECIPIENT_ALL_HR_STAFF = 1;
    const RECIPIENT_ALL_HR = 2;
    const RECIPIENT_ALL_STAFF = 3;
    const RECIPIENT_SPECIFIC_HR = 4;

    const RECIPIENT_HR_ALL = 1;
    const RECIPIENT_HR_ALL_HR = 2;
    const RECIPIENT_HR_ALL_STAFF = 3;
    const RECIPIENT_HR_SPECIFIC_STAFF = 4;

    public static function tableName()
    {
        return '{{%notification}}';
    }
    
    public function insert($runValidation = true, $attributes = NULL)
    {
        $this->created_at = date('Y-m-d H:i:s');
        $this->admin_id = Yii::$app->user->getId();
        return parent::insert($runValidation, $attributes);
    }

    public static function create(\common\forms\MFINotificationForm $form)
    {
        $notification = new self;

        $attributes = $form->attributes;


        foreach($attributes as $field => $val) {
            if ($field != 'notification_id') {
                $notification->{$field} = $val;
            }
        }

        $notification->save();

        return $notification;
    }

    public function date()
    {
        return Common::date($this->created_at);
    }

    public function recipient()
    {
        return self::parseRecipients(json_decode($this->recipient, TRUE), FALSE);
    }

    public function send()
    {
        if ($this->sent) return;

        $recipients = json_decode($this->recipient, TRUE);

        $targetRecipient = [
            'hr' => '',
            'staff' => '',
        ];

        foreach($recipients as $recipient) {
            if ($recipient == 'all hr') {
                $accounts = Account::find()->where(['status' => Account::STATUS_ACTIVE])->all();

                foreach($accounts as $account) {
                    $targetRecipient['hr'][] = $account->account_id;
                }
            } elseif ($recipient == 'all staff') {
                $users = User::find()->where(['<>', 'status', User::STATUS_DELETED])->all();

                foreach($users as $user) {
                    $targetRecipient['staff'][] = $user->user_id;
                }
            } elseif(preg_match("/hr:(\d+) all hr/", $recipient)) {
                preg_match("/hr:(\d+) all hr/", $recipient, $res);

                $targetRecipient['hr'][] = $res[1];
            } elseif(preg_match("/hr:(\d+) all staff/", $recipient)) {
                preg_match("/hr:(\d+) all staff/", $recipient, $res);

                $users = User::find()
                    ->where(['<>', 'status', User::STATUS_DELETED])
                    ->andWhere(['account_id' => $res[1]])
                    ->all();

                foreach($users as $user) {
                    $targetRecipient['staff'][] = $user->user_id;
                }
            } elseif(preg_match("/staff:(\d+)/", $recipient)) {
                preg_match("/staff:(\d+)/", $recipient, $res);

                $targetRecipient['staff'][] = $res[1];
            }
        }

        if (!empty($targetRecipient['hr'])) $targetRecipient['hr'] = array_unique($targetRecipient['hr']);
        if (!empty($targetRecipient['staff'])) $targetRecipient['staff'] = array_unique($targetRecipient['staff']);

        foreach($targetRecipient as $key => $ids) {
            if (!empty($ids)) {
                foreach($ids as $id) {
                    if ($key == 'hr') {
                        HRNotification::create($this, $id);
                    } else {
                        UserNotification::create($this, $id);
                    }
                }
            }
        }

        $this->sent = 1;
        $this->save();
    }

    public static function recipients()
    {
        return [
            self::RECIPIENT_ALL_HR_STAFF => 'All Companies and Staffs',
            self::RECIPIENT_ALL_HR => 'All Companies',
            self::RECIPIENT_ALL_STAFF => 'All Staffs',
            self::RECIPIENT_SPECIFIC_HR => 'Specific Company...',
        ];
    }

    public static function recipientHR()
    {
        $accounts = Account::find()
            ->where(['status' => Account::STATUS_ACTIVE])
            ->all();

        $result = [];

        if ($accounts) {
            foreach($accounts as $account) {
                $result[$account->account_id] = $account->company();
            }
        }

        return $result;
    }

    public static function recipientHRFilter()
    {
        return [
            self::RECIPIENT_HR_ALL => 'All HRs AND StaffS',
            self::RECIPIENT_HR_ALL_HR => 'All HRs',
            self::RECIPIENT_HR_ALL_STAFF => 'All Staffs',
            self::RECIPIENT_HR_SPECIFIC_STAFF => 'Specific Staffs'
        ];
    }

    public static function parseRecipients($recipients = [], $isHtml = TRUE)
    {
        $list = '';

        if (!empty($recipients)) {
            foreach($recipients as $key => $recipient) {

                if ($key) $list .= "; ";

                if(preg_match("/hr:(\d+) all hr/", $recipient)) {
                    preg_match("/hr:(\d+) all hr/", $recipient, $res);

                    $account_id = $res[1];

                    $account = Account::findOne($account_id);

                    $list .= strtoupper(($account)? 'all ' . $account->company . ' hr' : $recipient);

                } elseif(preg_match("/hr:(\d+) all staff/", $recipient)) {
                    preg_match("/hr:(\d+) all staff/", $recipient, $res);

                    $account_id = $res[1];

                    $account = Account::findOne($account_id);

                    $list .= strtoupper(($account)? 'all ' . $account->company . ' staffs' : $recipient);
                } elseif(preg_match("/staff:(\d+)/", $recipient)) {
                    preg_match("/staff:(\d+)/", $recipient, $res);

                    $user_id = $res[1];

                    $user = User::findOne($user_id);

                    $list .= strtoupper(($user)? 'staff:' . $user->email : $recipient);
                } else {
                    $list .= strtoupper($recipient);
                }

                if ($isHtml) {
                    $list .= " <a href='javascript:void(0);' title='Remove' data-recipient='{$recipient}' class='removeRecipient'><i class='fa fa-trash'></i></a>";
                }
            }
        }

        return $list;
    }
}