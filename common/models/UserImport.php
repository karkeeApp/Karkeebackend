<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

use common\helpers\Common;
use common\models\User;

class UserImport extends ActiveRecord
{   
    const STATUS_PENDING = 1;
    const STATUS_INVALID_FORMAT = 2;
    const STATUS_COMPLETED = 3;

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public static function tableName()
    {
        return '{{%user_import}}';
    }

    public function date()
    {
        return Common::date($this->created_at);
    }

    
    public function insert($runValidation = true, $attributes = NULL)
    {
        $this->created_at = date('Y-m-d H:i:s');
        return parent::insert($runValidation, $attributes);
    }

    public function import()
    {
        $fileSrc = Yii::$app->params['dir_staff_import'] . $this->filename;

        $data = \moonland\phpexcel\Excel::import($fileSrc);

        $fields = [
            'lastname'            => 'Surname (ENG)', 
            'firstname'           => 'Given Name (ENG)', 
            'lastname_kh'         => 'Surname (KH)', 
            'firstname_kh'        => 'Given Name (KH)', 
            'citizenship'         => 'Citizenship', 
            'birthday'            => 'Date of Birth',
            'id_type'             => 'ID Type',
            'id_number'           => 'ID Number',
            
            'gender'              => 'Gender',
            'current_address'     => 'Home Address', 
            'marital_status'      => 'Marital Status', 
            'children'            => 'No. of Children', 
            'mobile'              => 'Mobile Number', 
            
            'home_number'         => 'Contact Number (Alternate)', 
            
            'email'               => 'Email Address', 
            'department'          => 'Department in Company', 
            'job_title'           => 'Position in Company', 
            'salary'              => 'Monthly Salary',
            'currency'            => 'Currency',
            'school'              => 'Name of School',
            'education_level'     => 'Highest Education Level',
            'course'              => 'Course',
            'years'               => 'Completion Years',

            'date_employed'       => 'Start Work Date', 
            
            'nok_fullname'        => 'NOK Fullname (ENG)',
            'nok_relationship'    => 'NOK Relationship',
            'nok_home_number'     => 'NOK Mobile Number',
            'nok_mobile_number'   => 'NOK Contact Number (Alternate)', 

            'leave_annual_quota'       => 'Annual Leave',
            'leave_casual_quota'       => 'Casual Leave',
            'leave_sick_quota'         => 'Sick Leave',
            'leave_compensatory_quota' => 'Compensatory Off',
            'leave_maternity_quota'    => 'Maternity Leave',
            'leave_paternity_quota'    => 'Paternity Leave',
        ];

        if (!empty($data)) {
            /**
             * Validate if format is correct
             */
            $sheet1 = $data[0];
            $row = $sheet1[0];
            unset($row['']);

            foreach($fields as $field) {
                if (!array_key_exists($field, $row)) {
                    $this->status = self::STATUS_INVALID_FORMAT;
                    $this->save();
                    return FALSE;
                }
            }

            $fields = array_flip($fields);

            $errorRows = [];
            $successRows = [];

            foreach($sheet1 as $count => $row) {
                unset($row['']);

                if (!User::import($row, $fields)) {
                    $errorRows[] = $count;
                } else {
                    $successRows = $count;
                }
            }

            $this->error_rows = json_encode($errorRows);
            $this->success_rows = json_encode($successRows);

            $this->status = self::STATUS_COMPLETED;
            $this->save();                

            return TRUE;
        } else {
            return FALSE;
        }

    }
}