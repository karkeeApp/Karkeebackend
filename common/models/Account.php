<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use common\forms\AccountForm;
use common\behaviors\TimestampBehavior;
use yii\helpers\Url;

class Account extends ActiveRecord
{
    const STATUS_PENDING  = 1;
    const STATUS_APPROVED = 2;
    const STATUS_DELETED  = 3;
    const STATUS_REJECTED = 4;

    public static function tableName()
    {
        return '{{%account}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    // public function rules()
    // {
    //     return [
    //         ['status', 'default', 'value' => self::STATUS_PENDING],
    //         ['status', 'in', 'range' => [self::STATUS_APPROVED, self::STATUS_REJECTED]],
    //     ];
    // }

    public function insert($runValidation = true, $attributes = NULL)
    {
        $this->hash_id = time();
        $this->status = self::STATUS_PENDING;
        
        return parent::insert($runValidation, $attributes);
    }

    public function getMasterSettings(){
        $settings = Settings::find()->where("1=1")->one();
        Yii::info($settings,"carkee");
        return $settings;
    }

    public function getSettings()
    {
        // $settings = HRSettings::find()->where(['account_id' => $this->account_id])->one();

        // if (!$settings) {
        //     $settings = new HRSettings;
        //     $settings->account_id = $this->account_id;
        //     $settings->save();
        // }
        $settings = null;
        return $settings;
    }

    public function getUsers()
    {
        return $this->hasMany(User::class,['account_id' => 'account_id'])->orderBy(['user_id'=>SORT_DESC]);
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['user_id' => 'user_id']);
    }

    public function getDocuments()
    {
        return $this->hasMany(Document::class,['account_id' => 'account_id']);
    }

    public function company()
    {
        return $this->company;
    }

    public static function statuses()
    {
        return [
            self::STATUS_PENDING  => 'Pending',
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_DELETED  => 'Deleted',
            self::STATUS_REJECTED => 'Rejected',
        ];
    }

    public function statusClass()
    {
        if ($this->isPending()) return 'text-primary';
        elseif ($this->isApproved()) return 'text-success';
        elseif ($this->isRejected()) return 'text-warning';
        elseif ($this->isDeleted()) return 'text-danger';
        else return NULL;
    }

    public function isPending()
    {
        return $this->status == self::STATUS_PENDING;
    }

    public function isApproved()
    {
        return $this->status == self::STATUS_APPROVED;
    }
    public function isConfirmed()
    {
        return $this->confirmed_by AND $this->confirmed_by > 0;
    }
    public function isDeleted()
    {
        return $this->status == self::STATUS_DELETED;
    }

    public function isRejected()
    {
        return $this->status == self::STATUS_REJECTED;
    }

    public function status()
    {
        $statuses = self::statuses();

        return (array_key_exists($this->status, $statuses)) ? $statuses[$this->status] : 'Unknow status';
    }

    public static function activeAccounts($isMobile = FALSE)
    {
        $accounts = self::find()->where(['status' => self::STATUS_APPROVED])->orderBy(['company' => SORT_ASC])->all();
        
        $result = [
            0 => 'Carkee',
        ];

        if ($accounts) {
            foreach($accounts as $account) {
                $result[$account->account_id] = $account->company; 
            }
        }

        if ($isMobile){
            $temp = [];

            foreach($result as $key => $val) {
                $temp[] = [
                    'id'    => $key,
                    'value' => strtoupper($val),
                ];
            }

            $result = $temp;
        }

        return $result;
    }

    public static function Create($form,$user_id){
        $account                    = new self;
        $account->company_full_name = $form->company_full_name;
        $account->company           = $form->company;
        $account->address           = $form->address;
        $account->contact_name      = $form->contact_name;
        $account->email             = $form->email;
        $account->logo              = $form->logo;
        $account->status            = $form->status;
        $account->user_id           = $user_id;

        $account->save();

        return $account;
    }

    public function data(){
        $attributes = $this->attributes;
        $attributes['logo_url'] = $this->logoUrl();
        $attributes['documents'] = $this->documents_per_club;
        // if(!empty($this->users))
        //     foreach($this->users as $user)
        //         if(!empty($user->documents))
        //             foreach($user->documents as $document) $attributes['documents'][] = $user->documents;
        
        return $attributes;
    }
    public function getDocuments_per_club(){
        $documents = $this->documents;
        
        $dataAcnt = [];
        if(!empty($documents) AND count($documents)>0){
            foreach($documents as $document){
                if(in_array($document->type,[Document::TYPE_TRANSFER_SCREENSHOT])) $dataAcnt[] = $document->data();
            }
        }

        return $dataAcnt;
    }
    public function logoUrl()
    {
        return Url::home(TRUE) . 'member/logo?account_id=' . $this->account_id . '&t=' . $this->logo;
    }

    public function getClub_link(){
        return "http://qa.club.carkee.sg/login/" . $this->prefix;
    }
    public function getLogo_link(){
        return $this->logo ? $this->logo : "https://qa.carkeeapi.carkee.sg/logo-edited.png&t=".$this->logo;
    }
}
