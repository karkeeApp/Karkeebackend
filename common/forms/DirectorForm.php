<?php
namespace common\forms;

use Yii;
use yii\base\Model;
use common\models\User;
use common\models\Account;
use common\helpers\Common;

class DirectorForm extends Model
{
    public $director_id;
    public $fullname;
    public $email;
    public $mobile_code;
    public $mobile_no;
    public $is_director    = 0;
    public $is_shareholder = 0;
    public $status         = 1;

    public function rules()
    {
        return [
            [['fullname', 'email', 'mobile_code', 'mobile_no'], 'required', 'on' => ['add-director','update-director']],
            ['mobile_no', 'number'],
            ['mobile_no', 'string', 'length' => 8],            
            ['email', 'email'],
            [['director_id'], 'required', 'on' => ['update-director','delete-director']],
        ];
    }
}