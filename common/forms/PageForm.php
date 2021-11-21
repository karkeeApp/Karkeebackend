<?php
namespace common\forms;

use Yii;
use yii\base\Model;
use common\models\Page;
use common\helpers\Common;

/**
 * Login form
 */
class PageForm extends Model
{
    public $page_id;
    public $name;
    public $title;
    public $content;

    public function rules()
    {
        return [
            [['name' , 'title', 'content'], 'trim'],
            [['name' , 'title', 'content'], 'required', 'on' => ['admin_add', 'admin_edit']],
            ['name', 'validateName', 'on' => ['admin_add', 'admin_edit']],
            ['page_id', 'safe'],
        ];
    }

    public function validateName($attribute, $params)
    {
        $check = Page::find()
            ->where(['name' => $this->name])
            ->andWhere(['<>', 'page_id', (int)$this->page_id])
            ->one();

        if ($check) {
            $this->addError($attribute, 'Page name already exists.');
        }
    }

}
