<?php
namespace common\models;

use common\lib\SendGridEmail;
use Yii;
use yii\db\ActiveRecord;

class Email extends ActiveRecord{

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public static function primaryKey()
    {
        return ['email_id'];
    }

    public static function tableName()
    {
        return '{{%email}}';
    }
    
    public function insert($runValidation = true, $attributes = NULL)
    {
        $this->created_at = date('Y-m-d H:i:s');
        return parent::insert($runValidation, $attributes);
    }

    public static function sendSendGridAPI($to, $subject, $template, $params=[], $attachmentPath = NULL, $isInquiry = false, $ignoreFormat = TRUE){
        SendGridEmail::sendEmail($to, $subject,$template, $params);
    }

    public static function sendEmailNotification($to, $subject, $template, $admin, $subAdmin, $superAdmin, $params=[], $attachmentPath = NULL, $isInquiry = false, $ignoreFormat = TRUE ) {
        SendGridEmail::sendNotification( $to, $subject, $template, $admin, $subAdmin, $superAdmin, $params);
    }

    public static function send($to, $subject, $template, $params=[], $attachmentPath = NULL, $isInquiry = false, $ignoreFormat = TRUE)
    {
        if (!$to OR !$subject OR !$template) {
            return FALSE;
        }
        if($isInquiry){
            $message = Yii::$app->mailer->compose($template, $params)
                    ->setFrom([$to,Yii::$app->params['admin.email']])
                    ->setTo([$to,Yii::$app->params['admin.email'],'franklin@unravelstudios.co','sheetalbdz@yopmail.com',$params['club_email']])
                    ->setSubject($subject);
        }else{
            $message = Yii::$app->mailer->compose($template, $params)
                ->setFrom(Yii::$app->params['admin.email'])
                ->setTo($to)
                ->setSubject($subject);
        }
        if ($attachmentPath) {
            $message->attach($attachmentPath);
        }

        $message->mailer->useFileTransport = TRUE;

        if (
            Yii::$app->params['environment'] == 'production'
            OR in_array($to, Yii::$app->params['test_emails'])
        ) {
            $message->mailer->useFileTransport = FALSE;
        }else if(!empty(Yii::$app->params['test_emails_format'])){
            foreach(Yii::$app->params['test_emails_format'] as $format) {
                if (preg_match("{$format}", $to)){
                    $message->mailer->useFileTransport = FALSE;
                    break;
                }
            }
        }else if($ignoreFormat){
            $message->mailer->useFileTransport = FALSE;
        }
        $message->send();

        return TRUE;
    }
}