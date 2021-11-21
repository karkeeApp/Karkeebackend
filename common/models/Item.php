<?php
namespace common\models;

use Yii;
use yii\helpers\Url;
use yii\db\ActiveRecord;

class Item extends ActiveRecord
{
    const STATUS_PENDING  = 1;
    const STATUS_APPROVED = 2;
    const STATUS_REJECTED = 3;
    const STATUS_DELETED  = 4;

    public $vendor;

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public static function tableName()
    {
        return '{{%item}}';
    }
    
    public function behaviors()
    {
        return [
            \common\behaviors\TimestampBehavior::class,
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class,['user_id' => 'user_id']);
    }

    public function getAccount()
    {
        return $this->hasOne(Account::class,['account_id' => 'account_id']);
    }
    
    public function getRedeem()
    {
        return $this->hasMany(ItemRedeem::class,['item_id' => 'item_id']);
    }

    public function getGallery()
    {
        return $this->hasMany(ItemGallery::class,['item_id' => 'item_id'])->where(['status' => ItemGallery::STATUS_ACTIVE]);
    }

    public function getFirstGallery()
    {
        return $this->hasOne(ItemGallery::class,['item_id' => 'item_id'])->where(['status' => ItemGallery::STATUS_ACTIVE]);
    }

    public function getPrimaryPhoto()
    {
        return $this->hasOne(ItemGallery::class,['item_id' => 'item_id'])->where(['status' => ItemGallery::STATUS_ACTIVE])->andWhere(['is_primary' => 1]);
    }

    public function getRedeemCount()
    {
        return $this->getRedeem()->count();
    }

    public function insert($runValidation = true, $attributes = NULL)
    {
        return parent::insert($runValidation, $attributes);
    }

    public function update($runValidation = true, $attributes = NULL)
    {
        return parent::update($runValidation, $attributes);
    }

    public static function add(\common\forms\ItemForm $form, User $user, $is_carkee = FALSE)
    {
        $item             = new self;
        $item->user_id    = $user->user_id;
        $item->account_id = ($is_carkee) ? 0 : $user->account_id;
        $item->title      = $form->title;
        $item->content    = $form->content;
        $item->limit      = $form->limit;
        $item->status     = $form->status;
        // $item->amount     = round($form->amount, 2);        
        $item->save();

        return $item;
    }

    public function edit(\common\forms\ItemForm $form)
    {
        $this->title      = $form->title;
        $this->content    = $form->content;
        $this->limit      = $form->limit;
        // $this->status     = $form->status;
        // $this->amount     = round($form->amount, 2);        
        $this->save();
    }

    public function redeem($user)
    {
        return ItemRedeem::add($this, $user);
    }

    public function isPending()
    {
        return $this->status == self::STATUS_PENDING;
    }

    public function isApproved()
    {
        return $this->status == self::STATUS_APPROVED;
    }

    public function isRejected()
    {
        return $this->status == self::STATUS_REJECTED;
    }

    public function isDeleted()
    {
        return $this->status == self::STATUS_DELETED;
    }

    public static function statuses()
    {
        return [
            self::STATUS_PENDING  => 'Pending',
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_REJECTED => 'Rejected',
            self::STATUS_DELETED  => 'Deleted',
        ];
    }

    public function featuredPhoto()
    {
        if ($this->primaryPhoto) {
            return $this->primaryPhoto->filelink();
        } elseif($this->firstGallery) {
            return $this->firstGallery->filelink();
        } else {
            return NULL;
        }
    }

    public function data()
    {
        $data = $this->attributes;

        unset($data['account_id']);
        
        $data['status_value']  = $this->status();
        $data['redeem_count']  = $this->redeemCount;
        $data['pretty_amount'] = '$'. ($this->amount + 0);
        $data['amount']        = number_format($this->amount, 2, '.', '');
        $data['primary_photo'] = $this->featuredPhoto();
        $data['gallery']       = [];

        if ($this->gallery) {
            foreach($this->gallery as $gallery){
                $data['gallery'][] = [
                    'id' => $gallery->gallery_id,
                    'url' => $gallery->filelink(),
                ];                
            }
        }

        $data['vendor_info'] = $this->user->vendorData();

        return $data;
    }

    public function status()
    {
        $statuses = self::statuses();

        if ($this->isPending()) {
            return (!$this->approved_by) ? 'Pending - Approval' : 'Pending - Confirmation';
        }

        return isset($statuses[$this->status]) ? $statuses[$this->status] : NULL;
    }

    public function statusClass()
    {
        if ($this->isPending()) return 'text-primary';
        elseif ($this->isApproved()) return 'text-success';
        elseif ($this->isRejected()) return 'text-warning';
        elseif ($this->isDeleted()) return 'text-danger';
        else return NULL;
    }

    public static function findByID($item_id, $account_id)
    {
        return static::findOne(['item_id' => $item_id, 'account_id' => $account_id]);
    }

    public function vendor()
    {
        return $this->user->vendor_name;
    }

    public function isCarkeeItem()
    {
        return $this->account_id == 0;
    }
}