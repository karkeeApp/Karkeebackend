<?php
namespace common\forms;

use Yii;
use yii\base\Model;
use common\helpers\Common;
use common\models\UserFile;

class FileForm extends Model
{
    public $file_id;
    public $type;
    public $filename;
    public $decription;

    public function rules()
    {
        return [
            [['type'], 'trim'],
            [['type', 'filename'], 'required'],
            [['type', 'filename'], 'required', 'on' => ['add', 'admin_add', 'account_add']],
            [['file_id', 'type', 'filename','decription'], 'safe'],
        ];
    }

    public static function statuses()
    {
        return [
            UserFile::TYPE_NATIONAL_ID           => 'National ID',
            UserFile::TYPE_FAMILY_BOOK           => 'ID / Family book',
            UserFile::TYPE_BIRTH_CERTIFICATE     => 'Childs Birth Certificate',
            UserFile::TYPE_EDUCATION_CERTIFICATE => 'Education Certificate',
            UserFile::TYPE_EMPLOYMENT_AGREEMENT  => 'Employment agreement',
            UserFile::TYPE_PAYSLIP               => 'Salary payslip',
            UserFile::TYPE_LAND_TITLE            => 'Rental Agreement / Land Title',
            UserFile::TYPE_OTHER           => 'Other',
        ];
    }

    public static function statuses2()
    {
        return [
            'Personal Profile' => [
                UserFile::TYPE_FAMILY_BOOK       => 'ID / Family book',
                UserFile::TYPE_BIRTH_CERTIFICATE => 'Birth Certificate',
            ],
            'Education' => [
                UserFile::TYPE_EDUCATION_CERTIFICATE => 'Certificate',
            ], 
            'Other Documents' => [
                UserFile::TYPE_EMPLOYMENT_AGREEMENT => 'Employment agreement',
                UserFile::TYPE_LAND_TITLE           => 'Rental Agreement / Land Title',
                UserFile::TYPE_OTHER           => 'Other',
            ]
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $this->filename->saveAs('uploads/identity/' . $this->imageFile->baseName . '.' . $this->imageFile->extension);
            return true;
        } else {
            return false;
        }
    }
}
