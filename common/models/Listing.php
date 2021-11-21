<?php
namespace common\models;

use Yii;
use yii\helpers\Url;
use yii\db\ActiveRecord;

class Listing extends ActiveRecord
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
        return '{{%listing}}';
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
    
    public function getGallery()
    {
        return $this->hasMany(ListingGallery::class,['listing_id' => 'listing_id'])->where(['status' => ListingGallery::STATUS_ACTIVE]);
    }

    public function getFirst_gallery()
    {
        return $this->hasOne(ListingGallery::class,['listing_id' => 'listing_id'])->where(['status' => ListingGallery::STATUS_ACTIVE]);
    }

    public function getPrimaryPhoto()
    {
        return $this->hasOne(ListingGallery::class,['listing_id' => 'listing_id'])->where(['status' => ListingGallery::STATUS_ACTIVE])->andWhere(['is_primary' => 1]);
    }

    public function insert($runValidation = true, $attributes = NULL)
    {
        return parent::insert($runValidation, $attributes);
    }

    public function update($runValidation = true, $attributes = NULL)
    {
        return parent::update($runValidation, $attributes);
    }

    public static function Create(\common\forms\ListingForm $form, User $user, $is_carkee = FALSE)
    {
        $listing             = new self;
        $listing->user_id    = $user->user_id;
        $listing->account_id = ($is_carkee) ? 0 : $user->account_id;
        $listing->title      = $form->title;
        $listing->content    = $form->content;
        $listing->image      = $form->filename;
        $listing->status     = $form->status;

        $listing->save();

        /**
         * Auto set as primary if only one
         */
        // $total = self::find()->where(['listing_id'=> $listing->listing_id])->count();

        if ($listing->getGallery()->count() == 1 AND $listing->getGallery()->where(['is_primary' => 1])->count() == 0){
            ListingGallery::updateAll(['is_primary' => 0], ['listing_id' => $listing->listing_id]);
            if($listing->is_primary == 0) $listing->is_primary = 1;
            $listing->save();
            if($listing->first_gallery->is_primary == 0){
                $listing->first_gallery->is_primary = 1;
                $listing->first_gallery->save();
            }
        }else if(!$listing->first_gallery) $listing->is_primary = 1;

        return $listing;
    }

    public function UpdateList(\common\forms\ListingForm $form)
    {
        $this->title      = $form->title;
        $this->content    = $form->content;
        // $this->status     = $form->status;
                
        $this->save();

        if ($this->getGallery()->count() == 1 OR $this->getGallery()->where(['is_primary' => 1])->count() == 0){
            ListingGallery::updateAll(['is_primary' => 0], ['listing_id' => $this->listing_id]);
            if($this->is_primary == 0) $this->is_primary = 1; 
            $this->save();         

            if($this->first_gallery->is_primary == 0){
                $this->first_gallery->is_primary = 1;
                $this->first_gallery->save();
            }
        }else if(!$this->first_gallery) $this->is_primary = 1;

        return $this;
    }

    public static function add(\common\forms\ListingForm $form, User $user, $is_carkee = FALSE)
    {
        $listing             = new self;
        $listing->user_id    = $user->user_id;
        $listing->account_id = ($is_carkee) ? 0 : $user->account_id;
        $listing->title      = $form->title;
        $listing->content    = $form->content;
        $listing->status     = $form->status;
        $listing->save();

        /**
         * Auto set as primary if only one
         */
        $total = static::find([
            'user_id'    => $listing->user_id,
            'account_id' => $listing->account_id,
        ])->count();

        if ($total == 1){
            $listing->is_primary = 1;
            $listing->save();
        }

        return $listing;
    }

    public function edit(\common\forms\ListingForm $form)
    {
        $this->title      = $form->title;
        $this->content    = $form->content;
        // $this->status     = $form->status;
        $this->save();
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

    public function featuredPhoto($hash_id = NULL)
    {
        if ($this->primaryPhoto) {
            return $this->primaryPhoto->filelink($hash_id);
        } elseif($this->first_gallery) {
            return $this->first_gallery->filelink($hash_id);
        } else {
            return NULL;
        }
    }

    public function data($hash_id = NULL)
    {
        $data = $this->attributes;

        unset($data['account_id']);
        unset($data['image']);
        
        $data['image']  = $this->imageLink();
        $data['status_value']  = $this->status();
        $data['primary_photo'] = (!empty($this->image) ? $this->imageLink() : (!empty($this->first_gallery) ? $this->filelink() : ""));
       // $data['primary_photo'] = $this->featuredPhoto($hash_id);
        $data['gallery']       = [];

        if ($this->gallery) {
            foreach($this->gallery as $gallery){                
                $data['gallery'][] = [
                    'id' => $gallery->gallery_id,
                    'url' => $gallery->filelink($hash_id),
                    'is_primary' => $gallery->is_primary
                ];                
            }
        }

        $data['vendor_info'] = $this->user->vendorData();

        return $data;
    }

    public function relatedData()
    {
        $listings = self::find()->where([
            'user_id'    => $this->user_id,
            'account_id' => $this->account_id,
            'status'     => self::STATUS_APPROVED,
        ])
        ->andWhere(['<>', 'listing_id', $this->listing_id])
        ->all();

        $result = [];

        if ($listings){
            foreach($listings as $listing){
                $result[] = $listing->data();
            }
        }

        return $result;
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

    public static function findByID($listing_id, $account_id)
    {
        return static::findOne(['listing_id' => $listing_id, 'account_id' => $account_id]);
    }

    public function vendor()
    {
        return $this->user->vendor_name;
    }

    public function isCarkeeItem()
    {
        return $this->account_id == 0;
    }

    public function setPrimary()
    {
        /**
         * Reset primary
         */
        static::updateAll(['is_primary' => 0], 'user_id = ' . $this->user_id . ' AND account_id = ' . $this->account_id);

        $this->is_primary = 1;
        $this->save();
    }

    public function filelink($hash_id = NULL)
    {
        return (!empty($this->first_gallery)? Url::home(TRUE) . 'listing/gallery?id=' .$this->first_gallery->gallery_id: ''); 
    }
    public function imageLink($hash_id = NULL)
    {
        return (!empty($this->image)? Url::home(TRUE) . 'listing/image?id=' .$this->listing_id: ''); 
    }

    // public function filelink($hash_id = NULL)
    // {
    //     return ($this->image)? Url::home(TRUE) . 'listing/gallery/?id=' . $this->listing_id . "&t=" . base64_encode($this->image) . ($hash_id ? '&account_id=' . $hash_id : NULL) : NULL;
    // }
}