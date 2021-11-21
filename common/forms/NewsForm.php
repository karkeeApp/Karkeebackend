<?php
namespace common\forms;

use Yii;
use yii\base\Model;
use common\helpers\Common;
use common\models\News;

/**
 * Login form
 */
class NewsForm extends Model
{
    public $news_id;
    public $content;
    public $title;
    public $category_id;
    public $summary;
    public $image;
    public $order = 0;
    public $is_news;
    public $is_guest;
    public $is_trending;
    public $is_event;
    public $is_happening;
    public $is_public;
    public $account_id = 0;
    public $notification_type;

    public function rules()
    {
        return [
            [['account_id','notification_type'], 'safe'],
            [['title'], 'trim'],
            [['title', 'summary'], 'string','max'=>255],
            [['content', 'title', 'summary'], 'required', 'on' => ['account_add', 'account_edit', 'admin_add', 'admin_edit', 'admin-carkee-add', 'admin-carkee-edit']],
            ['image', 'file', 'skipOnEmpty' => TRUE, 'extensions' => 'png, jpg, jpeg', 'maxSize' => 1024 * 1024 * 20, 'on' => ['account_add', 'admin_add', 'admin-carkee-add']],
            ['image', 'file', 'skipOnEmpty' => TRUE, 'extensions' => 'png, jpg, jpeg', 'maxSize' => 1024 * 1024 * 20, 'on' => ['account_edit', 'admin_edit', 'admin-carkee-edit']],
            [['news_id', 'content', 'title', 'summary', 'category_id', 'order', 'is_news', 'is_guest', 'is_trending', 'is_event', 'is_happening', 'is_public'], 'safe'],
            [['news_id', 'order', 'is_news', 'is_guest', 'is_trending', 'is_event', 'is_happening', 'is_public'], 'required', 'on' => ['set-default-settings']],
            [['news_id'], 'validateIfNewsBelongsToUser', 'on' => ['set-public']],            
            ['is_public', 'default', 'value' => 0],
        ];
    }

    public function attributeLabels()
    {
        return [
            'image'        => 'Featured Image',
            'category_id'  => 'Category',
            'is_guest'     => 'Guest',
            'is_news'      => 'News',
            'is_trending'  => 'Trending',
            'is_event'     => 'Events',
            'is_happening' => "What's Happening",
            'is_public'    => 'Enable Public View',
        ];
    }

    public function validateIfNewsBelongsToUser($attr)
    {
        $user = Yii::$app->user->identity;

        if (!$user) {
            $this->addError('id', 'Your request was made with invalid credentials');
            return;
        }

        $news = News::findOne($this->news_id);

        if (!$news) {
            $this->addError('news_id', 'News not found');
            return;
        } else if($news->created_by != $user->user_id && !$user->isAdministrator()){
            $this->addError('news_id', 'Unauthorized');
            return;
        }
    }
}
