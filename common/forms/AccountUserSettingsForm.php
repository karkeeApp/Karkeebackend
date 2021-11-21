<?php
namespace common\forms;

use common\models\AccountUser;
use Yii;
use yii\base\Model;
use common\models\User;
use common\helpers\Common;

/**
 * Login form
 */
class AccountUserSettingsForm extends Model
{
    public $account_id;
    public $cut_off;
    public $working_days;

    public $working_mon;
    public $working_tue;
    public $working_wed;
    public $working_thu;
    public $working_fri;
    public $working_sat;
    public $working_sun;

    public $leave_full;
    public $leave_half;
    public $leave_quarter;

    public $salary_date;
    public $loan_cut_off;
    public $salary_tax;

    public function rules()
    {
        return [
            [['cut_off', ], 'trim'],
            [['cut_off', ], 'required', 'on' => ['admin_add', 'user_add', 'add']],
            [['account_id', 'cut_off', 'working_mon', 'working_tue', 'working_wed', 'working_thu', 'working_fri', 'working_sat', 'working_sun','leave_full','leave_half','leave_quarter','salary_date','loan_cut_off','salary_tax'], 'safe'],
        ];
    }

    public static function tax_payable()
    {
        return [
            AccountUser::STAFF   => 'Staff',
            AccountUser::COMPANY => 'Company',
        ];
    }

    public function attributeLabels()
    {
        return [
            'cut_off'      => Yii::t('app', 'Cut off every (%day) of the month'),

            'working_mon' => 'Monday', 
            'working_tue' => 'Tuesday', 
            'working_wed' => 'Wednesday', 
            'working_thu' => 'Thursday', 
            'working_fri' => 'Friday', 
            'working_sat' => 'Saturday', 
            'working_sun' => 'Sunday',
        ];
    }
}
