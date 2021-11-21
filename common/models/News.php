<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

use yii\helpers\Url;

use common\helpers\Common;
use common\lib\Helper;
use yii\helpers\ArrayHelper;

class News extends ActiveRecord
{    
    const CATEGORY_UNDEFINED = 0;
    const CATEGORY_NEWS      = 1;
    const CATEGORY_TRENDING  = 2;
    const CATEGORY_EVENT     = 3;
    const CATEGORY_HAPPENING = 4;
    const CATEGORY_GUEST     = 5;

    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;

    public static function tableName()
    {
        return '{{%news}}';
    }

    public function insert($runValidation = true, $attributes = NULL)
    {
        $this->created_at = date('Y-m-d H:i:s');
        $this->created_by = Yii::$app->user->getId();
        
        return parent::insert($runValidation, $attributes);
    }

    public function getAccount()
    {
        return $this->hasOne(Account::class, ['account_id' => 'account_id']);
    }

    public function getGalleries()
    {
        return $this->hasMany(NewsGallery::class, ['news_id' => 'news_id']);
    }

    public function imagelink($hash_id = NULL)
    {
        if (Common::isApi() OR Common::isCarkeeApi()) {
            return ($this->image)? Url::home(TRUE) . 'file/news?id=' . $this->news_id . "&t={$this->image}" . '&access-token=' . Yii::$app->request->get('access-token') : '';
        } else if(Common::isAccount() OR Common::isCpanel()){
            return ($this->image)? Url::home(TRUE) . 'file/news?id=' . $this->news_id . "&t={$this->image}" : '';
        }
        // return ($this->image)? Url::home(TRUE) . 'file/news?id=' . $this->news_id : '';
        return ($this->image)? Url::home(TRUE) . 'file/news/' . $this->news_id . "?t={$this->image}" . ($hash_id ? '&account_id=' . $hash_id : NULL) : NULL;
    }

    // public static function all()
    // {
    //     return ArrayHelper::map(self::categories(), 'id', 'name');
    // }

    // public static function generateSettings()
    // {
    //     Helper::generateSettings(self::all(), 'news_category');
    //     return;
    // }


    public function data($user = NULL)
    {
        $token = ($user) ? "&access-token={$user->auth_key}" : '';
        $action = !$user ? 'view' : 'view-private';

        $data = [
            'news_id'       => $this->news_id,
            'type'          => 'news',
            'title'         => $this->title,
            'content'       => $this->content,
            'created_at'    => $this->created_at,
            'is_guest'      => $this->is_guest,
            'is_news'       => $this->is_news,
            'is_trending'   => $this->is_trending,
            'is_event'      => $this->is_event,
            'is_happening'  => $this->is_happening,
            'summary'       => $this->summary,
            'image'      => $this->imagelink($this->account ? $this->account->hash_id : NULL),
            'url'        => Url::home(TRUE) . 'news/' . $action . '?news_id=' . $this->news_id . ($this->account ? '&account_id=' . $this->account->hash_id : NULL) . $token,
            'status'     => $this->status
        ];

        $data['is_public'] = ($this->is_public OR ($user AND $user->isApproved())) ? true : false; 
        $data['view_more_message'] = ($user AND !$user->isApproved()) ? 'Account is pending for approval' : NULL;
        
        $data['galleries'] = [];

        if ($this->galleries) {
            foreach ($this->galleries as $key => $gallery) {
                $data['galleries'][] = [
                    'id'  => $gallery->gallery_id,
                    'url' => $gallery->filelink(),
                ];
            }
        }
        // else{
            
        //     $data['galleries'][] = [
        //         // 'id'  => 1,
        //         'url' => $this->imagelink()
        //     ];
        // }
        return $data;
    }

    public function category()
    {
        $categories = [];

        if ($this->is_guest) $categories[] = 'Guest';
        if ($this->is_news) $categories[] = 'News';
        if ($this->is_trending) $categories[] = 'Trending';
        if ($this->is_event) $categories[] = 'Events';
        if ($this->is_happening) $categories[] = "What's Happening";

        return implode(', ', $categories);
    }

    public static function categories()
    {
        return [
            // self::CATEGORY_UNDEFINED => 'Undefined',
            self::CATEGORY_GUEST        => "Guest",
            self::CATEGORY_NEWS         => 'News',
            self::CATEGORY_TRENDING     => 'Trending',
            self::CATEGORY_EVENT        => "Events",
            self::CATEGORY_HAPPENING    => "Happening",
        ];
    }

    public function status()
    {
        return self::statuses()[$this->status];
    }

    public static function statuses()
    {
        return [
            self::STATUS_ACTIVE        => "Active",
            self::STATUS_DELETED        => "Deleted"
        ];
    }
    
    public function isCarkeeNews()
    {
        return $this->account_id == 0;
    }


    // public static function create(\common\forms\NewsForm $form, $user_id)
    // {
    //     $news              = new self;
    //     $news->created_by  = $user_id;
    //     $news->title       = $form->title;
    //     $news->content     = $form->content;
    //     $news->account_id  = $form->account_id;
    //     $news->category_id = $form->category_id;
    //     $news->summary     = $form->summary;
    //     $news->order       = $form->order;
    //     $news->is_news     = $form->is_news;
    //     $news->is_guest    = $form->is_guest;
    //     $news->is_trending = $form->is_trending;
    //     $news->is_event    = $form->is_event;
    //     $news->is_happening= $form->is_happening;
    //     $news->is_public   = $form->is_public;
        
    //     $news->save();

    //     return $news;
    // }
}