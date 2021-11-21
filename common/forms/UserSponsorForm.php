<?php
namespace common\forms;

use Yii;
use yii\base\Model;
use common\helpers\Common;

/**
 * Banner Image Form
 */
class UserSponsorForm extends Model
{
    public $id;
    public $sponsor_id;
    public $user_id;

    public function rules()
    {
        return [
            [['sponsor_id','user_id'], 'required','on' => ['sponsor_add', 'sponsor_edit', 'admin_add', 'admin_edit']],
            [['id'], 'required','on' => ['sponsor_edit', 'admin_edit']],
        ];
    }
}
