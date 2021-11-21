<?php
namespace common\forms;

use Yii;
use yii\base\Model;
use common\models\Item;
use common\helpers\Common;

class ItemForm extends Model
{
    public $item_id = 0;
    public $title;
    public $content;
    public $limit = 999999;
    public $amount = 0;
    public $status = 1;
    public $imageFiles;

    public function rules()
    {
        return [
            ['item_id', 'required', 'on' => ['edit']],
            [['title', 'content'], 'required', 'on' => ['add', 'edit']],
            [['amount', 'status', 'item_id'], 'safe'],
            // [['imageFiles'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, jpeg', 'maxFiles' => 10, 'on' => ['add']],
            // [['imageFiles'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg', 'maxFiles' => 10, 'on' => ['edit']],
            
            // ['amount', 'compare','operator'=>'>=','compareValue'=> 0 , 'message'=> Yii::t('app','Must be greater than or equal to zero'), 'on' => ['add', 'edit']],
            ['limit', 'compare','operator'=>'>=','compareValue'=> 0 , 'message'=> Yii::t('app','Must be greater than zero'), 'on' => ['add', 'edit']],
            // ['status', 'validateStatus', 'on' => ['add', 'edit']],
        ];
    }

    public function validateStatus($attr)
    {
        if (!array_key_exists($this->status, Item::statuses())){
            $this->addError($attr, 'Invalid status.');
        }
    }

}