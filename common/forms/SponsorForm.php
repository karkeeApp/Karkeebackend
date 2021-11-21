<?php
namespace common\forms;

use Yii;
use yii\base\Model;
use common\helpers\Common;

/**
 * Banner Image Form
 */
class SponsorForm extends Model
{
    public $id;
    public $sponsor_user_id;
    public $user_id;
    public $name;
    public $image;
    public $category;
    public $filename;
    public $description;

    public function rules()
    {
        return [
            [['name','category','description', ], 'required','on' => ['sponsor_add', 'sponsor_edit', 'admin_add', 'admin_edit']],
            [['id'], 'required','on' => ['sponsor_edit', 'admin_edit']],
            ['image', 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, jpeg', 'maxSize' => 1024 * 1024 * 20, 'on' => ['sponsor_add', 'admin_add']],
            //['image', 'file', 'extensions' => 'png, jpg, jpeg', 'maxSize' => 1024 * 1024 * 20, 'on' => ['sponsor_edit', 'admin_edit']],
             ['description', 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'filename'        => 'Sponsor Logo'
        ];
    }
}
