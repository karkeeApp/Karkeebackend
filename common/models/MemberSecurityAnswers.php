<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\behaviors\TimestampBehavior;
use common\helpers\Common;
use yii\helpers\FileHelper;
use yii\helpers\Url;

class MemberSecurityAnswers extends ActiveRecord{    

    public static function tableName()
    {
        return '{{%member_security_answers}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function getQuestion()
    {
        return $this->hasOne(AccountSecurityQuestions::class,['id' => 'question_id']);
    }

    public function getMember_security_question()
    {
        return $this->hasMany(AccountSecurityQuestions::class, ['id' => 'question_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::class,['user_id' => 'user_id']);
    }

    public function getAccount()
    {
        return $this->hasOne(Account::class,['account_id' => 'account_id']);
    }

    public function simpleData(){
        $attrs = [];
        $attrs['question_id'] = $this->question_id;
        $attrs['question'] = ( $this->question ? $this->question->question : "");
        $attrs['answer'] = ($this->isImageMimeType() ? $this->imagelink() : $this->answer);
        $attrs['mime_type'] = ($this->isImageMimeType() ? $this->getMimeType() : null);
        $attrs['is_file_upload'] = ( $this->question ? $this->question->is_file_upload : null);
        return $attrs;
    }

    public function data(){
        $attrs = $this->attributes;
        unset($attrs['answer']);
        $attrs['question'] = $this->question;
        $attrs['mime_type'] = ($this->isImageMimeType() ? $this->getMimeType() : null);
        $attrs['answer'] = ($this->isImageMimeType() ? $this->imagelink() : $this->answer);
        $attrs['question'] = $this->member_security_question;
        return $attrs;
    }


    public function imagelink()
    {
        if (Common::isApi() OR Common::isCarkeeApi()) {
            return ($this->answer)? Url::home(TRUE) . 'member/file-security-answers?id=' . $this->id . "&t={$this->answer}" : '';
        } else if(Common::isAccount() OR Common::isCpanel()){
            return ($this->answer)? Url::home(TRUE) . 'member/file-security-answers?id=' . $this->id . "&t={$this->answer}" : '';
        }
        return ($this->answer)? Url::home(TRUE) . 'member/file-security-answers?id=' . $this->id . "?t={$this->answer}" : NULL;
    }


    public function isImageMimeType(){
        $file_absURL = Yii::$app->params['dir_sec_questions'].$this->answer;
        if(!file_exists($file_absURL)) return FALSE;
        $file_mime = FileHelper::getMimeType($file_absURL);
        if (preg_match("/image/", $file_mime)) return TRUE;

        return FALSE;
    }

    public function getMimeType(){
        $file_absURL = Yii::$app->params['dir_sec_questions'].$this->answer;
        if(!file_exists($file_absURL)) return FALSE;
        return FileHelper::getMimeType($file_absURL);
    }
}