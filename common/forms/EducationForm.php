<?php
namespace common\forms;

use Yii;
use yii\base\Model;
use common\models\User;
use common\helpers\Common;

class EducationForm extends Model
{
    public $user_id;
    public $education_level;
    public $certificate;
    public $name_school;
    public $years;
    public $course;

    public function rules()
    {
        return [
            [['education_level','certificate','name_school', 'years','course'], 'trim'],
            [['education_level','name_school', 'years'], 'required', 'on' => ['add', 'admin_add', 'account_add']],
            [['user_id','education_level','certificate','name_school','years','course'], 'safe'],
        ];
    }
}
