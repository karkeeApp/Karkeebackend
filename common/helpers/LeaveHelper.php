<?php
namespace common\helpers;

use Yii;
use common\models\LeaveApplication;
use common\models\LeaveHoliday;

class LeaveHelper
{
	public static function calculator(\common\models\User $user, $date_from, $date_to, $definition, $hours, &$error=NULL)
	{
        $leaveSummary = $user->leaveSummary;
        $accountSettings = $user->account->settings;

        if ((!$leaveSummary->working_days OR $leaveSummary->working_days == '[]') AND $accountSettings->working_days) {
            $leaveSummary->working_days = $accountSettings->working_days;
            $leaveSummary->save();
        }
        
        $validLeaves = [];

		$workingDays = $leaveSummary->working_days;
        if (!$workingDays) $workingDays = json_encode([]);

        $workingDays = json_decode($workingDays, TRUE);

        /**
         * Validate if dates are valid
         */
        $expectedHours = 0;

        while ($date_from <= $date_to) {
            $theDay = strtolower(date('D', strtotime($date_from)));
            $holiday = LeaveHoliday::find()
                ->where(['account_id' => $user->account_id])
                ->andWhere(['holiday_date' => $date_from])
                ->one();

            if (array_key_exists($theDay, $workingDays) AND !$holiday) {
                $validLeaves[] = $date_from;

                $expectedHours += ($workingDays[$theDay] == 'half') ? LeaveApplication::WORKING_HOUR / 2 : LeaveApplication::WORKING_HOUR;
            }

            $date_from = date("Y-m-d", strtotime("+1 day", strtotime($date_from)));
        }

        $days = count($validLeaves);

        if ($days == 0) {
            $error = [
                'field' => 'date_to',
                'message' => Yii::t('app', 'Invalid date range.'),
            ];
        	return 0;
        }

        if (in_array($definition, [LeaveApplication::DEFINITION_HALF, LeaveApplication::DEFINITION_QUARTER])) {
            if ($days > 1) {
                $error = [
                    'field' => 'date_to',
                    'message' => Yii::t('app', 'Invalid date range for quater day or half day type.'),
                ];
	        	return 0;
            }

            if ($definition == LeaveApplication::DEFINITION_QUARTER AND !(int)$hours) {
                /**
                $error = [
                    'field' => 'hours',
                    'message' => Yii::t('app', 'Hours is required for quarter day type.'),
                ];
                 * **/
                $hours = LeaveApplication::WORKING_HOUR / 4;
            }

            if ($definition == LeaveApplication::DEFINITION_HALF) {
                $hours = LeaveApplication::WORKING_HOUR / 2;
            }
        } else {
            $hours = $expectedHours;
        }

        return $hours;
	}

    public static function workingHourToDay($hour)
    {   
        if (!$hour) return 0;

        return $hour / LeaveApplication::WORKING_HOUR;
    }

    public static function importHoliday($account_id, $filename, $year)
    {
        $dir = Yii::$app->params['dir_holiday_import'] . $account_id . '/';

        $fileSrc = $dir . $filename;

        $data = \moonland\phpexcel\Excel::import($fileSrc);

        $fields = [
            'holiday' => 'Name', 
            'holiday_date' => 'Date', 
        ];

        if (!empty($data)) {
            
            $getOldHoliday = LeaveHoliday::find()
                ->where(['account_id' => $account_id])
                ->andWhere(['year' => $year])
                ->all();

            foreach($getOldHoliday as $row) {
                $row->delete();
            }
            /**
             * Validate if format is correct
             */
            $row = $data[0];
            unset($row['']);

            foreach($fields as $field) {
                if (!array_key_exists($field, $row)) {
                    return FALSE;
                }
            }

            foreach($data as $count => $row) {
                unset($row['']);

                $holiday = new LeaveHoliday;

                foreach($fields as $key => $field) {
                    $holiday->{$key} = $row[$field];
                }

                $holiday->created_by = Yii::$app->user->getId();
                $holiday->account_id = $account_id;
                $holiday->year = $year;
                $holiday->save();
            }

            return TRUE;
        } else {
            return FALSE;
        }

    }
}