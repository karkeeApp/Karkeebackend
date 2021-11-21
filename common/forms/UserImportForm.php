<?php
namespace common\forms;

use Yii;
use yii\base\Model;
use common\helpers\Common;
use common\models\UserFile;

class UserImportForm extends Model
{
    public $filename;

    public function rules()
    {
        return [
            [['filename'], 'required', 'on' => ['account_add']],
            [['filename'], 'safe'],
        ];
    }
}
